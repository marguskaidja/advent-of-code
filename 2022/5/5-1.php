<?php

declare(strict_types=1);


$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

$crates = [];
while(count($input)) {
    $ln = array_shift($input);
    if (!preg_match("|\\[([A-Z])\\]|", $ln)) {
        break;
    }

    for ($c = 0; $c < strlen($ln); $c+=4) {
        $crate = $ln[$c+1];
        if ($crate != " ") {
            $crates[$c/4][] = $crate;
        }
    }
}

ksort($crates);

while(count($input)) {
    $ln = array_shift($input);
    if (preg_match("|move ([0-9]+) from ([0-9]+) to ([0-9]+)|", $ln, $matches)) {
        $count = (int)$matches[1];
        $from = (int)$matches[2]-1;
        $to = (int)$matches[3]-1;
        array_unshift($crates[$to], ...array_reverse(array_slice($crates[$from], 0, $count)));

        $crates[$from] = array_slice($crates[$from], $count);
    }
}

$top = '';
foreach($crates as $e) {
    $top .= current($e);
}
echo $top . "\n";