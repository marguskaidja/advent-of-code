<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

//  X for Rock, Y for Paper, and Z for Scissors
$choiceScore = [
    'X' => 1,
    'Y' => 2,
    'Z' => 3,
];

// A for Rock, B for Paper, and C for Scissors
$defeatMap = [
    'A' => 'Y',
    'B' => 'Z',
    'C' => 'X'
];

// A for Rock, B for Paper, and C for Scissors
$drawMap = [
    'A' => 'X',
    'B' => 'Y',
    'C' => 'Z'
];

$outcomeMap = [
    'A' => [
        // lose
        'X' => 'Z',
        // draw
        'Y' => 'X',
        // win
        'Z' => 'Y'
    ],

    'B' => [
        // lose
        'X' => 'X',
        // draw
        'Y' => 'Y',
        // win
        'Z' => 'Z'
    ],

    'C' => [
        // lose
        'X' => 'Y',
        // draw
        'Y' => 'Z',
        // win
        'Z' => 'X'
    ],
];

$score = 0;
foreach ($input as $ln) {
    list($opponent, $outcome) = preg_split("/ +/", trim($ln), 2);

    $me = $outcomeMap[$opponent][$outcome];

    $score += $choiceScore[$me];

    if ($drawMap[$opponent] === $me) {
        echo "$opponent vs $me: DRAW (add " . (3 + $choiceScore[$me]) . ")\n";
        $score += 3;
    } elseif ($defeatMap[$opponent] === $me) {
        echo "$opponent vs $me: WIN! (add " . (6 + $choiceScore[$me]) . ")\n";
        $score += 6;
    } else {
        echo "$opponent vs $me: LOST! (add " . ($choiceScore[$me]) . ")\n";
    }
}

echo "SCORE: " . $score . "\n";