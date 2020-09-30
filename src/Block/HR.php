<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use Premier\MarkdownBuilder\BlockInterface;

final class HR implements BlockInterface
{
    public function __toString(): string
    {
        return '---------------------------------------';
    }
}
