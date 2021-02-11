<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Tests;

use PHPUnit\Framework\TestCase;
use Premier\MarkdownBuilder\Markdown;

final class MarkdownTest extends TestCase
{
    public function testImg(): void
    {
        self::assertSame('![Title](file.jpg)', Markdown::img('file.jpg', 'Title'));
    }

    public function testLink(): void
    {
        self::assertSame('[Title](http://example.com)', Markdown::link('http://example.com', 'Title'));
    }

    public function testBold(): void
    {
        self::assertSame('**Bold!**', Markdown::bold('Bold!'));
    }

    public function testItalic(): void
    {
        self::assertSame('*Italic!*', Markdown::italic('Italic!'));
    }

    public function testCode(): void
    {
        self::assertSame('`$var = "foo";`', Markdown::code('$var = "foo";'));
    }

    public function testInline(): void
    {
        self::assertSame('A B C', Markdown::inline('A '.PHP_EOL.'B '.PHP_EOL.'C '));
    }

    public function testListAsHtml(): void
    {
        self::assertSame('<ul><li>A </li><li>B </li><li>C </li></ul>', Markdown::listAsHtml('A '.PHP_EOL.'B '.PHP_EOL.'C '));
    }
}
