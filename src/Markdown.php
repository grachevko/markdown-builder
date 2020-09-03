<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder;

use const PHP_EOL;
use function preg_replace;
use function sprintf;
use function str_replace;
use function trim;

final class Markdown
{
    private function __construct()
    {
    }

    public static function builder(): Builder
    {
        return new Builder();
    }

    public static function bold(string $string): string
    {
        return sprintf('**%s**', $string);
    }

    public static function link(string $url, string $title): string
    {
        return sprintf('[%s](%s)', $title, $url);
    }

    public static function img(string $url, string $title): string
    {
        return sprintf('![%s](%s)', $title, $url);
    }

    public static function code(string $code): string
    {
        return sprintf('`%s`', $code);
    }

    public static function italic(string $string): string
    {
        return sprintf('*%s*', $string);
    }

    public static function inline(string $string): string
    {
        $string = str_replace(PHP_EOL, '', $string);
        $string = (string) preg_replace('/\s+/', ' ', $string);

        return trim($string);
    }

    /**
     * @param array<int, string>|callable $list
     */
    public static function bulletedList($list): string
    {
        return self::builder()
            ->bulletedList($list)
            ->getMarkdown();
    }

    /**
     * @param array<int, string>|callable $list
     */
    public static function numberedList($list): string
    {
        return self::builder()
            ->numberedList($list)
            ->getMarkdown();
    }

    /**
     * @param array<string, bool>|callable $list
     */
    public static function checklist($list): string
    {
        return self::builder()
            ->checklist($list)
            ->getMarkdown();
    }
}
