<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Tests;

use PHPUnit\Framework\TestCase;
use Premier\MarkdownBuilder\Builder;
use Premier\MarkdownBuilder\BulletedListBuilder;
use Premier\MarkdownBuilder\ChecklistBuilder;
use Premier\MarkdownBuilder\Markdown;
use Premier\MarkdownBuilder\NumberedListBuilder;
use Premier\MarkdownBuilder\TableBuilder;

class BuilderTest extends TestCase
{
    public function testP(): void
    {
        static::assertSame('foo bar', Markdown::builder()->p('foo bar')->getMarkdown());
    }

    public function testH1(): void
    {
        $markdown = <<<'MARKDOWN'
            foo bar
            =======
            MARKDOWN;

        $builder = Markdown::builder()->h1('foo bar');

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testH1Multiline(): void
    {
        $markdown = <<<'MARKDOWN'
            foo bar
            =======
            MARKDOWN;

        $builder = Markdown::builder()->h1('foo
        bar');

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testH2(): void
    {
        $markdown = <<<'MARKDOWN'
            foo bar
            -------
            MARKDOWN;

        $builder = Markdown::builder()->h2('foo bar');

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testH2Multiline(): void
    {
        $markdown = <<<'MARKDOWN'
            foo bar
            -------
            MARKDOWN;

        $builder = Markdown::builder()->h2('foo
        bar');

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testH3(): void
    {
        $markdown = <<<'MARKDOWN'
            ### foo bar
            MARKDOWN;

        $builder = Markdown::builder()->h3('foo bar');

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testH3Multiline(): void
    {
        $markdown = <<<'MARKDOWN'
            ### foo bar
            MARKDOWN;

        $builder = Markdown::builder()->h3('foo
        bar');

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testBlockquote(): void
    {
        $markdown = <<<'MARKDOWN'
            >  foo bar
            >     hey ho
            MARKDOWN;

        $builder = Markdown::builder()->blockquote("foo bar\n   hey ho");

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testBlockquoteComplex(): void
    {
        $expected = <<<'MARKDOWN'
            >  test
            >  ====
            >
            >  * A
            >  * B
            >  * C
            >
            >  >  test123
            >
            >  foo bar
            MARKDOWN;

        $builder = Markdown::builder()->blockquote(
            Markdown::builder()
                ->h1('test')
                ->bulletedList(['A', 'B', 'C'])
                ->blockquote('test123')
                ->p('foo bar')
                ->getMarkdown()
        );

        static::assertSame($expected, $builder->getMarkdown());
    }

    public function testBulletedList(): void
    {
        $markdown = <<<'MARKDOWN'
            * Hallo
            * foo
            * bar
            MARKDOWN;

        $builder = Markdown::builder()->bulletedList([
            'Hallo',
            'foo',
            'bar',
        ]);

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testBulletedCallableList(): void
    {
        $markdown = <<<'MARKDOWN'
            * Hallo
            * foo
            * bar
            MARKDOWN;

        $builder = Markdown::builder()->bulletedList(static function (BulletedListBuilder $builder): void {
            $builder->addLine(
                'Hallo',
                'foo',
                'bar',
            );
        });

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testBulletedListMultiline(): void
    {
        $markdown = <<<'MARKDOWN'
            * Hallo
            * foo
              bar
            * bar
            MARKDOWN;

        $builder = Markdown::builder()->bulletedList([
            'Hallo',
            "foo\nbar",
            'bar',
        ]);

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testNumberedList(): void
    {
        $markdown = <<<'MARKDOWN'
            1. Hallo
            2. foo
            3. bar
            MARKDOWN;

        $builder = Markdown::builder()->numberedList([
            'Hallo',
            'foo',
            'bar',
        ]);

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testNumberedCallableList(): void
    {
        $markdown = <<<'MARKDOWN'
            1. Hallo
            2. foo
            3. bar
            MARKDOWN;

        $builder = Markdown::builder()->numberedList(static function (NumberedListBuilder $builder): void {
            $builder->addLine(
                'Hallo',
                'foo',
                'bar',
            );
        });

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testNumberedListMultiline(): void
    {
        $markdown = <<<'MARKDOWN'
            1. Hallo
            2. foo
               geheim
            3. bar
            MARKDOWN;

        $builder = new Builder();
        $builder->numberedList([
            'Hallo',
            "foo\ngeheim",
            'bar',
        ]);
        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testMultiLists(): void
    {
        $markdown = <<<'MARKDOWN'
            1. Hallo
            2. * A
               * B
               * C
            3. bar
            MARKDOWN;

        $builder = Markdown::builder()->numberedList([
            'Hallo',
            Markdown::bulletedList([
                'A',
                'B',
                'C',
            ]),
            'bar',
        ]);

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testChecklist(): void
    {
        $markdown = <<<'MARKDOWN'
            - [ ] Hallo
            - [ ] foo
            - [X] bar
            MARKDOWN;

        $builder = Markdown::builder()->checklist([
            'Hallo' => false,
            'foo' => false,
            'bar' => true,
        ]);

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testChecklistCallable(): void
    {
        $markdown = <<<'MARKDOWN'
            - [ ] Hallo
            - [ ] foo
            - [X] bar
            MARKDOWN;

        $builder = Markdown::builder()->checklist(static function (ChecklistBuilder $builder): void {
            $builder
                ->addLine('Hallo', false)
                ->addLine('foo', false)
                ->addLine('bar', true);
        });

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testHr(): void
    {
        $markdown = <<<'MARKDOWN'
            ---------------------------------------
            MARKDOWN;

        $builder = Markdown::builder()->hr();

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testCode(): void
    {
        $markdown = <<<'MARKDOWN'
            ```bash
            apt-get install php5
            ```
            MARKDOWN;

        $builder = Markdown::builder()->code('apt-get install php5', 'bash');

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testListAndHeadline(): void
    {
        $markdown = <<<'MARKDOWN'
            * foo
            * bar

            test
            ====
            MARKDOWN;

        $builder = Markdown::builder()
            ->bulletedList([
                'foo',
                'bar',
            ])
            ->h1('test');

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testCallback(): void
    {
        $markdown = <<<'MARKDOWN'
            * foo
            * bar

            test
            ====
            MARKDOWN;

        $builder = Markdown::builder()->callback(static function (Builder $builder): void {
            $builder
                ->bulletedList([
                    'foo',
                    'bar',
                ])
                ->h1('test');
        });

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testTable(): void
    {
        $markdown = <<<'MARKDOWN'
            First Header | Second Header
            ------------ | -------------
            Content from cell 1 | Content from cell 2
            Content in the first column | Content in the second column
            MARKDOWN;

        $builder = Markdown::builder()
            ->table(
                ['First Header', 'Second Header'],
                [
                    ['Content from cell 1', 'Content from cell 2'],
                    ['Content in the first column', 'Content in the second column'],
                ]
            );

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testTableCallable(): void
    {
        $markdown = <<<'MARKDOWN'
            First Header | Second Header
            ------------ | -------------
            Content from cell 1 | Content from cell 2
            Content in the first column | Content in the second column
            MARKDOWN;

        $builder = Markdown::builder()
            ->table(
                ['First Header', 'Second Header'],
                static function (TableBuilder $builder): void {
                    $builder->addRow(
                        ['Content from cell 1', 'Content from cell 2'],
                        ['Content in the first column', 'Content in the second column'],
                    );
                },
            );

        static::assertSame($markdown, $builder->getMarkdown());
    }
}
