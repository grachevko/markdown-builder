<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use Premier\MarkdownBuilder\BlockInterface;
use Premier\MarkdownBuilder\Markdown;

final class H1 implements BlockInterface
{
    private string $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function __toString(): string
    {
        $string = Markdown::inline($this->string);
        $string .= PHP_EOL.\str_repeat('=', \mb_strlen($string));

        return $string;
    }
}
