<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder;

use function array_map;
use function array_values;
use function explode;
use function implode;
use function mb_strlen;
use const PHP_EOL;
use function str_repeat;
use function strpos;
use function trim;

/**
 * @ internal
 */
final class Builder
{
    /**
     * @var string[]
     */
    private array $blocks = [];

    public function __toString(): string
    {
        return $this->getMarkdown();
    }

    public function p(string $text): self
    {
        $this->blocks[] = trim($text).PHP_EOL;

        $this->blocks[] = PHP_EOL;

        return $this;
    }

    public function h1(string $header): self
    {
        $header = Markdown::inline($header);

        $this->blocks[] = $header.PHP_EOL;
        $this->blocks[] = str_repeat('=', mb_strlen($header)).PHP_EOL;

        $this->blocks[] = PHP_EOL;

        return $this;
    }

    public function h2(string $header): self
    {
        $header = Markdown::inline($header);

        $this->blocks[] = $header.PHP_EOL;
        $this->blocks[] = str_repeat('-', mb_strlen($header)).PHP_EOL;

        $this->blocks[] = PHP_EOL;

        return $this;
    }

    public function h3(string $header): self
    {
        $this->blocks[] = '### '.Markdown::inline($header).PHP_EOL;

        $this->blocks[] = PHP_EOL;

        return $this;
    }

    public function h4(string $header): self
    {
        $this->blocks[] = '#### '.Markdown::inline($header).PHP_EOL;

        $this->blocks[] = PHP_EOL;

        return $this;
    }

    public function h5(string $header): self
    {
        $this->blocks[] = '##### '.Markdown::inline($header).PHP_EOL;

        $this->blocks[] = PHP_EOL;

        return $this;
    }

    public function h6(string $header): self
    {
        $this->blocks[] = '###### '.Markdown::inline($header).PHP_EOL;

        $this->blocks[] = PHP_EOL;

        return $this;
    }

    public function blockquote(string $text): self
    {
        $lines = explode(PHP_EOL, $text);
        $newLines = array_map(static function ($line): string {
            return trim('>  '.$line);
        }, $lines);

        $content = implode(PHP_EOL, $newLines);

        return $this->p($content);
    }

    /**
     * @param array<int, string> $list
     */
    public function bulletedList(array $list): self
    {
        foreach ($list as $element) {
            $lines = explode(PHP_EOL, $element);

            foreach ($lines as $i => $line) {
                if (0 === $i) {
                    $this->blocks[] = '* '.$line.PHP_EOL;
                } else {
                    $this->blocks[] = '  '.$line.PHP_EOL;
                }
            }
        }

        $this->blocks[] = PHP_EOL;

        return $this;
    }

    /**
     * @param array<int, string> $list
     */
    public function numberedList(array $list): self
    {
        foreach (array_values($list) as $key => $element) {
            $lines = explode(PHP_EOL, $element);

            foreach ($lines as $i => $line) {
                $line = trim($line);

                if (0 === $i) {
                    $this->blocks[] = ($key + 1).'. '.$line.PHP_EOL;
                } else {
                    $this->blocks[] = '   '.$line.PHP_EOL;
                }
            }
        }

        $this->blocks[] = PHP_EOL;

        return $this;
    }

    public function hr(): self
    {
        return $this->p('---------------------------------------');
    }

    public function code(string $code, string $lang = ''): self
    {
        $backticks = '```';
        if (false !== strpos($code, '```')) {
            $backticks .= '`';
        }

        $this->blocks[] = $backticks.$lang.PHP_EOL;
        $this->blocks[] = $code.PHP_EOL;
        $this->blocks[] = $backticks.PHP_EOL;

        $this->blocks[] = PHP_EOL;

        return $this;
    }

    public function br(): self
    {
        $this->blocks[] = PHP_EOL;

        return $this;
    }

    public function getMarkdown(): string
    {
        return trim(implode('', $this->blocks));
    }

    /**
     * @param mixed $args
     */
    public function callback(callable $callback, ...$args): self
    {
        $callback($this, ...$args);

        return $this;
    }

    /**
     * @param array<int, string>   $headers
     * @param iterable<int, array> $values
     */
    public function table(array $headers, iterable $values): self
    {
        $this->blocks[] = (new TableBuilder($headers))->addRow(...$values);

        return $this;
    }
}
