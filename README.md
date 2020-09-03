Markdown Builder
================

A helper class to create markdown.

Installation
------------

```bash
composer require premier/markdown-builder
```

Usage
-----

```php
echo Markdown::builder()
    ->h1('Markdown Builder')
    ->p('A helper class to create markdown.')
    ->h2('Install '.Markdown::bold('this').' powerfull library')
    ->code('composer require premier/markdown-builder', 'bash')
    ->h2('Todos')
    ->bulletedList([
        'write tests',
        Markdown::numberedList(['A', 'B', 'C']),
        'add more markdown features',
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

* write tests
* 1. A
  2. B
  3. C
* add more markdown features
````

API
===

The markdown builder have two kinds of elements, block and inline elements.
        Block elements will be buffered and you can get the markdown if you call the method `getMarkdown()`.
        All inline Elements get instantly the markdown output.

### Block Elements

#### h1

```php
echo Markdown::builder()->h1('Hello H1')->getMarkdown();
```

```markdown
Hello H1
========
```

#### h2

```php
echo Markdown::builder()->h2('Hello H2')->getMarkdown();
```

```markdown
Hello H2
--------
```

#### h3

```php
echo Markdown::builder()->h3('Hello H3')->getMarkdown();
```

```markdown
### Hello H3
```

#### h4

```php
echo Markdown::builder()->h4('Hello H4')->getMarkdown();
```

```markdown
#### Hello H4
```

#### h5

```php
echo Markdown::builder()->h5('Hello H5')->getMarkdown();
```

```markdown
##### Hello H5
```

#### h6

```php
echo Markdown::builder()->h6('Hello H6')->getMarkdown();
```

```markdown
###### Hello H6
```

#### p

```php
echo Markdown::builder()->p('paragraph')->getMarkdown();
```

```markdown
paragraph
```

#### Blockquote

```php
echo Markdown::builder()->blockquote("Foo\nBar\nBaz")->getMarkdown();
```

```markdown
>  Foo
>  Bar
>  Baz
```

#### hr

```php
echo Markdown::builder()->hr()->getMarkdown();
```

```markdown
---------------------------------------
```

#### Code

```php
echo Markdown::builder()->code('$var = "test";', 'php')->getMarkdown();
```

````markdown
```php
$var = "test";
```
````

#### Bulleted list

```php
echo Markdown::builder()->bulletedList(['Foo', 'Bar', 'Baz'])->getMarkdown();
```

```markdown
* Foo
* Bar
* Baz
```

#### Bulleted list callable

```php
echo Markdown::builder()
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
echo Markdown::builder()->numberedList(['Foo', 'Bar', 'Baz'])->getMarkdown();
```

```markdown
1. Foo
2. Bar
3. Baz
```

#### Numbered list callable

```php
echo Markdown::builder()->numberedList(static function (NumberedListBuilder $builder) {
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

#### Table

```php
echo Markdown::builder()
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
echo $builder = Markdown::builder()
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
echo Markdown::bold('Hey!');
```

```markdown
**Hey!**
```

#### Italic

```php
echo Markdown::italic('huhu');
```

```markdown
*huhu*
```

#### Code

```php
echo Markdown::code('$var = "test";');
```

```markdown
`$var = "test";`
```

#### Link

```php
echo Markdown::link('https://google.com', 'Google');
```

```markdown
[Google](https://google.com)
```

#### img

```php
echo Markdown::img('cat.jpg', 'Cat');
```

```markdown
![Cat](cat.jpg)
```

#### Numbered list

```php
echo Markdown::numberedList(['A', 'B', 'C']);
```

```markdown
1. A
2. B
3. C
```

#### Bulleted list

```php
echo Markdown::bulletedList(['A', 'B', 'C']);
```

```markdown
* A
* B
* C
```

### Advanced Features

#### Collapse block

if you need collapse blocks, you can create a new builder instance with his own clean buffer.

```php
echo Markdown::builder()
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
echo Markdown::builder()
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