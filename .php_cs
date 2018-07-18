<?php

/* vim: set ft=php: */

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/app')
    ->in(__DIR__.'/bundle')
;

return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        '@PHP71Migration' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'no_useless_return' => true,
        'semicolon_after_instruction' => false,
        'yoda_style' => false,
    ])
;
