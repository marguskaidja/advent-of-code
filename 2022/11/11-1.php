<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

function op_mul($a, $b){
    return $a * $b;
}
function op_div($a, $b){
    return $a / $b;
}
function op_add($a, $b){
    return $a + $b;
}
function op_sub($a, $b){
    return $a - $b;
}

$curMonkey = null;
$monkeys = [];
foreach ($input as $ln) {
    if (preg_match('|Monkey ([0-9]+)|', $ln, $matches)) {
        $curMonkey = $matches[1];
    } elseif (preg_match('|Starting items: ([0-9, ]+)|', $ln, $matches)) {
        $monkeys[$curMonkey]['items'] = preg_split('|\s*,\s*|', $matches[1], -1, PREG_SPLIT_NO_EMPTY);
    } elseif (preg_match('/Operation: new = (old|[0-9]+) (\\+|\\*|\\-) (old|[0-9]+)/', $ln, $matches)) {
        $monkeys[$curMonkey]['operation'] = [
            'operand1' => $matches[1],
            'operator' => match($matches[2]) {
                '*' => op_mul(...),
                '+' => op_add(...),
                '-' => op_sub(...),
                '/' => op_div(...),
            },
            'operand2' => $matches[3],
        ];
    } elseif (preg_match('/Test: divisible by ([0-9]+)/', $ln, $matches)) {
        $monkeys[$curMonkey]['test_divisible_by'] = $matches[1];
    } elseif (preg_match('/If true: throw to monkey ([0-9]+)/', $ln, $matches)) {
        $monkeys[$curMonkey]['if_true_throw_to'] = $matches[1];
    } elseif (preg_match('/If false: throw to monkey ([0-9]+)/', $ln, $matches)) {
        $monkeys[$curMonkey]['if_false_throw_to'] = $matches[1];
    }
}


$inspects = array_fill(0, count($monkeys), 0);
for ($round = 0; $round < 20; $round++) {

    for ($curMonkey = 0; $curMonkey < count($monkeys); $curMonkey++) {

        foreach ($monkeys[$curMonkey]['items'] as $itemIdx => $oldWorryLevel) {
            $inspects[$curMonkey]++;

            $op1 = $monkeys[$curMonkey]['operation']['operand1'] === 'old' ? $oldWorryLevel : $monkeys[$curMonkey]['operation']['operand1'];
            $op2 = $monkeys[$curMonkey]['operation']['operand2'] === 'old' ? $oldWorryLevel : $monkeys[$curMonkey]['operation']['operand2'];
            $newWorryLevel = $monkeys[$curMonkey]['operation']['operator']($op1, $op2);

            $newWorryLevel = (int)($newWorryLevel / 3);

            $test = (($newWorryLevel % $monkeys[$curMonkey]['test_divisible_by']) === 0);

            if ($test) {
                $throwToMonkey = $monkeys[$curMonkey]['if_true_throw_to'];
            } else {
                $throwToMonkey = $monkeys[$curMonkey]['if_false_throw_to'];
            }

            $monkeys[$throwToMonkey]['items'][] = $newWorryLevel;
            array_shift($monkeys[$curMonkey]['items']);
        }
    }
}


rsort($inspects);

echo "Monkeybusiness: " . ($inspects[0] * $inspects[1]) . "\n";