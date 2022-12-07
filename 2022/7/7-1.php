<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

function calc_dir_size(string $dir, array &$hierarchy): int
{
    $sz = 0;
    foreach ($hierarchy[$dir]['files'] as $size) {
        $sz += $size;
    }

    foreach ($hierarchy[$dir]['dirs'] as $n => $dummy) {
        $sz += calc_dir_size(($dir != '/' ? $dir : '') . '/' . $n, $hierarchy);
    }

    $hierarchy[$dir]['size'] = $sz;

    return $sz;
}

$total = 0;
$hierarchy = [];
$curdir = [];
$inlisting = false;
while (count($input)) {
    $ln = array_shift($input);
    $curdirname = '/' . implode("/", $curdir);

    if (preg_match("|\\$ cd (.+)|", $ln, $matches)) {
        if ($matches[1] == '..') {
            array_pop($curdir);
        } elseif ($matches[1] !== '/') {
            $curdir[] = $matches[1];
        }
    } elseif (preg_match("|\\$ ls|", $ln, $matches)) {
        $hierarchy[$curdirname] = [
            'files' => [],
            'dirs' => [],
            'size' => 0,
        ];
    } else {
        if (preg_match("/([0-9]+) (.+)/", $ln, $matches)) {
            $size = $matches[1];
            $name = $matches[2];
            $hierarchy[$curdirname]['files'][$name] = $size;
        } elseif (preg_match("/(dir) (.+)/", $ln, $matches)) {
            $name = $matches[2];
            $hierarchy[$curdirname]['dirs'][$name] = true;
        }
    }
}

calc_dir_size('/', $hierarchy);
$total = 0;
foreach($hierarchy as $name => $entry) {
    if ($entry['size'] <= 100000) {
        $total += $entry['size'];
    }
}

echo "TOTAL: $total\n";