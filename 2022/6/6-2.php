<?php

declare(strict_types=1);

$ln = file_get_contents(dirname(__FILE__).'/input.txt') ;

for($c = 14; $c < strlen($ln); $c++) {

    $characters = str_split(substr($ln, $c - 14, 14));
    $characters = array_unique($characters);
    if (count($characters) == 14) {
        echo $c;
        break;
    }
}
