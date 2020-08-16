#!/usr/bin/env php
<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer'                           => true,
        '@PhpCsFixer:risky'                     => true,
        '@Symfony'                              => true,
        '@Symfony:risky'                        => true,
        'array_syntax'                          => ['syntax' => 'short'],
        'declare_strict_types'                  => true,
        'binary_operator_spaces'                => ['operators' => ['=>' => 'single_space']],
        'fopen_flags'                           => ['b_mode' => true],
        'fully_qualified_strict_types'          => true,
        'global_namespace_import'               => ['import_functions' => true],
        'heredoc_indentation'                   => true,
        'linebreak_after_opening_tag'           => true,
        'list_syntax'                           => ['syntax' => 'short'],
        'multiline_whitespace_before_semicolons'=> false,
        'native_function_invocation'            => true,
        'no_null_property_initialization'       => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else'                       => true,
        'no_useless_return'                     => true,
        'non_printable_character'               => false,
        'not_operator_with_space'               => false,
        'not_operator_with_successor_space'     => false,
        'ordered_class_elements'                => true,
        'ordered_imports'                       => true,
        'php_unit_internal_class'               => false,
        'php_unit_strict'                       => true,
        'php_unit_test_case_static_method_calls'=> true,
        'php_unit_test_class_requires_covers'   => false,
        'phpdoc_order'                          => true,
        'phpdoc_to_comment'                     => false,
        'phpdoc_types_order'                    => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'random_api_migration'                  => true,
        'simplified_null_return'                => false,
        'single_line_throw'                     => false,
        'strict_comparison'                     => true,
        'strict_param'                          => true,
        'ternary_to_null_coalescing'            => true,
        'void_return'                           => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__.'/.php_cs.cache');
