<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

define('NUM_KNOTS', 10);

$knots = array_fill(
    0,
    NUM_KNOTS,
    ['x' => 0, 'y' => 0]
);

$visited = ['0:0' => 1];

foreach ($input as $lineIdx => $ln) {
    list($direction, $steps) = explode(' ', $ln);

    for ($i = 0; $i < (int)$steps; $i++) {
        // Remember previous positions of the knots
        $oldTail = $knots[NUM_KNOTS-1];

        // Move the Head
        switch ($direction) {
            case 'R':
                $knots[0]['x'] += 1;
                break;
            case 'L':
                $knots[0]['x'] -= 1;
                break;
            case 'U':
                $knots[0]['y'] -= 1;
                break;
            case 'D':
                $knots[0]['y'] += 1;
                break;
        }

        // Now iterate the rest of the knots and move them according the rules
        $check = false;
        for ($knotIdx = 1; $knotIdx < NUM_KNOTS; $knotIdx++) {
            if (
                // Previous knot moved UP too much, move along with current one
                $knots[$knotIdx-1]['y'] < $knots[$knotIdx]['y']
                && $knots[$knotIdx]['y'] - $knots[$knotIdx-1]['y'] > 1
            ) {
                $knots[$knotIdx]['y'] -= 1;
                if ($knots[$knotIdx]['x'] != $knots[$knotIdx-1]['x']) {
                    $knots[$knotIdx]['x'] += ($knots[$knotIdx]['x'] < $knots[$knotIdx - 1]['x'] ? 1 : -1);
                }
            } elseif (
                // Previous knot moved DOWN too much, move along with current one
                $knots[$knotIdx-1]['y'] > $knots[$knotIdx]['y']
                && $knots[$knotIdx-1]['y'] - $knots[$knotIdx]['y'] > 1
            ) {
                $knots[$knotIdx]['y'] += 1;
                if ($knots[$knotIdx]['x'] != $knots[$knotIdx-1]['x']) {
                    $knots[$knotIdx]['x'] += ($knots[$knotIdx]['x'] < $knots[$knotIdx - 1]['x'] ? 1 : -1);
                }
            } elseif (
                // Previous knot moved RIGHT too much, move along with current one
                $knots[$knotIdx-1]['x'] > $knots[$knotIdx]['x']
                && $knots[$knotIdx-1]['x'] - $knots[$knotIdx]['x'] > 1
            ) {
                $knots[$knotIdx]['x'] += 1;
                if ($knots[$knotIdx]['y'] != $knots[$knotIdx-1]['y']) {
                    $knots[$knotIdx]['y'] += ($knots[$knotIdx]['y'] < $knots[$knotIdx - 1]['y'] ? 1 : -1);
                }
            } elseif (
                // Previous knot moved LEFT too much, move along with current one
                $knots[$knotIdx-1]['x'] < $knots[$knotIdx]['x']
                && $knots[$knotIdx]['x'] - $knots[$knotIdx-1]['x'] > 1
            ) {
                $knots[$knotIdx]['x'] -= 1;
                if ($knots[$knotIdx]['y'] != $knots[$knotIdx-1]['y']) {
                    $knots[$knotIdx]['y'] += ($knots[$knotIdx]['y'] < $knots[$knotIdx - 1]['y'] ? 1 : -1);
                }
            } else {
                // No move required for current knto, thus the rest of
                // the knots will also not move. Break safely out of the knots iterator and
                // start the next movement of the head.
                break;
            }
        }

        if ($knots[NUM_KNOTS-1] != $oldTail) {
            $tailX = $knots[NUM_KNOTS-1]['x'];
            $tailY = $knots[NUM_KNOTS-1]['y'];
            $visited[$tailX.':'.$tailY] = ($visited[$tailX.':'.$tailY] ?? 0) + 1;
        }
    }
}

echo "VISITED: " . count($visited) . "\n";
