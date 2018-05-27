<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

$inputFile = getcwd() . '/' . $argv[1];
$percentage = min(100, max(0, (int) $argv[2]));

if (!file_exists($inputFile)) {
    throw new InvalidArgumentException('Invalid input file provided (' . $inputFile . ')');
}

if (!$percentage) {
    throw new InvalidArgumentException('An integer checked percentage must be given as second parameter');
}

$xml = new SimpleXMLElement(file_get_contents($inputFile));
$metrics = $xml->xpath('//metrics');
$totalElements = 0;
$checkedElements = 0;

foreach ($metrics as $metric) {
    $totalElements += (int) $metric['elements'];
    $checkedElements += (int) $metric['coveredelements'];
}

$coverage = ($checkedElements / $totalElements) * 100;

echo PHP_EOL .
    '👉  Check file://' . getcwd() . '/bootstrap/cache/reports/coverage/index.html for more information'
    . PHP_EOL . PHP_EOL;

if ($coverage < $percentage) {
    echo '🚫  ERROR: Code coverage is ' . $coverage . '%, which is below the accepted ' . $percentage . '%' . PHP_EOL . PHP_EOL;
    exit(1);
}

echo PHP_EOL . '👌  OK: Code coverage is ' . $coverage . '% - OK!';
