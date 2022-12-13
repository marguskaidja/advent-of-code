<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

function compare_pairs(array $a, array $b): int
{
    $countA = count($a);
    $countB = count($b);
    $minListLength = min($countA, $countB);

    for ($idx = 0; $idx < $minListLength; $idx++) {
        $val1 = $a[$idx];
        $val2 = $b[$idx];

        if (is_int($val1) && is_int($val2)) {
            if ($val1 > $val2) {
                return 1;
            } elseif ($val1 < $val2) {
                return -1;
            }
        } else {
            if (is_int($val1)) {
                $val1 = [$val1];
            } elseif (is_int($val2)) {
                $val2 = [$val2];
            }

            $subResult = compare_pairs($val1, $val2);

            if ($subResult != 0) {
                return $subResult;
            }
        }
    }

    return ($countA - $countB);
}

$pairs = [];
$pair = [];
foreach ($input as $ln) {
    if ($ln === '') {
        $pairs[] = $pair;
        $pair = [];
    } else {
        $pair[] = json_decode($ln, true, 10000,JSON_THROW_ON_ERROR);
    }
}
$pairs[] = $pair;

$inOrderSum = 0;
foreach ($pairs as $pairIdx => $pair) {
    $pairIdx++;

    if (compare_pairs($pair[0], $pair[1], 2) <= 0) {
        $inOrderSum += $pairIdx;
    }
}

echo "Sum: " . $inOrderSum . "\n";