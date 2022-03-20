<?php

namespace Akaddev\GameOfLife\Cell;

class CellRepository
{
    private array $cells = [];

    /**
     * @return \Generator|Cell[]
     */
    public function getAll(): \Generator
    {
        foreach ($this->cells as $cells) {
            foreach ($cells as $cell) {
                yield clone $cell;
            }
        }
    }

    /**
     * @return array|Cell[]
     */
    public function getAllNeighbours(Cell $cell): array
    {
        $result = [];

        foreach ($this->getNeighboursCoordinates($cell) as $coordinate) {
            $result[] = $this->findByCoordinate($coordinate);
        }

        return $result;
    }

    public function findByCoordinate(Coordinate $coordinate): ?Cell
    {
        return $this->cells[$coordinate->x()][$coordinate->y()] ?? null;
    }

    public function add(Cell $cell)
    {
        $coordinate = $cell->coordinate();

        $this->cells[$coordinate->x()][$coordinate->y()] = $cell;

        //Because our universe is infinite, we must always have neighbours cells around alive ones
        if ($cell->isAlive()) {
            $this->fillNeighbours($cell);
        }
    }

    public function update(Cell $cell)
    {
        $coordinate = $cell->coordinate();

        if (null !== $this->findByCoordinate($coordinate)) {
            $this->cells[$coordinate->x()][$coordinate->y()] = $cell;
        }

        //Because our universe is infinite, we must always have neighbours cells around alive ones
        if ($cell->isAlive()) {
            $this->fillNeighbours($cell);
        }
    }

    public function remove(Cell $cell)
    {
        $coordinate = $cell->coordinate();

        if (null !== $this->findByCoordinate($coordinate)) {
            unset($this->cells[$coordinate->x()][$coordinate->y()]);

            if (count($this->cells[$coordinate->x()]) === 0) {
                unset($this->cells[$coordinate->x()]);
            }
        }
    }

    public function clearAll()
    {
        $this->cells = [];
    }

    public function countAliveNeighbours(Cell $cell): int
    {
        $count = 0;

        foreach ($this->getAllNeighbours($cell) as $neighbourCell) {
            if (null !== $neighbourCell && $neighbourCell->isAlive()) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Removes nested dead cells
     */
    public function cleanUp()
    {
        foreach ($this->getAll() as $cell) {
            if (!$cell->isAlive() && $this->countAliveNeighbours($cell) === 0) {
                $this->remove($cell);
            }
        }
    }

    /**
     * @param \Akaddev\GameOfLife\Cell\Cell $cell
     * @return \Generator|Coordinate[]
     */
    private function getNeighboursCoordinates(Cell $cell): \Generator
    {
        $coordinate = $cell->coordinate();
        $x = $coordinate->x();
        $y = $coordinate->y();

        for ($i = $x - 1, $iMax = $x + 1; $i <= $iMax; $i++) {
            for ($j = $y - 1, $jMax = $y + 1; $j <= $jMax; $j++) {
                if ($i === $x && $j === $y) {
                    continue;
                }

                yield new Coordinate($i, $j);
            }
        }
    }

    private function fillNeighbours(Cell $cell)
    {
        foreach ($this->getNeighboursCoordinates($cell) as $coordinate) {
            if ($this->findByCoordinate($coordinate) === null) {
                $this->add(new Cell($coordinate, false));
            }
        }
    }
}
