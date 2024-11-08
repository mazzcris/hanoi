<?php

namespace Hanoi\Controller;

use Hanoi\Game;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameController
{
    private SessionInterface $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function new(): Response
    {
        $game = new Game();
        $this->session->set('game', $game->getState());

        $responseContent = PHP_EOL . '#### NEW GAME #####' . PHP_EOL;

        return new Response($responseContent . $game->printTowers());
    }

    public function state(): Response
    {
        $game = Game::fromSession($this->session);
        if (!$game) {
            return new Response(PHP_EOL . '#### NO GAME AVAILABLE, PLEASE START A NEW GAME #####' . PHP_EOL . PHP_EOL);
        }

        $this->session->set('game', $game->getState());

        return new Response($game->printTowers());
    }

    public function move(int $from, int $to): Response
    {
        $game = Game::fromSession($this->session);

        if (!$game) {
            return new Response(PHP_EOL . '#### NO GAME AVAILABLE, PLEASE START A NEW GAME #####' . PHP_EOL . PHP_EOL);
        }

        try {
            $game->move($from, $to);
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $this->session->set('game', $game->getState());

        $content = $game->printTowers();
        if ($game->checkWin()) {
            $content .= PHP_EOL . '##### YOU WON #####' . PHP_EOL . PHP_EOL;
        }

        return new Response($content);
    }
}