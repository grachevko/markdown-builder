[![Latest Stable Version](https://poser.pugx.org/premier/markdown-builder/v)](//packagist.org/packages/premier/markdown-builder)
[![Total Downloads](https://poser.pugx.org/premier/markdown-builder/downloads)](//packagist.org/packages/premier/markdown-builder)
[![Latest Unstable Version](https://poser.pugx.org/premier/markdown-builder/v/unstable)](//packagist.org/packages/premier/markdown-builder)
[![License](https://poser.pugx.org/premier/markdown-builder/license)](//packagist.org/packages/premier/markdown-builder)

Markdown Builder
================

A helper class to create markdown.

This README.md generated by this library, check [bin/readme.php](bin/readme.php) for details.

Installation
------------

```bash
composer require premier/markdown-builder
```

Usage
-----

```php
Markdown::builder()
    ->h1('Markdown Builder')
    ->p('A helper class to create markdown.')
    ->h2('Install '.Markdown::bold('this').' powerfull library')
    ->code('composer require premier/markdown-builder', 'bash')
    ->h2('Todos')
    ->checklist([
        'write tests' => true,
        Markdown::numberedList(['TableBuilder', 'ListBuilders', 'Checklist']) => true,
        'add more markdown features' => false,
        'Configure CI' => false,
    ])
    ->getMarkdown();
```

````markdown
Markdown Builder
================

A helper class to create markdown.

Install **this** powerfull library
----------------------------------

```bash
composer require premier/markdown-builder
```

Todos
-----

- [X] write tests
- [X] 1. TableBuilder
      2. ListBuilders
      3. Checklist
- [ ] add more markdown features
- [ ] Configure CI
````

API
===

The markdown builder have two kinds of elements, block and inline elements.
        Block elements will be buffered and you can get the markdown if you call the method `getMarkdown()`.
        All inline Elements get instantly the markdown output.

### Block Elements

#### h1

```php
Markdown::builder()->h1('Hello H1')->getMarkdown();
```

```markdown
Hello H1
========
```

#### h2

```php
Markdown::builder()->h2('Hello H2')->getMarkdown();
```

```markdown
Hello H2
--------
```

#### h3

```php
Markdown::builder()->h3('Hello H3')->getMarkdown();
```

```markdown
### Hello H3
```

#### h4

```php
Markdown::builder()->h4('Hello H4')->getMarkdown();
```

```markdown
#### Hello H4
```

#### h5

```php
Markdown::builder()->h5('Hello H5')->getMarkdown();
```

```markdown
##### Hello H5
```

#### h6

```php
Markdown::builder()->h6('Hello H6')->getMarkdown();
```

```markdown
###### Hello H6
```

#### p

```php
Markdown::builder()->p('paragraph')->getMarkdown();
```

```markdown
paragraph
```

#### Blockquote

```php
Markdown::builder()->blockquote("Foo\nBar\nBaz")->getMarkdown();
```

```markdown
>  Foo
>  Bar
>  Baz
```

#### hr

```php
Markdown::builder()->hr()->getMarkdown();
```

```markdown
---------------------------------------
```

#### Code

```php
Markdown::builder()->code('$var = "test";', 'php')->getMarkdown();
```

````markdown
```php
$var = "test";
```
````

#### Badge

```php
Markdown::builder()
    ->badge(
        'Latest Stable Version',
        'https://poser.pugx.org/premier/markdown-builder/v',
        '//packagist.org/packages/premier/markdown-builder',
    )->getMarkdown();
```

```markdown
[![Latest Stable Version](https://poser.pugx.org/premier/markdown-builder/v)](//packagist.org/packages/premier/markdown-builder)
```

#### Bulleted list

```php
Markdown::builder()->bulletedList(['Foo', 'Bar', 'Baz'])->getMarkdown();
```

```markdown
* Foo
* Bar
* Baz
```

#### Bulleted list callable

```php
Markdown::builder()
    ->bulletedList(static function (BulletedListBuilder $builder): void {
        $builder->addLine(
            'Hallo',
            'foo',
            'bar',
        );
    })->getMarkdown();
```

```markdown
* Hallo
* foo
* bar
```

#### Numbered list

```php
Markdown::builder()->numberedList(['Foo', 'Bar', 'Baz'])->getMarkdown();
```

```markdown
1. Foo
2. Bar
3. Baz
```

#### Numbered list callable

```php
Markdown::builder()
    ->numberedList(static function (NumberedListBuilder $builder) {
        $builder->addLine(
            'Hallo',
            'foo',
            'bar',
        );
    })->getMarkdown();
```

```markdown
1. Hallo
2. foo
3. bar
```

#### Checklist

```php
Markdown::builder()
    ->checklist([
        'Hallo' => false,
        'foo' => false,
        'bar' => true,
    ])->getMarkdown();
```

```markdown
- [ ] Hallo
- [ ] foo
- [X] bar
```

#### Checklist callable

```php
Markdown::builder()
    ->checklist(static function (ChecklistBuilder $builder): void {
        $builder
            ->addLine('Hallo', false)
            ->addLine('foo', false)
            ->addLine('bar', true);
    })->getMarkdown();
```

```markdown
- [ ] Hallo
- [ ] foo
- [X] bar
```

#### Table

```php
Markdown::builder()
    ->table(
        ['First Header', 'Second Header'],
        [
            ['Content from cell 1', 'Content from cell 2'],
            ['Content in the first column', 'Content in the second column'],
        ]
    )
    ->getMarkdown();
```

```markdown
First Header | Second Header
------------ | -------------
Content from cell 1 | Content from cell 2
Content in the first column | Content in the second column
```

#### Table callable values

```php
$builder = Markdown::builder()
    ->table(
        ['First Header', 'Second Header'],
        static function (TableBuilder $builder): void {
            $builder->addRow(
                ['Content from cell 1', 'Content from cell 2'],
                ['Content in the first column', 'Content in the second column'],
            );
        },
    )
    ->getMarkdown();
```

```markdown
First Header | Second Header
------------ | -------------
Content from cell 1 | Content from cell 2
Content in the first column | Content in the second column
```

### Inline Blocks

#### Bold

```php
Markdown::bold('Hey!');
```

```markdown
**Hey!**
```

#### Italic

```php
Markdown::italic('huhu');
```

```markdown
*huhu*
```

#### Code

```php
Markdown::code('$var = "test";');
```

```markdown
`$var = "test";`
```

#### Link

```php
Markdown::link('https://google.com', 'Google');
```

```markdown
[Google](https://google.com)
```

#### img

```php
Markdown::img('cat.jpg', 'Cat');
```

```markdown
![Cat](cat.jpg)
```

#### Badge

```php
Markdown::badge(
    'Latest Stable Version',
    'https://poser.pugx.org/premier/markdown-builder/v',
    '//packagist.org/packages/premier/markdown-builder',
    );
```

```markdown
[![Latest Stable Version](https://poser.pugx.org/premier/markdown-builder/v)](//packagist.org/packages/premier/markdown-builder)
```

#### Numbered list

```php
Markdown::numberedList(['A', 'B', 'C']);
```

```markdown
1. A
2. B
3. C
```

#### Bulleted list

```php
Markdown::bulletedList(['A', 'B', 'C']);
```

```markdown
* A
* B
* C
```

#### Checklist

```php
Markdown::checklist(['A' => true, 'B' => true, 'C' => false]);
```

```markdown
- [X] A
- [X] B
- [ ] C
```

### Advanced Features

#### Collapse block

if you need collapse blocks, you can create a new builder instance with his own clean buffer.

```php
Markdown::builder()
    ->blockquote(
        Markdown::builder()
          ->h1('Lists')
          ->bulletedList([
            'Foo',
            Markdown::numberedList(['A', 'B', 'C']),
            'Bar'
          ])->getMarkdown()
    )
    ->getMarkdown();
```

```markdown
>  Lists
>  =====
>
>  * Foo
>  * 1. A
>    2. B
>    3. C
>  * Bar
```

#### Callback

If you want to add blocks from complex logic or iterable value, but don't want to stop chain calls you can use callback.

```php
Markdown::builder()
    ->p('Callback Example')
    ->callback(static function (Builder $builder) {
        foreach ([1, 2, 3] as $item) {
            $builder
                ->p($item.' paragraph.')
                ->hr();
        }
    })
    ->getMarkdown();
```

```markdown
Callback Example

1 paragraph.

---------------------------------------

2 paragraph.

---------------------------------------

3 paragraph.

---------------------------------------
```

#### Dump to File

Instead of returning markdown as a string you can easy dump result to file.

```php
Markdown::builder()->h1('Hello world!')->dump('index.md');
```
