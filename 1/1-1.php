<?php


$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);


$n = 0;
$max = 0;
foreach ($input as $ln) {
    $ln = trim($ln);

    if ($ln == '') {
        if ($n > $max) {
            $max = $n;
        }
        $n = 0;
    } else {
        $n += ((int)$ln);
    }
}

if ($n > $max) {
    $max = $n;
}

echo "MAX: " . $max . "\n";