<?php

namespace Hanoi;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Game
{
    private array $tower1 = [];
    private array $tower2 = [];
    private array $tower3 = [];

    public function __construct()
    {
        $this->tower1 = [1, 2, 3, 4, 5, 6, 7];
        $this->tower2 = [];
        $this->tower3 = [];
    }

    public function checkWin(): bool
    {
        if (!empty($this->tower1) || !empty($this->tower2)) {
            return false;
        }

        return [1, 2, 3, 4, 5, 6, 7] === $this->tower3;
    }

    public function setState(array $towers): void
    {
        $this->tower1 = $towers[0];
        $this->tower2 = $towers[1];
        $this->tower3 = $towers[2];
    }

    public function getState(): array
    {
        return [
            $this->tower1,
            $this->tower2,
            $this->tower3
        ];
    }

    public function move(int $from, int $to): void
    {
        $towers = $this->getState();

        $fromTower = $towers[$from - 1];
        $toTower = $towers[$to - 1];

        if (empty($fromTower)) {
            throw new \LogicException('No discs to move from tower ' . $from);
        }

        if (isset($toTower[0]) && $fromTower[0] > $toTower[0]) {
            throw new \LogicException('Cannot move from tower ' . $from . ' to tower ' . $to);
        }

        $disk = array_shift($towers[$from - 1]);
        array_unshift($towers[$to - 1], $disk);

        $this->setState([$towers[0], $towers[1], $towers[2]]);
    }

    public function printTowers(): string
    {
        $towers = array_map(function (array $tower) {
            return array_pad($tower, -7, 0);
        }, [$this->tower1, $this->tower2, $this->tower3]);

        $lines = array_map(null, ...$towers);

        $output = PHP_EOL;

        foreach ($lines as $line) {
            foreach ($line as $diskSize) {
                $string = $diskSize == 0 ? '|' : str_repeat('=', $diskSize);

                $output .= str_pad($string, 20, " ", STR_PAD_BOTH);
            }

            $output .= PHP_EOL;
        }

        $output .= PHP_EOL;

        return $output;
    }

    public static function fromSession(SessionInterface $session): ?Game
    {
        if ($session->has('game')) {
            $game = new Game();
            $game->setState($session->get('game'));

            return $game;
        }

        return null;
    }
}