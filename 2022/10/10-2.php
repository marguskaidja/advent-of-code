<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

$totalCycles = 0;
$totalX = 1;

foreach ($input as $ln) {
    list($cmd, $addX) = explode(' ', $ln . ' dummy');

    $addX = (int)$addX;

    $nCycles = ($cmd === 'noop' ? 1 : 2);

    do {
        $hpos = $totalCycles % 40;

        if ($hpos >= $totalX - 1 && $hpos <= $totalX + 1) {
            echo '#';
        } else {
            echo '.';
        }

        if ($hpos === 39) {
            echo "\n";
        }

        $totalCycles++;

        if (--$nCycles === 0) {
            $totalX += $addX;
        }
    } while($nCycles > 0);
}
