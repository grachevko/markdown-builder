<?php

declare(strict_types=1);

use Premier\MarkdownBuilder\Builder;
use Premier\MarkdownBuilder\Markdown;

require_once \dirname(__DIR__).'/vendor/autoload.php';

$codeAndMarkdownCallback = static function (Builder $builder, string $code): void {
    $builder
        ->code(''.\trim($code), 'php')
        ->code(eval('use Premier\MarkdownBuilder\{Markdown, Builder, TableBuilder, BulletedListBuilder, NumberedListBuilder, ChecklistBuilder}; return '.$code), 'markdown');
};

$parseFileCallback = static function (Builder $builder, string $file) use ($codeAndMarkdownCallback): void {
    $codeAndMarkdownCallback($builder, \file_get_contents(\dirname(__DIR__).'/docs/'.$file));
};

$parseDirCallback = static function (Builder $builder, string $dir) use ($codeAndMarkdownCallback): void {
    foreach (\scandir($dir) as $file) {
        if (\in_array($file, ['.', '..'], true)) {
            continue;
        }

        $builder
            ->h4(\substr(\str_replace('_', ' ', $file), 4))
            ->callback($codeAndMarkdownCallback, \file_get_contents($dir.'/'.$file));
    }
};

Markdown::builder()
    ->badge(
        'Latest Stable Version',
        'https://poser.pugx.org/premier/markdown-builder/v',
        '//packagist.org/packages/premier/markdown-builder',
    )
    ->badge(
        'Total Downloads',
        'https://poser.pugx.org/premier/markdown-builder/downloads',
        '//packagist.org/packages/premier/markdown-builder',
    )
    ->badge(
        'Latest Unstable Version',
        'https://poser.pugx.org/premier/markdown-builder/v/unstable',
        '//packagist.org/packages/premier/markdown-builder',
    )
    ->badge(
        'License',
        'https://poser.pugx.org/premier/markdown-builder/license',
        '//packagist.org/packages/premier/markdown-builder',
    )
    ->br()
    ->h1('Markdown Builder')
    ->p('A helper class to create markdown.')
    ->p('This README.md generated by this library, check '.Markdown::link('bin/readme.php', 'bin/readme.php').' for details.')
    ->h2('Installation')
    ->code('composer require premier/markdown-builder', 'bash')
    ->h2('Usage')
    ->callback($parseFileCallback, 'usage')
    ->h1('API')
    ->p('
        The markdown builder have two kinds of elements, block and inline elements.
        Block elements will be buffered and you can get the markdown if you call the method `getMarkdown()`.
        All inline Elements get instantly the markdown output.
        ')
    ->h3('Block Elements')
    ->callback($parseDirCallback, \dirname(__DIR__).'/docs/blocks')
    ->h3('Inline Blocks')
    ->callback($parseDirCallback, \dirname(__DIR__).'/docs/inline')
    ->h3('Advanced Features')
    ->h4('Collapse block')
    ->p('if you need collapse blocks, you can create a new builder instance with his own clean buffer.')
    ->callback($parseFileCallback, 'collapse')
    ->h4('Callback')
    ->p('If you want to add blocks from complex logic or iterable value, but don\'t want to stop chain calls you can use callback.')
    ->callback($parseFileCallback, 'callback')
    ->h4('Dump to File')
    ->p('Instead of returning markdown as a string you can easy dump result to file.')
    ->code('Markdown::builder()->h1(\'Hello world!\')->dump(\'index.md\');', 'php')
    ->dump(\dirname(__DIR__).'/README.md');
