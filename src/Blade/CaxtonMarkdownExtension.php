<?php

namespace SavvyWombat\Caxton\Blade;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark as CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Delimiter\Processor\EmphasisDelimiterProcessor;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\CommonMark\Node as CoreNode;
use League\CommonMark\Parser as CoreParser;
use League\CommonMark\Renderer as CoreRenderer;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;

class CaxtonMarkdownExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('commonmark', Expect::structure([
            'use_asterisk' => Expect::bool(true),
            'use_underscore' => Expect::bool(true),
            'enable_strong' => Expect::bool(true),
            'enable_em' => Expect::bool(true),
            'unordered_list_markers' => Expect::listOf('string')->min(1)->default(['*', '+', '-'])->mergeDefaults(false),
        ]));
    }

    // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing.TooMuchSpaceAfterComma,Squiz.WhiteSpace.SemicolonSpacing.Incorrect
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addBlockStartParser(new CommonMarkCoreExtension\Parser\Block\BlockQuoteStartParser(),     70)
            ->addBlockStartParser(new CommonMarkCoreExtension\Parser\Block\HeadingStartParser(),        60)
            ->addBlockStartParser(new CommonMarkCoreExtension\Parser\Block\FencedCodeStartParser(),     50)
            ->addBlockStartParser(new CommonMarkCoreExtension\Parser\Block\HtmlBlockStartParser(),      40)
            ->addBlockStartParser(new CommonMarkCoreExtension\Parser\Block\ThematicBreakStartParser(),  20)
            ->addBlockStartParser(new CommonMarkCoreExtension\Parser\Block\ListBlockStartParser(),      10)

            ->addInlineParser(new CoreParser\Inline\NewlineParser(), 200)
            ->addInlineParser(new CommonMarkCoreExtension\Parser\Inline\BacktickParser(),    150)
            ->addInlineParser(new CommonMarkCoreExtension\Parser\Inline\EscapableParser(),    80)
            ->addInlineParser(new CommonMarkCoreExtension\Parser\Inline\EntityParser(),       70)
            ->addInlineParser(new CommonMarkCoreExtension\Parser\Inline\AutolinkParser(),     50)
            ->addInlineParser(new CommonMarkCoreExtension\Parser\Inline\HtmlInlineParser(),   40)
            ->addInlineParser(new CommonMarkCoreExtension\Parser\Inline\CloseBracketParser(), 30)
            ->addInlineParser(new CommonMarkCoreExtension\Parser\Inline\OpenBracketParser(),  20)
            ->addInlineParser(new CommonMarkCoreExtension\Parser\Inline\BangParser(),         10)

            ->addRenderer(CommonMarkCoreExtension\Node\Block\BlockQuote::class,    new CommonMarkCoreExtension\Renderer\Block\BlockQuoteRenderer(),    0)
            ->addRenderer(CoreNode\Block\Document::class,  new CoreRenderer\Block\DocumentRenderer(),  0)
            ->addRenderer(CommonMarkCoreExtension\Node\Block\FencedCode::class,    new CommonMarkCoreExtension\Renderer\Block\FencedCodeRenderer(),    0)
            ->addRenderer(CommonMarkCoreExtension\Node\Block\Heading::class,       new CommonMarkCoreExtension\Renderer\Block\HeadingRenderer(),       0)
            ->addRenderer(CommonMarkCoreExtension\Node\Block\HtmlBlock::class,     new CommonMarkCoreExtension\Renderer\Block\HtmlBlockRenderer(),     0)
            ->addRenderer(CommonMarkCoreExtension\Node\Block\IndentedCode::class,  new CommonMarkCoreExtension\Renderer\Block\IndentedCodeRenderer(),  0)
            ->addRenderer(CommonMarkCoreExtension\Node\Block\ListBlock::class,     new CommonMarkCoreExtension\Renderer\Block\ListBlockRenderer(),     0)
            ->addRenderer(CommonMarkCoreExtension\Node\Block\ListItem::class,      new CommonMarkCoreExtension\Renderer\Block\ListItemRenderer(),      0)
            ->addRenderer(CoreNode\Block\Paragraph::class, new CoreRenderer\Block\ParagraphRenderer(), 0)
            ->addRenderer(CommonMarkCoreExtension\Node\Block\ThematicBreak::class, new CommonMarkCoreExtension\Renderer\Block\ThematicBreakRenderer(), 0)

            ->addRenderer(CommonMarkCoreExtension\Node\Inline\Code::class,        new CommonMarkCoreExtension\Renderer\Inline\CodeRenderer(),        0)
            ->addRenderer(CommonMarkCoreExtension\Node\Inline\Emphasis::class,    new CommonMarkCoreExtension\Renderer\Inline\EmphasisRenderer(),    0)
            ->addRenderer(CommonMarkCoreExtension\Node\Inline\HtmlInline::class,  new CommonMarkCoreExtension\Renderer\Inline\HtmlInlineRenderer(),  0)
            ->addRenderer(CommonMarkCoreExtension\Node\Inline\Image::class,       new CommonMarkCoreExtension\Renderer\Inline\ImageRenderer(),       0)
            ->addRenderer(CommonMarkCoreExtension\Node\Inline\Link::class,        new CommonMarkCoreExtension\Renderer\Inline\LinkRenderer(),        0)
            ->addRenderer(CoreNode\Inline\Newline::class, new CoreRenderer\Inline\NewlineRenderer(), 0)
            ->addRenderer(CommonMarkCoreExtension\Node\Inline\Strong::class,      new CommonMarkCoreExtension\Renderer\Inline\StrongRenderer(),      0)
            ->addRenderer(CoreNode\Inline\Text::class,    new CoreRenderer\Inline\TextRenderer(),    0)
        ;

        if ($environment->getConfiguration()->get('commonmark/use_asterisk')) {
            $environment->addDelimiterProcessor(new EmphasisDelimiterProcessor('*'));
        }

        if ($environment->getConfiguration()->get('commonmark/use_underscore')) {
            $environment->addDelimiterProcessor(new EmphasisDelimiterProcessor('_'));
        }
    }
}
