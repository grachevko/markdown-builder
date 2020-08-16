<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Tests;

use const PHP_EOL;
use PHPUnit\Framework\TestCase;
use Premier\MarkdownBuilder\Markdown;

final class MarkdownTest extends TestCase
{
    public function testImg(): void
    {
        static::assertSame('![Title](file.jpg)', Markdown::img('file.jpg', 'Title'));
    }

    public function testLink(): void
    {
        static::assertSame('[Title](http://example.com)', Markdown::link('http://example.com', 'Title'));
    }

    public function testBold(): void
    {
        static::assertSame('**Bold!**', Markdown::bold('Bold!'));
    }

    public function testItalic(): void
    {
        static::assertSame('*Italic!*', Markdown::italic('Italic!'));
    }

    public function testCode(): void
    {
        static::assertSame('`$var = "foo";`', Markdown::code('$var = "foo";'));
    }

    public function testInline(): void
    {
        static::assertSame('A B C', Markdown::inline('A '.PHP_EOL.'B '.PHP_EOL.'C '));
    }
}
