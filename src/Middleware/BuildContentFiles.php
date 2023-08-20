<?php

namespace SavvyWombat\Caxton\Middleware;

use SavvyWombat\Caxton\Blade\ViewFactory;
use SavvyWombat\Caxton\Config;
use SavvyWombat\Caxton\Markdown\MarkdownConverter;
use SavvyWombat\Caxton\Site;
use SavvyWombat\Caxton\File;

class BuildContentFiles implements Middleware
{
    protected $markdown = null;
    protected ?Site $site = null;

    public function __construct() {
        $this->markdown = new MarkdownConverter();
    }

    public function run(Site $site, callable $next): Site
    {
        $this->site = $site;

        $extensions = str_replace('.', '\.', Config::instance()->get('blade.extensions'));

        foreach ($site->sourceFiles() as $file) {
            if (preg_match('#(.*)' . $extensions . '$#', $file->sourcePath())) {
                $this->buildFromTemplate($file,  $file->outputPath());
            }
        }

        return $next($site);
    }

    protected function buildFromTemplate(File $sourceFile, string $outputPath)
    {
        $extensions = str_replace('.', '\.', Config::instance()->get('blade.extensions'));

        $templateName = str_replace(
            '/',
            '.',
            str_replace(
                Config::instance()->get('paths.content') . '/',
                '',
                preg_replace(
                    '#' . $extensions . '$#',
                    '',
                    $sourceFile->sourcePath(),
                ),

            )
        );

        $output = ViewFactory::instance()->make(
            str_replace('/', '.', $templateName),
            [
                'site' => $this->site,
                ...$sourceFile->data(),
            ],
        )->render();

        if (!file_exists(Config::instance()->get('paths.output') . dirname($outputPath))) {
            // directories need the 'execute/search' bit
            // permissions are subject to the umask value in the running environment
            mkdir(Config::instance()->get('paths.output') . dirname($outputPath), 0775, true);
        }

        file_put_contents(Config::instance()->get('paths.output') . $outputPath, $output);
    }
}
