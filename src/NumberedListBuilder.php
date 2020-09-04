<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder;

use function array_values;
use function explode;
use const PHP_EOL;
use function trim;
use function usort;

final class NumberedListBuilder implements Block
{
    /**
     * @var array<int, string>
     */
    private array $lines;

    /**
     * @param array<int, string> $lines
     */
    public function __construct(array $lines = [])
    {
        $this->lines = $lines;
    }

    public function __toString(): string
    {
        return $this->getMarkdown();
    }

    public function addLine(string $line): self
    {
        $this->lines[] = $line;

        return $this;
    }

    /**
     * @param callable(string, string): int $callback
     */
    public function sort(callable $callback): self
    {
        usort($this->lines, $callback);

        return $this;
    }

    public function getMarkdown(): string
    {
        $markdown = '';

        $lines = $this->lines;
        foreach (array_values($lines) as $key => $element) {
            $lines = explode(PHP_EOL, $element);

            foreach ($lines as $i => $line) {
                $line = trim($line);

                if (0 === $i) {
                    $markdown .= ($key + 1).'. '.$line.PHP_EOL;
                } else {
                    $markdown .= '   '.$line.PHP_EOL;
                }
            }
        }

        $markdown .= PHP_EOL;

        return $markdown;
    }
}
