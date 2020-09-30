<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use Premier\MarkdownBuilder\BlockInterface;

final class InlineBuilder implements BlockInterface
{
    private Builder $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function __toString(): string
    {
        return $this->builder->getMarkdown(true);
    }
}
