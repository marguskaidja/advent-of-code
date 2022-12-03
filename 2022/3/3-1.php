<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

$priorities = 0;

foreach ($input as $ln) {
    $a = str_split($ln);
    $p1 = array_slice($a, 0, count($a)/2);
    $p2 = array_slice($a, count($a)/2);

    $a = array_intersect($p1, $p2);

    $item = ord(current($a));

    if ($item >= ord('A') && $item <= ord('Z')) {
        $priorities += $item - ord('A') + 27;
    } else {
        $priorities += $item - ord('a') + 1;
    }

}

echo "TOTAL: " . $priorities . "\n";