<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Block;

use function file_put_contents;
use function implode;
use function is_callable;
use const PHP_EOL;
use Premier\MarkdownBuilder\BlockInterface;
use Premier\MarkdownBuilder\Markdown;

/**
 * @ internal
 */
final class Builder implements BlockInterface
{
    /**
     * @var BlockInterface[]
     */
    private array $blocks = [];

    public function __toString(): string
    {
        return $this->getMarkdown();
    }

    public function p(string $text): self
    {
        $this->blocks[] = new Block($text);

        return $this;
    }

    public function h1(string $header): self
    {
        $this->blocks[] = new H1($header);

        return $this;
    }

    public function h2(string $header): self
    {
        $this->blocks[] = new H2($header);

        return $this;
    }

    public function h3(string $header): self
    {
        $this->blocks[] = new H3($header);

        return $this;
    }

    public function h4(string $header): self
    {
        $this->blocks[] = new H4($header);

        return $this;
    }

    public function h5(string $header): self
    {
        $this->blocks[] = new H5($header);

        return $this;
    }

    public function h6(string $header): self
    {
        $this->blocks[] = new H6($header);

        return $this;
    }

    public function blockquote(string $text): self
    {
        $this->blocks[] = new Blockquote($text);

        return $this;
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
     * @param array<int, array{bool, string}>|callable $list
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
        $this->blocks[] = new HR();

        return $this;
    }

    public function code(string $code, string $lang = ''): self
    {
        $this->blocks[] = new Code($code, $lang);

        return $this;
    }

    public function br(): self
    {
        $this->blocks[] = new BR();

        return $this;
    }

    public function getMarkdown(bool $inline = false): string
    {
        $glue = PHP_EOL.PHP_EOL;

        if ($inline) {
            $glue = PHP_EOL;
        }

        return implode($glue, $this->blocks);
    }

    /**
     * @param mixed $args
     */
    public function inline(callable $callback, ...$args): self
    {
        $builder = Markdown::builder();

        $callback($builder, ...$args);

        $this->blocks[] = new InlineBuilder($builder);

        return $this;
    }

    /**
     * @param mixed $args
     */
    public function callback(callable $callback, ...$args): self
    {
        $builder = Markdown::builder();

        $callback($builder, ...$args);

        $this->blocks[] = $builder;

        return $this;
    }

    /**
     * @param array<int, string>                      $headers
     * @param array<int, array<int, string>>|callable $values
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
        $this->blocks[] = new Block(Markdown::link($url, Markdown::img($img, $title)));

        return $this;
    }
}
