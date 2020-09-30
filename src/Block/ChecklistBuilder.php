<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use function array_key_last;
use function explode;
use const PHP_EOL;
use Premier\MarkdownBuilder\BlockInterface;
use function usort;

final class ChecklistBuilder implements BlockInterface
{
    /**
     * @var array<int, array{bool, string}>
     */
    private array $lines;

    /**
     * @param array<int, array{bool, string}> $lines
     */
    public function __construct(array $lines = [])
    {
        $this->lines = $lines;
    }

    public function __toString(): string
    {
        return $this->getMarkdown();
    }

    public function addLine(bool $checked, string $line): self
    {
        $this->lines[] = [$checked, $line];

        return $this;
    }

    /**
     * @param callable(array{bool, string}, array{bool, string}): int $callback
     */
    public function sort(callable $callback): self
    {
        usort($this->lines, $callback);

        return $this;
    }

    public function getMarkdown(): string
    {
        $markdown = '';

        foreach ($this->lines as $key => [$checked, $element]) {
            $lines = explode(PHP_EOL, $element);

            foreach ($lines as $i => $line) {
                if (0 === $i) {
                    $markdown .= '- ['.($checked ? 'X' : ' ').'] '.$line;
                } else {
                    $markdown .= '      '.$line;
                }

                if (array_key_last($lines) !== $i) {
                    $markdown .= PHP_EOL;
                }
            }

            if (array_key_last($this->lines) !== $key) {
                $markdown .= PHP_EOL;
            }
        }

        return $markdown;
    }
}
