<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder;

interface BlockInterface
{
    public function __toString(): string;
}
