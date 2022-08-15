<?php

/* vim: set ft=php: */

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/app')
    ->in(__DIR__.'/bundle')
;

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        '@PHP73Migration' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_useless_return' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'semicolon_after_instruction' => false,
        'yoda_style' => false,
    ])
;
