<?php

namespace SavvyWombat\Caxton\Middleware;

use SavvyWombat\Caxton\Config;
use SavvyWombat\Caxton\ContentFileFilter;
use SavvyWombat\Caxton\Markdown\MarkdownConverter;
use SavvyWombat\Caxton\Site;
use SavvyWombat\Caxton\File;

class ScanFiles implements Middleware
{
    protected MarkdownConverter $markdownConverter;
    protected ?Site $site = null;

    public function __construct() {
        $this->markdownConverter = new MarkdownConverter();
    }

    public function run(Site $site, callable $next): Site
    {
        $this->site = $site;
        $this->scanPublicFiles();
        $this->scanContentFiles();

        return $next($this->site);
    }

    protected function scanPublicFiles(): void
    {
        $publicFiles = new \RecursiveIteratorIterator(
            new ContentFileFilter(
                new \RecursiveDirectoryIterator(
                    Config::instance()->get('paths.public'),
                    \FilesystemIterator::SKIP_DOTS
                )
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($publicFiles as $publicFile) {
            if (! is_dir($publicFile)) {
                $sourcePath = $publicFile->getRealPath();
                $outputPath = str_replace(
                    Config::instance()->get('paths.public'),
                    '',
                    $sourcePath
                );

                $this->site->addFile(
                    new File($sourcePath, $outputPath)
                );
            }
        }
    }

    protected function scanContentFiles(): void
    {
        $contentFiles = new \RecursiveIteratorIterator(
            new ContentFileFilter(
                new \RecursiveDirectoryIterator(
                    Config::instance()->get('paths.content'),
                    \FilesystemIterator::SKIP_DOTS
                )
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($contentFiles as $contentFile) {
            if (! is_dir($contentFile)) {
                $sourcePath = $contentFile->getRealPath();
                $outputPath = str_replace(
                    Config::instance()->get('paths.content'),
                    '',
                    $sourcePath
                );

                $data = [];

                $extensions = str_replace('.', '\.', Config::instance()->get('blade.extensions'));
                if (preg_match('#(.*)' . $extensions . '$#', $outputPath, $matches)) {
                    $frontMatter = $this->markdownConverter->extractFrontMatter(
                        file_get_contents($sourcePath)
                    );

                    $data = yaml_parse($frontMatter) ?? [];

                    $outputPath = $matches[1] . '.html';

                    $this->site->addMap($this->buildMap($outputPath, $data));
                }

                $this->site->addFile(
                    new File($sourcePath, $outputPath, $data)
                );
            }
        }
    }

    protected function buildMap(string $path, array $data): ?array
    {
        $maps = Config::instance()->get('output.maps');
        list ($map, $basepath) = (function() use ($maps, $path) {
            foreach ($maps as $map) {
                $pattern = str_replace('*', '.*?', $map['path']);

                if (preg_match('#^(' . $pattern . ").*$#", $path, $matches)) {
                    return [$map, $matches[1]];
                }
            }

            return [null, $path];
        })();

        if ($map) {
            preg_match_all('#{{(.*?)}}#', $map['url'], $matches);

            $url = $map['url'];

            foreach ($matches[1] as $variable) {
                $url = str_replace('{{' . $variable . '}}', $data[trim($variable)], $url);
            }

            return ['path' => $basepath, 'url' => $url];
        }

        return [];
    }
}