<?php

declare(strict_types=1);

namespace Premier\MarkdownBuilder\Tests;

use PHPUnit\Framework\TestCase;
use Premier\MarkdownBuilder\Block\Builder;
use Premier\MarkdownBuilder\Block\BulletedListBuilder;
use Premier\MarkdownBuilder\Block\ChecklistBuilder;
use Premier\MarkdownBuilder\Block\NumberedListBuilder;
use Premier\MarkdownBuilder\Block\TableBuilder;
use Premier\MarkdownBuilder\Markdown;

class BuilderTest extends TestCase
{
    public function testP(): void
    {
        $markdown = <<<'MARKDOWN'
            foo bar
            MARKDOWN;

        static::assertSame($markdown, Markdown::builder()->p('foo bar')->getMarkdown());
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
            > foo bar
            >    hey ho
            MARKDOWN;

        $builder = Markdown::builder()->blockquote("foo bar\n   hey ho");

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testBlockquoteComplex(): void
    {
        $expected = <<<'MARKDOWN'
            > test
            > ====
            >
            > * A
            > * B
            > * C
            >
            > > test123
            >
            > foo bar
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
            $builder
                ->addLine('Hallo')
                ->addLine('foo')
                ->addLine('bar');
        });

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testBulletedCallableListSort(): void
    {
        $markdown = <<<'MARKDOWN'
            * A
            * B
            * C
            MARKDOWN;

        $builder = Markdown::builder()->bulletedList(static function (BulletedListBuilder $builder): void {
            $builder
                ->addLine('B')
                ->addLine('C')
                ->addLine('A')
                ->sort(fn (string $left, string $right) => $left <=> $right);
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
            $builder
                ->addLine('Hallo')
                ->addLine('foo')
                ->addLine('bar');
        });

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testNumberedCallableListSort(): void
    {
        $markdown = <<<'MARKDOWN'
            1. A
            2. B
            3. C
            MARKDOWN;

        $builder = Markdown::builder()->numberedList(static function (NumberedListBuilder $builder): void {
            $builder
                ->addLine('C')
                ->addLine('B')
                ->addLine('A')
                ->sort(fn (string $left, string $right) => $left <=> $right);
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

    public function testNumberedNestedList(): void
    {
        $markdown = <<<'MARKDOWN'
            1. One
            2. Two
               1. Two.One
               2. Two.Two
            3. Three
            MARKDOWN;

        $builder = new Builder();
        $builder->numberedList(static function (NumberedListBuilder $builder): void {
            $builder
                ->addLine('One')
                ->addLine(function (Builder $builder): void {
                    $builder
                        ->p('Two')
                        ->numberedList(['Two.One', 'Two.Two']);
                })
                ->addLine('Three');
        });

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
            [false, 'Hallo'],
            [false, 'foo'],
            [true, 'bar'],
        ]);

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testChecklistMultiline(): void
    {
        $markdown = <<<'MARKDOWN'
            - [ ] Hallo
            - [ ] 1. foo
                  2. bar
                  3. baz
            - [X] bar
            MARKDOWN;

        $builder = Markdown::builder()->checklist([
            [false, 'Hallo'],
            [false, Markdown::numberedList(['foo', 'bar', 'baz'])],
            [true, 'bar'],
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
                ->addLine(false, 'Hallo')
                ->addLine(false, 'foo')
                ->addLine(true, 'bar');
        });

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testChecklistCallableSort(): void
    {
        $markdown = <<<'MARKDOWN'
            - [ ] A
            - [ ] B
            - [X] C
            MARKDOWN;

        $builder = Markdown::builder()->checklist(static function (ChecklistBuilder $builder): void {
            $builder
                ->addLine(false, 'B')
                ->addLine(true, 'C')
                ->addLine(false, 'A')
                ->sort(fn (array $left, array $right) => $left[1] <=> $right[1]);
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
                    $builder
                        ->addRow('Content from cell 1', 'Content from cell 2')
                        ->addRow('Content in the first column', 'Content in the second column');
                },
            );

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testTableCallableSort(): void
    {
        $markdown = <<<'MARKDOWN'
            First Header | Second Header
            ------------ | -------------
            A | Content from cell A
            B | Content from cell B
            C | Content from cell C
            MARKDOWN;

        $builder = Markdown::builder()
            ->table(
                ['First Header', 'Second Header'],
                static function (TableBuilder $builder): void {
                    $builder
                        ->addRow('C', 'Content from cell C')
                        ->addRow('A', 'Content from cell A')
                        ->addRow('B', 'Content from cell B')
                        ->sort(fn (array $left, array $right) => $left[0] <=> $right[0]);
                },
            );

        static::assertSame($markdown, $builder->getMarkdown());
    }

    public function testTableWithNestedChecklist(): void
    {
        $markdown = <<<'MARKDOWN'
            First Header | Second Header
            ------------ | -------------
            A | <ul><li>- [X] A</li><li>- [ ] B</li><li>- [ ] C</li></ul>
            B | <ul><li>- [X] D</li><li>- [ ] E</li><li>- [ ] F</li></ul>
            MARKDOWN;

        $builder = Markdown::builder()
            ->table(
                ['First Header', 'Second Header'],
                static function (TableBuilder $builder): void {
                    $builder
                        ->addRow('A', Markdown::listAsHtml(Markdown::checklist([
                            [true, 'A'],
                            [false, 'B'],
                            [false, 'C'],
                        ])))
                        ->addRow('B', Markdown::listAsHtml(Markdown::checklist([
                            [true, 'D'],
                            [false, 'E'],
                            [false, 'F'],
                        ])));
                },
            );

        static::assertSame($markdown, $builder->getMarkdown());
    }
}
