<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use function array_key_last;
use function array_values;
use function explode;
use function is_string;
use const PHP_EOL;
use Premier\MarkdownBuilder\BlockInterface;
use Premier\MarkdownBuilder\Markdown;
use function usort;

final class NumberedListBuilder implements BlockInterface
{
    /**
     * @var array<int, BlockInterface>
     */
    private array $lines = [];

    /**
     * @param array<int, string|callable> $lines
     */
    public function __construct(array $lines = [])
    {
        foreach ($lines as $line) {
            $this->addLine($line);
        }
    }

    public function __toString(): string
    {
        return $this->getMarkdown();
    }

    /**
     * @param string|callable $line
     */
    public function addLine($line): self
    {
        if (is_string($line)) {
            $this->lines[] = new Block($line);
        } else {
            $builder = Markdown::builder();

            $line($builder);

            $this->lines[] = new InlineBuilder($builder);
        }

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

        foreach (array_values($this->lines) as $key => $element) {
            $lines = explode(PHP_EOL, (string) $element);

            foreach ($lines as $i => $line) {
                if (0 === $i) {
                    $markdown .= ($key + 1).'. '.$line;
                } else {
                    $markdown .= '   '.$line;
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
