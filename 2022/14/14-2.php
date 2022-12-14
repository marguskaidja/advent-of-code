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

        $lines[] = new Line($minX, $this->nRows + 2, $maxX, $this->nRows + 2);

        $this->nRows+=3;
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

    public function addLeftCol(): void
    {
        foreach ($this->grid as $i => $row) {
            if ($i === $this->nRows - 1) {
                $ch = '#';
            } else {
                $ch = '.';
            }
            array_unshift($row, $ch);
            $this->grid[$i] = $row;
        }
        $this->nCols++;
        $this->inputCol++;
    }

    public function addRightCol(): void
    {
        foreach ($this->grid as $i => $row) {
            if ($i === $this->nRows - 1) {
                $ch = '#';
            } else {
                $ch = '.';
            }
            array_push($row, $ch);
            $this->grid[$i] = $row;
        }
        $this->nCols++;
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
        if ($y >= $this->nRows) {
            throw new Exception("y>=\$this->nRows");
        }

        if ($y < 0) {
            throw new Exception("y<0");
        }

        if ($x >= 0 && $x < $this->nCols && $y >= 0) {
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

            $contents = $this->cellContents($x, $y+1);

            if ($contents === '.') {
                $newY = $y+1;
            } elseif ($y >= 0) {
                // Move diagonally left
                $contents = $this->cellContents($x-1, $y+1);

                // Restart
                if ($contents === null) {
                    $this->addLeftCol();
                    $x++;
                    continue;
                } elseif ($contents === '.') {
                    $newX = $x-1;
                    $newY = $y+1;
                } else {
                    // Move diagonally right
                    $contents = $this->cellContents($x + 1, $y + 1);

                    // Restart
                    if ($contents === null) {
                        $this->addRightCol();
                        continue;
                    } elseif ($contents === '.') {
                        $newX = $x + 1;
                        $newY = $y + 1;
                    }
                }
            }

            if ($newX === $x && $newY === $y) {
                if ($newY === -1) {
                    return false;
                }

                $this->grid[$newY][$newX] = 'o';
                break;
            }

            $x = $newX;
            $y = $newY;
        } while (1);

        return true;
    }
}

$grid = new Grid($input);
$n = 0;

while($grid->pourNextUnitOfSand()) {
    $n++;
}

//$grid->drawGrid();

echo "Num sand units: " . $n . "\n";