<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

$map = [];
foreach ($input as $ln) {
    $map[] = str_split($ln);
}

function score($row, $col, $map): int
{
    $tree = $map[$row][$col];
    $score = 1;
    // top
    $row1 = $row;
    while ($row1 > 0) {
        $row1--;
        if ($map[$row1][$col] >= $tree) {
            break;
        }
    }

    $score *= ($row - $row1);

    // bottom
    $row1 = $row;
    while ($row1 < count($map)-1) {
        $row1++;
        if ($map[$row1][$col] >= $tree) {
            break;
        }
    }
    $score *= ($row1 - $row);

    // left
    $col1 = $col;
    while ($col1 < count($map[0])-1) {
        $col1++;
        if ($map[$row][$col1] >= $tree) {
            break;
        }
    }
    $score *= ($col1 - $col);

    // right
    $col1 = $col;
    while ($col1 > 0) {
        $col1--;
        if ($map[$row][$col1] >= $tree) {
            break;
        }
    }
    $score *= ($col - $col1);

    return $score;
}

$max = 0;
for ($row = 1; $row < count($map)-1; $row++) {
    for ($col = 1; $col < count($map[0])-1; $col++) {
        $score = score($row, $col, $map);
        if ($score > $max) {
            $max = $score;
        }
    }
}

echo "MAX: $max\n";