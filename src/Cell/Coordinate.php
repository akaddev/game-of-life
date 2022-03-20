<?php

namespace Akaddev\GameOfLife\Cell;

class Coordinate
{
    public function __construct(private int $x, private int $y)
    {
    }

    public function x(): int
    {
        return $this->x;
    }

    public function y(): int
    {
        return $this->y;
    }
}
