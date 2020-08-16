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

PHP-Code:

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

Markdown:

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

PHP-Code:

```php
echo Markdown::builder()->h1('Hello H1')->getMarkdown();
```

Markdown:

```markdown
Hello H1
========
```

#### h2

PHP-Code:

```php
echo Markdown::builder()->h2('Hello H2')->getMarkdown();
```

Markdown:

```markdown
Hello H2
--------
```

#### h3

PHP-Code:

```php
echo Markdown::builder()->h3('Hello H3')->getMarkdown();
```

Markdown:

```markdown
### Hello H3
```

#### h4

PHP-Code:

```php
echo Markdown::builder()->h4('Hello H4')->getMarkdown();
```

Markdown:

```markdown
#### Hello H4
```

#### h5

PHP-Code:

```php
echo Markdown::builder()->h5('Hello H5')->getMarkdown();
```

Markdown:

```markdown
##### Hello H5
```

#### h6

PHP-Code:

```php
echo Markdown::builder()->h6('Hello H6')->getMarkdown();
```

Markdown:

```markdown
###### Hello H6
```

#### p

PHP-Code:

```php
echo Markdown::builder()->p('paragraph')->getMarkdown();
```

Markdown:

```markdown
paragraph
```

#### Blockquote

PHP-Code:

```php
echo Markdown::builder()->blockquote("Foo\nBar\nBaz")->getMarkdown();
```

Markdown:

```markdown
>  Foo
>  Bar
>  Baz
```

#### Bulleted list

PHP-Code:

```php
echo Markdown::builder()->bulletedList(['Foo', 'Bar', 'Baz'])->getMarkdown();
```

Markdown:

```markdown
* Foo
* Bar
* Baz
```

#### Numbered list

PHP-Code:

```php
echo Markdown::builder()->numberedList(['Foo', 'Bar', 'Baz'])->getMarkdown();
```

Markdown:

```markdown
1. Foo
2. Bar
3. Baz
```

#### hr

PHP-Code:

```php
echo Markdown::builder()->hr()->getMarkdown();
```

Markdown:

```markdown
---------------------------------------
```

#### Code

PHP-Code:

```php
echo Markdown::builder()->code('$var = "test";', 'php')->getMarkdown();
```

Markdown:

````markdown
```php
$var = "test";
```
````

#### Table

PHP-Code:

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

Markdown:

```markdown
First Header | Second Header
============ | =============
Content from cell 1 | Content from cell 2
Content in the first column | Content in the second column
```

### Inline Blocks

#### Bold

PHP-Code:

```php
echo Markdown::bold('Hey!');
```

Markdown:

```markdown
**Hey!**
```

#### Italic

PHP-Code:

```php
echo Markdown::italic('huhu');
```

Markdown:

```markdown
*huhu*
```

#### Code

PHP-Code:

```php
echo Markdown::code('$var = "test";');
```

Markdown:

```markdown
`$var = "test";`
```

#### Link

PHP-Code:

```php
echo Markdown::link('https://google.com', 'Google');
```

Markdown:

```markdown
[Google](https://google.com)
```

#### img

PHP-Code:

```php
echo Markdown::img('cat.jpg', 'Cat');
```

Markdown:

```markdown
![Cat](cat.jpg)
```

#### Numbered list

PHP-Code:

```php
echo Markdown::numberedList(['A', 'B', 'C']);
```

Markdown:

```markdown
1. A
2. B
3. C
```

#### Bulleted list

PHP-Code:

```php
echo Markdown::bulletedList(['A', 'B', 'C']);
```

Markdown:

```markdown
* A
* B
* C
```

### Advanced Features

#### Collapse block

if you need collapse blocks, you can create a new builder instance with his own clean buffer.

PHP-Code:

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

Markdown:

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

PHP-Code:

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

Markdown:

```markdown
Callback Example

1 paragraph.

---------------------------------------

2 paragraph.

---------------------------------------

3 paragraph.

---------------------------------------
```