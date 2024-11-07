<?php

namespace Hanoi;

class Game
{
    private array $tower1 = [];
    private array $tower2 = [];
    private array $tower3 = [];

    public function checkWin(): bool
    {
        if (!empty($this->tower1) || !empty($this->tower2)) {
            return false;
        }

        return [1, 2, 3, 4, 5, 6, 7] === $this->tower3;
    }

    public function setState(array $tower1, array $tower2, array $tower3): void
    {
        $this->tower1 = $tower1;
        $this->tower2 = $tower2;
        $this->tower3 = $tower3;
    }
}