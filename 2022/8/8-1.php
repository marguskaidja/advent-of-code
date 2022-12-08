<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

$map = [];
foreach ($input as $ln) {
    $map[] = str_split($ln);
}

function is_visible($row, $col, $map): bool
{
    if ($row === 0 || $col === 0 || $row == count($map) - 1 || $col == count($map[0]) - 1) {
        return true;
    }

    $tree = $map[$row][$col];

    // top
    $row1 = $row;
    $visible = true;
    while (--$row1 >= 0) {
        if ($map[$row1][$col] >= $tree) {
            $visible = false;
            break;
        }
    }
    if ($visible) {
        return true;
    }
    // bottom
    $row1 = $row;
    $visible = true;
    while (++$row1 < count($map)) {
        if ($map[$row1][$col] >= $tree) {
            $visible = false;
            break;
        }
    }
    if ($visible) {
        return true;
    }
    // left
    $col1 = $col;
    $visible = true;
    while (++$col1 < count($map[0])) {
        if ($map[$row][$col1] >= $tree) {
            $visible = false;
            break;
        }
    }
    if ($visible) {
        return true;
    }
    // right
    $col1 = $col;
    $visible = true;
    while (--$col1 >= 0) {
        if ($map[$row][$col1] >= $tree) {
            $visible = false;
            break;
        }
    }

    return $visible;
}

$total = 0;
for ($row = 0; $row < count($map); $row++) {
    for ($col = 0; $col < count($map[0]); $col++) {
        if (is_visible($row, $col, $map)) {
            $total++;
        }
    }
}

echo "TOTAL: $total\n";