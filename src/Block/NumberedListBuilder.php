<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use Premier\MarkdownBuilder\BlockInterface;

final class NumberedListBuilder implements BlockInterface
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
        \usort($this->lines, $callback);

        return $this;
    }

    public function getMarkdown(): string
    {
        $markdown = '';

        foreach (\array_values($this->lines) as $key => $element) {
            $lines = \explode(PHP_EOL, $element);

            foreach ($lines as $i => $line) {
                if (0 === $i) {
                    $markdown .= ($key + 1).'. '.$line;
                } else {
                    $markdown .= '   '.$line;
                }

                if (\array_key_last($lines) !== $i) {
                    $markdown .= PHP_EOL;
                }
            }

            if (\array_key_last($this->lines) !== $key) {
                $markdown .= PHP_EOL;
            }
        }

        return $markdown;
    }
}
