<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use Premier\MarkdownBuilder\BlockInterface;
use function strpos;

final class Code implements BlockInterface
{
    private string $code;

    private string $lang;

    public function __construct(string $code, string $lang = '')
    {
        $this->code = $code;
        $this->lang = $lang;
    }

    public function __toString(): string
    {
        $backticks = '```';
        if (false !== strpos($this->code, '```')) {
            $backticks .= '`';
        }

        return $backticks.$this->lang.PHP_EOL.$this->code.PHP_EOL.$backticks;
    }
}
