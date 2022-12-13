<?php

declare(strict_types=1);

$input = preg_split("/(\n|\r\n)/", file_get_contents(dirname(__FILE__).'/input.txt'), -1);

$gridData = [];

foreach ($input as $ln) {
    $gridData[] = str_split($ln);
}

/**
 * First I intuitively implemented algorithm, which tried to recursively explore all possible paths.
 * At first it seemed actually work with test data, but then it didn't work at all with actual puzzle input.
 * It just hanged forever.
 *
 * Then I had to actually google "shortest path algorithm" and learned about BFS (Breadth First Search) algorithm,
 * which is applied to 2dimensional grid and finds the shortest path from Start to Finish blazingly fast.
 */
class Coord
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly int $dist
    ) {}
}

class Queue
{
    private array $items = [];

    public function __construct() {}

    public function enqueue(Coord $coord): void
    {
        $this->items[] = $coord;
    }

    public function dequeue(): Coord|null
    {
        if (count($this->items) === 0) {
            return null;
        }

        return array_shift($this->items);
    }

    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }
}

class Grid
{
    private Coord $start;

    private Coord $finish;

    private int $nRows;

    private int $nCols;

    private array $visited = [];

    public function __construct(
        private array $grid
    ) {
        $this->nRows = count($this->grid);
        $this->nCols = count($this->grid[0]);

        // Find the starting and finish position on the input map
        for($y = 0; $y < $this->nRows; $y++) {
            for($x = 0; $x < $this->nCols; $x++) {
                if ($this->grid[$y][$x] === 'S') {
                    $this->start = new Coord($x, $y, 0);
                    $this->grid[$y][$x] = 'a';
                } elseif ($this->grid[$y][$x] === 'E') {
                    $this->finish = new Coord($x, $y, 0);
                    $this->grid[$y][$x] = 'z';
                } elseif ($this->grid[$y][$x] < 'a' || $this->grid[$y][$x] > 'z') {
                    throw new Exception("Invalid elevation `{$this->grid[$y][$x]}` at position x=$x,y=$y");
                }
            }
        }
    }

    public function getStart(): Coord
    {
        return $this->start;
    }

    public function isFinish(Coord $coord): bool
    {
        return $this->finish->x == $coord->x && $this->finish->y == $coord->y;
    }

    public function getElevationAt(Coord $coord): int
    {
        return ord($this->grid[$coord->y][$coord->x]);
    }

    public function addVisited(Coord $coord): void
    {
        $this->visited[$coord->x.':'.$coord->y] = true;
    }

    public function isVisited(Coord $coord): bool
    {
        return isset($this->visited[$coord->x.':'.$coord->y]);
    }

    public function addNeighboursToQueue(Coord $coord, Queue $q): void
    {
        $curElevation = $this->getElevationAt($coord);
        $this->addVisited($coord);

        foreach (
            [
                [-1, 0],
                [1, 0],
                [0, -1],
                [0, 1],
            ] as $direction
        ) {
            $newX = $coord->x + $direction[0];
            $newY = $coord->y + $direction[1];

            $newCoord = new Coord($newX, $newY, $coord->dist + 1);

            if (
                $newX >= 0 && $newX < $this->nCols
                && $newY >= 0 && $newY < $this->nRows
                && !$this->isVisited($newCoord)
            ) {
                if ($this->getElevationAt($newCoord) - $curElevation <= 1) {
                    $this->addVisited($newCoord);
                    $q->enqueue($newCoord);
                }
            }
        }
    }
}

function find_shortest_distance_to_finish(Grid $grid): Coord|null
{
    $q = new Queue();
    $q->enqueue($grid->getStart());

    while(!$q->isEmpty()) {
        $coord = $q->dequeue();

        if ($grid->isFinish($coord)) {
            return $coord;
        }

        $grid->addNeighboursToQueue($coord, $q);
    }

    return null;
}

$finish = find_shortest_distance_to_finish(new Grid($gridData));

if ($finish) {
    echo "Found shortest path from S to E @ x=$finish->x,y=$finish->y,distance=$finish->dist\n";
} else {
    echo "There is not path from Start to Finish\n";
}
