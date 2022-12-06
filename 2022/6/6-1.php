<?php

declare(strict_types=1);

$ln = file_get_contents(dirname(__FILE__).'/input.txt');

for($c = 4; $c < strlen($ln); $c++) {

    $characters = str_split(substr($ln, $c - 4, 4));
    $characters = array_unique($characters);
    if (count($characters) == 4) {
        echo $c;
        break;
    }
}
