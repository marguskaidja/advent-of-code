<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

$priorities = 0;

while (count($input) > 0) {
    $a1 = array_shift($input);
    $a2 = array_shift($input);
    $a3 = array_shift($input);

    $intersect = array_intersect(
        str_split($a1),str_split($a2),str_split($a3)
    );

    $item = ord(current($intersect));

    if ($item >= ord('A') && $item <= ord('Z')) {
        $priorities += $item - ord('A') + 27;
    } else {
        $priorities += $item - ord('a') + 1;
    }
}

echo "TOTAL: " . $priorities . "\n";