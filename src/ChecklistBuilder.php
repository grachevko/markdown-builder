<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder;

use function explode;
use const PHP_EOL;

final class ChecklistBuilder implements Block
{
    /**
     * @var array<int, array{string, bool}>
     */
    private array $lines = [];

    public function __toString(): string
    {
        return $this->getMarkdown();
    }

    public function addLine(string $line, bool $checked): self
    {
        $this->lines[] = [$line, $checked];

        return $this;
    }

    public function getMarkdown(): string
    {
        $markdown = '';

        foreach ($this->lines as [$element, $checked]) {
            $lines = explode(PHP_EOL, $element);

            foreach ($lines as $i => $line) {
                if (0 === $i) {
                    $markdown .= '- ['.($checked ? 'X' : ' ').'] '.$line.PHP_EOL;
                } else {
                    $markdown .= '      '.$line.PHP_EOL;
                }
            }
        }

        $markdown .= PHP_EOL;

        return $markdown;
    }
}
