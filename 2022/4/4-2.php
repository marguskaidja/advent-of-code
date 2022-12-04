<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

$total = 0;
foreach ($input as $ln) {
    list($e1, $e2) = explode(',', $ln, 2);

    $r1 = explode("-", $e1, 2);
    $r2 = explode("-", $e2, 2);

    if (
        ($r1[0] >= $r2[0] && $r1[1] <= $r2[1]) ||
        ($r2[0] >= $r1[0] && $r2[1] <= $r1[1]) ||

        ($r1[0] >= $r2[0] && $r1[0] <= $r2[1]) ||
        ($r1[1] >= $r2[0] && $r1[1] <= $r2[1])
    ) {
        $total++;
    }

}

echo "TOTAL: " . $total . "\n";