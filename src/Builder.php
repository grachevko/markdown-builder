<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder;

use function array_map;
use function explode;
use function file_put_contents;
use function implode;
use function is_callable;
use function mb_strlen;
use const PHP_EOL;
use function str_repeat;
use function strpos;
use function trim;

/**
 * @ internal
 */
final class Builder implements Block
{
    /**
     * @var string[]|Block[]
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
     * @param array<int, string>|callable $list
     */
    public function bulletedList($list): self
    {
        if (is_callable($list)) {
            $list($this->blocks[] = new BulletedListBuilder());

            return $this;
        }

        $this->blocks[] = new BulletedListBuilder($list);

        return $this;
    }

    /**
     * @param array<int, string>|callable $list
     */
    public function numberedList($list): self
    {
        if (is_callable($list)) {
            $list($this->blocks[] = new NumberedListBuilder());

            return $this;
        }

        $this->blocks[] = new NumberedListBuilder($list);

        return $this;
    }

    /**
     * @param array<int, array{string, bool}>|callable $list
     */
    public function checklist($list): self
    {
        if (is_callable($list)) {
            $list($this->blocks[] = new ChecklistBuilder());

            return $this;
        }

        $this->blocks[] = new ChecklistBuilder($list);

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
        $builder = Markdown::builder();

        $callback($builder, ...$args);

        $this->blocks[] = $builder;
        $this->blocks[] = PHP_EOL;
        $this->blocks[] = PHP_EOL;

        return $this;
    }

    /**
     * @param array<int, string>         $headers
     * @param array<int, array>|callable $values
     */
    public function table(array $headers, $values): self
    {
        if (is_callable($values)) {
            $this->blocks[] = $tableBuilder = new TableBuilder($headers);

            $values($tableBuilder);

            return $this;
        }

        $this->blocks[] = $tableBuilder = new TableBuilder($headers, $values);

        return $this;
    }

    public function dump(string $file): self
    {
        file_put_contents($file, $this->getMarkdown());

        return $this;
    }

    public function badge(string $title, string $img, string $url): self
    {
        $this->blocks[] = Markdown::link($url, Markdown::img($img, $title));

        $this->blocks[] = PHP_EOL;

        return $this;
    }
}
