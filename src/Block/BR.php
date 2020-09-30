<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use Premier\MarkdownBuilder\BlockInterface;

final class BR implements BlockInterface
{
    public function __toString(): string
    {
        return PHP_EOL;
    }
}
