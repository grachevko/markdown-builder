<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder;

use function explode;
use const PHP_EOL;

final class BulletedListBuilder implements Block
{
    /**
     * @var array<int, string>
     */
    private array $lines = [];

    public function __toString(): string
    {
        return $this->getMarkdown();
    }

    public function addLine(string ...$line): self
    {
        $this->lines = [...$this->lines, ...$line];

        return $this;
    }

    public function getMarkdown(): string
    {
        $markdown = '';

        $lines = $this->lines;
        foreach ($lines as $element) {
            $lines = explode(PHP_EOL, $element);

            foreach ($lines as $i => $line) {
                if (0 === $i) {
                    $markdown .= '* '.$line.PHP_EOL;
                } else {
                    $markdown .= '  '.$line.PHP_EOL;
                }
            }
        }

        $markdown .= PHP_EOL;

        return $markdown;
    }
}
