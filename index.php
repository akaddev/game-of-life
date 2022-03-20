<?php

require 'vendor/autoload.php';

use Akaddev\GameOfLife\Cell\CellRepository;
use Akaddev\GameOfLife\Cell\Coordinate;
use Akaddev\GameOfLife\GameOfLife;

function printToConsole(int $sizeX, int $sizeY, CellRepository $cellRepository)
{
    foreach (range(0, $sizeX) as $x) {
        foreach (range(0, $sizeY) as $y) {
            $coordinate = new Coordinate($x, $y);
            $cell = $cellRepository->findByCoordinate($coordinate);

            echo null !== $cell && $cell->isAlive() ? '⬛' : '⬜';
        }

        echo PHP_EOL;
    }
}

$cellRepository = new CellRepository();

$game = new GameOfLife($cellRepository);
$game->plantGliderSeed();

foreach (range(1, 55) as $n) {
    echo "\033c";
    printToConsole(25, 25, $cellRepository);
    usleep(500000);

    $game->tick();
}
