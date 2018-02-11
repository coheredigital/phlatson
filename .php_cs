<?php


return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,

        // docs
        'phpdoc_align' => true,
        'phpdoc_inline_tag' => true,
        'phpdoc_return_self_reference' => true,

        // aesthetics
        'align_multiline_comment' => true,
        'blank_line_after_opening_tag' => true,
        'single_quote' => true,
        'combine_consecutive_issets' => true,
        'dir_constant' => true,
        'include' => true,
        'ordered_imports' => true,

        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,

        'method_chaining_indentation' => true,
        'modernize_types_casting' => true,
        'native_function_invocation' => true,

        // removers
        'no_alias_functions' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_empty_comment' => true,
        'no_blank_lines_before_namespace' => true,
        'no_leading_namespace_whitespace' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_multiline_whitespace_before_semicolons' => true,
        'no_null_property_initialization' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unneeded_control_parentheses' => true,
        'no_useless_else' => true,
        'no_spaces_around_offset' => [
            'inside', 'outside'
        ],
        'no_extra_consecutive_blank_lines' => [
            'default',
            'extra',
            'break',
            'continue',
            'curly_brace_block',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'throw',
            'use',
            'use_trait'
        ],

        // spacing
        'object_operator_without_whitespace' => true,
        'cast_spaces' => [
            'space' => 'single'
        ],
        'declare_equal_normalize' => [
            'space' => 'single'
        ],
        'concat_space' => [
            'spacing' => 'none'
        ],
        'array_syntax' => [
            'syntax' => 'short'
        ],
    ]);
