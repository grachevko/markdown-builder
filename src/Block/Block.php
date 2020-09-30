<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use Premier\MarkdownBuilder\BlockInterface;

final class Block implements BlockInterface
{
    private string $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
