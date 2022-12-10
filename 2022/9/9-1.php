<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

$head_x = 0;
$head_y = 0;
$tail_x = 0;
$tail_y = 0;

$visited = [$tail_x.':'.$tail_y => 1];
foreach ($input as $ln) {
    list($direction, $steps) = explode(' ', $ln);
    $steps = (int)$steps;
    for ($i = 0; $i < $steps; $i++) {
        $old_tail_x = $tail_x;
        $old_tail_y = $tail_y;
        $old_head_x = $head_x;
        $old_head_y = $head_y;
        switch ($direction) {
            case 'R':
                $head_x += 1;
                if (abs($head_x - $tail_x) >= 2) {
                    $tail_x += ($old_head_x - $tail_x);
                    $tail_y = $head_y;
                }
                break;
            case 'L':
                $head_x -= 1;
                if (abs($head_x - $tail_x) >= 2) {
                    $tail_x -= ($tail_x - $old_head_x);
                    $tail_y = $head_y;
                }
                break;
            case 'U':
                $head_y -= 1;
                if (abs($head_y - $tail_y) >= 2) {
                    $tail_y -= ($tail_y - $old_head_y);
                    $tail_x = $head_x;
                }
                break;
            case 'D':
                $head_y += 1;
                if (abs($head_y - $tail_y) >= 2) {
                    $tail_y += ($old_head_y - $tail_y);
                    $tail_x = $head_x;
                }
                break;
        }
        if ($old_tail_x != $tail_x || $old_tail_y != $tail_y) {
            $visited[$tail_x.':'.$tail_y] = ($visited[$tail_x.':'.$tail_y] ?? 0) + 1;
        }
    }
}

echo "VISITED: " . count($visited) . "\n";