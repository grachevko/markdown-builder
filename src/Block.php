<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder;

interface Block
{
    public function __toString(): string;
}
