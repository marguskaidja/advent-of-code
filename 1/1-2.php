<?php


$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);


$n = 0;
$all = [];

foreach ($input as $ln) {
    $ln = trim($ln);

    if ($ln == '') {
        $all[] = $n;
        $n = 0;
    } else {
        $n += ((int)$ln);
    }
}

$all[] = $n;

rsort($all);
$all = array_slice($all, 0, 3);
$total = array_sum($all);

echo "TOP 3 TOTAL: " . $total . "\n";