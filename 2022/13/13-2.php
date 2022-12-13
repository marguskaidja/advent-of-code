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

$packets = [];
$input[] = '[[2]]';
$input[] = '[[6]]';

foreach ($input as $ln) {
    if ($ln !== '') {
        $packets[] = json_decode($ln, true, 10000,JSON_THROW_ON_ERROR);
    }
}

usort($packets, compare_pairs(...));

$product = 1;
foreach ($packets as $packetIdx => $packet) {
    $enc = json_encode($packet);
    if ($enc === '[[2]]' || $enc === '[[6]]') {
        $product *= ($packetIdx+1);
    }
}

echo "Product: " . $product . "\n";
