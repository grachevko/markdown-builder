<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use Premier\MarkdownBuilder\BlockInterface;

final class TableBuilder implements BlockInterface
{
    /**
     * @var array<int, string>
     */
    private array $headers;

    /**
     * @var array<int, array<int, string>>
     */
    private array $rows;

    /**
     * @param array<int, string>             $headers
     * @param array<int, array<int, string>> $rows
     */
    public function __construct(array $headers, array $rows = [])
    {
        $this->headers = $headers;
        $this->rows = $rows;
    }

    public function __toString(): string
    {
        return $this->getMarkdown();
    }

    public function addRow(string ...$row): self
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @param callable(array<int, string>, array<int, string>): int $callback
     */
    public function sort(callable $callback): self
    {
        \usort($this->rows, $callback);

        return $this;
    }

    public function getMarkdown(): string
    {
        $markdown = '';

        $headers = $this->headers;
        $separator = \array_map(static function (string $header): string {
            return \str_repeat('-', \mb_strlen($header));
        }, $headers);

        $markdown .= \implode(' | ', $headers).PHP_EOL;
        $markdown .= \implode(' | ', $separator).PHP_EOL;

        foreach ($this->rows as $key => $row) {
            $markdown .= \implode(' | ', $row);

            if (\array_key_last($this->rows) !== $key) {
                $markdown .= PHP_EOL;
            }
        }

        return $markdown;
    }
}
