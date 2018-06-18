<?php
$project_name = 'JsonApiPlayground';
$config = require __DIR__.'/../../vendor/reyesoft/ci/php/rules/php-cs-fixer.dist.php';

// rules override
$rules = array_merge(
    $config->getRules(),
    [
        // 'strict_comparison' => true,
    ]
);

return $config
    ->setRules($rules)
    ->setFinder(
        PhpCsFixer\Finder::create()
        ->in('./app')
        ->notPath('./bootstrap/*.php')
        ->in('./config')
        ->in('./database')
        ->in('./routes')
        ->in('./tests')
    );
