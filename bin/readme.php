<?php

declare(strict_types=1);

use Premier\MarkdownBuilder\Builder;
use Premier\MarkdownBuilder\Markdown;

require_once \dirname(__DIR__).'/vendor/autoload.php';

$codeAndMarkdownCallback = static function (Builder $builder, string $code): void {
    $builder
        ->code('echo '.\trim($code), 'php')
        ->code(eval('use Premier\MarkdownBuilder\{Markdown, Builder, TableBuilder, BulletedListBuilder, NumberedListBuilder}; return '.$code), 'markdown');
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
            ->h4(\substr(\str_replace('_', ' ', $file), 3))
            ->callback($codeAndMarkdownCallback, \file_get_contents($dir.'/'.$file));
    }
};

$markdown = Markdown::builder()
    ->h1('Markdown Builder')
    ->p('A helper class to create markdown.')
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
    ->getMarkdown();

\file_put_contents(\dirname(__DIR__).'/README.md', $markdown);
