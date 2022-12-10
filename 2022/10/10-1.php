<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

$totalCycles = 0;
$totalX = 1;
$sumStrength = 0;

foreach ($input as $ln) {
    list($cmd, $addX) = explode(' ', $ln . ' dummy');

    $addX = (int)$addX;

    $nCycles = ($cmd === 'noop' ? 1 : 2);

    do {
        $totalCycles++;

        if (($totalCycles - 20) % 40 === 0 && $totalCycles <= 220) {
            $sumStrength += ($totalCycles * $totalX);
        }

        if (--$nCycles === 0) {
            $totalX += $addX;
        }
    } while($nCycles > 0);
}

echo "Total: " . $sumStrength . "\n";