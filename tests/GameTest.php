<?php

namespace Hanoi\tests;

use Hanoi\exception\InvalidMoveException;
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
            [
                $tower1,
                $tower2,
                $tower3
            ]
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

    #[Test]
    public function testGetState()
    {
        $game = new Game();

        $this->assertEquals([[1, 2, 3, 4, 5, 6, 7], [], []], $game->getState());
    }

    #[Test]
    public function testSetState()
    {
        $game = new Game();
        $this->assertEquals([[1, 2, 3, 4, 5, 6, 7], [], []], $game->getState());

        $expectedState = [[4, 6, 7], [1, 2, 3], [5]];
        $game->setState($expectedState);
        $this->assertEquals($expectedState, $game->getState());
    }

    #[Test]
    public function testMove()
    {
        $game = new Game();
        $game->setState([[2, 3], [1, 5, 6], [4, 7]]);

        $game->move(1, 3);

        $this->assertEquals([[3], [1, 5, 6], [2, 4, 7]], $game->getState());

        $game->move(2, 1);
        $this->assertEquals([[1, 3], [5, 6], [2, 4, 7]], $game->getState());
    }

    #[Test]
    public function testMoveInvalid()
    {
        $game = new Game();
        $game->setState([[2, 3], [1, 5, 6], [4, 7]]);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Cannot move from tower 3 to tower 1');

        $game->move(3, 1);
    }

    #[Test]
    public function testMoveNoDisksToMove()
    {
        $game = new Game();
        $game->setState([[], [1, 2, 3, 4, 5, 6], [7]]);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No discs to move from tower 1');

        $game->move(1, 3);
    }

    #[Test]
    public function testMoveToEmptyTower()
    {
        $game = new Game();
        $game->setState([[2, 3], [1, 4, 5, 6, 7], []]);

        $game->move(1, 3);

        $this->assertEquals([[3], [1, 4, 5, 6, 7], [2]], $game->getState());

    }
}