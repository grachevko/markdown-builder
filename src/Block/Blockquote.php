<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use function array_map;
use function explode;
use function implode;
use Premier\MarkdownBuilder\BlockInterface;

final class Blockquote implements BlockInterface
{
    private string $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function __toString(): string
    {
        $lines = explode(PHP_EOL, $this->string);
        $newLines = array_map(static function ($line): string {
            $markdown = '>';

            if ('' !== $line) {
                $markdown .= ' ';
            }

            return $markdown.$line;
        }, $lines);

        return implode(PHP_EOL, $newLines);
    }
}
