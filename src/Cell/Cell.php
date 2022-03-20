<?php

namespace Akaddev\GameOfLife\Cell;

class Cell
{
    public function __construct(private Coordinate $coordinate, private bool $isAlive)
    {
    }

    public function coordinate(): Coordinate
    {
        return $this->coordinate;
    }

    public function isAlive(): bool
    {
        return $this->isAlive;
    }

    public function die()
    {
        $this->isAlive = false;
    }

    public function revive()
    {
        $this->isAlive = true;
    }
}
