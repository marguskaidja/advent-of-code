<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

class Line
{
    public function __construct(
        public readonly int $x1,
        public readonly int $y1,
        public readonly int $x2,
        public readonly int $y2,
    ) {}
}

class Grid
{
    private ?int $nRows = null;
    private int $nCols;
    private int $inputCol;
    private array $grid;

    public function __construct(array $scans)
    {
        // Parse rock structures
        $lines = [];
        $minX = null;
        $maxX = null;
        foreach ($scans as $scan) {
            $coords = explode(' -> ', $scan);
            $prev = null;
            foreach ($coords as $coordTxt) {
                list($x, $y) = explode(',', $coordTxt);
                $x = (int)$x;
                $y = (int)$y;

                if ($prev !== null) {
                    $lines[] = new Line($prev[0], $prev[1], $x, $y);
                }

                $prev = [$x, $y];

                if ($minX === null || $x < $minX) {
                    $minX = $x;
                }

                if ($maxX === null || $x > $maxX) {
                    $maxX = $x;
                }

                if ($this->nRows === null || $y > $this->nRows) {
                    $this->nRows = $y;
                }
            }
        }

        $this->nRows++;
        $this->nCols = ($maxX - $minX) + 1;
        $this->inputCol = 500 - $minX;

        $this->grid = array_fill(
            0,
            $this->nRows,
            array_fill(0, $this->nCols, '.')
        );

        // Create rock structures
        foreach ($lines as $line) {
            if ($line->y1 != $line->y2) {
                $start = min($line->y1, $line->y2);
                $end = max($line->y1, $line->y2);

                for ($y = $start; $y <= $end; $y++) {
                    $this->grid[$y][$line->x1 - $minX] = '#';
                }
            } elseif ($line->x1 != $line->x2) {
                $start = min($line->x1, $line->x2) - $minX;
                $end = max($line->x1, $line->x2) - $minX;

                for ($x = $start; $x <= $end; $x++) {
                    $this->grid[$line->y1][$x] = '#';
                }
            }
        }
    }

    public function drawGrid(): void
    {
        for ($y = 0; $y < $this->nRows; $y++) {
            for ($x = 0; $x < $this->nCols; $x++) {
                echo $this->grid[$y][$x];
            }
            echo "\n";
        }
    }

    public function cellContents(int $x, int $y): string|null
    {
        if ($x >= 0 && $x < $this->nCols && $y >= 0 && $y < $this->nRows) {
            return $this->grid[$y][$x];
        }

        return null;
    }

    public function pourNextUnitOfSand(): bool
    {
        $x = $this->inputCol;
        $y = -1;

        do {
            $newX = $x;
            $newY = $y;

            if (($contents = $this->cellContents($x, $y+1)) !== null) {
                if ($contents === '.') {
                    $newY = $y+1;
                } else {
                    if (in_array($this->cellContents($x-1, $y+1), ['.', null])) {
                        $newX = $x-1;
                        $newY = $y+1;
                    } elseif (in_array($this->cellContents($x+1, $y+1), ['.', null])) {
                        $newX = $x+1;
                        $newY = $y+1;
                    }
                }
            } else {
                $newY = $y+1;
            }

            if ($newX < 0 || $newX >= $this->nCols || $newY >= $this->nRows) {
                return false;
            } elseif ($newX === $x && $newY === $y) {
                $this->grid[$newY][$newX] = 'o';
                break;
            } else {
                $x = $newX;
                $y = $newY;
            }
        } while (1);

        return true;
    }
}

$grid = new Grid($input);
$n = 0;

while ($grid->pourNextUnitOfSand()) {
    $n++;
}

//$grid->drawGrid();

echo "Num sand units: " . $n . "\n";