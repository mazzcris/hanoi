<?php

namespace Hanoi\tests;

use Hanoi\Game;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    #[Test]
    #[DataProvider('stateProvider')]
    public function testCheckWin(array $tower1, array $tower2, array $tower3, bool $isWin): void
    {
        $game = new Game();
        $game->setState(
            $tower1,
            $tower2,
            $tower3
        );

        $this->assertEquals($isWin, $game->checkWin());
    }

    static function stateProvider(): array
    {
        return [
            [[], [], [1, 2, 3, 4, 5, 6, 7], true],
            [[], [2, 3], [1, 4, 5, 6, 7], false],
            [[1], [2], [3, 4, 5, 6, 7], false],
        ];
    }

}