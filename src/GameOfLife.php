<?php

namespace Akaddev\GameOfLife;

use Akaddev\GameOfLife\Cell\Cell;
use Akaddev\GameOfLife\Cell\CellRepository;
use Akaddev\GameOfLife\Cell\Coordinate;

class GameOfLife
{
    public function __construct(private CellRepository $cellRepository)
    {
    }

    public function tick()
    {
        $cells = $this->cellRepository->getAll();

        $toUpdate = [];

        foreach ($cells as $cell) {
            $neighboursCount = $this->cellRepository->countAliveNeighbours($cell);

            if ($neighboursCount < 2 || $neighboursCount > 3) {
                $cell->die();
            }

            if ($neighboursCount === 3) {
                $cell->revive();
            }

            $toUpdate[] = $cell;
        }

        foreach ($toUpdate as $item) {
            $this->cellRepository->update($item);
        }

        $this->cellRepository->cleanUp();
    }

    public function plantGliderSeed()
    {
        $this->cellRepository->clearAll();

        $this->cellRepository->add(new Cell(new Coordinate(11, 12), true));
        $this->cellRepository->add(new Cell(new Coordinate(12, 13), true));
        $this->cellRepository->add(new Cell(new Coordinate(13, 11), true));
        $this->cellRepository->add(new Cell(new Coordinate(13, 12), true));
        $this->cellRepository->add(new Cell(new Coordinate(13, 13), true));
    }
}
