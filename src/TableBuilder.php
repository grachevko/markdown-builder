<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder;

use function array_map;
use function implode;
use function mb_strlen;
use const PHP_EOL;
use function str_repeat;

final class TableBuilder
{
    /**
     * @var array<int, string>
     */
    private array $headers;

    /**
     * @var array<int, array<int, string>>
     */
    private array $rows = [];

    /**
     * @param array<int, string> $headers
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    public function __toString(): string
    {
        return $this->getMarkdown();
    }

    /**
     * @param array<int, string> $row
     */
    public function addRow(array ...$row): self
    {
        $this->rows = [...$this->rows, ...$row];

        return $this;
    }

    public function getMarkdown(): string
    {
        $markdown = '';

        $headers = $this->headers;
        $separator = array_map(static function (string $header): string {
            return str_repeat('-', mb_strlen($header));
        }, $headers);

        $markdown .= implode(' | ', $headers).PHP_EOL;
        $markdown .= implode(' | ', $separator).PHP_EOL;

        foreach ($this->rows as $row) {
            $markdown .= implode(' | ', $row).PHP_EOL;
        }

        return $markdown;
    }
}
