<?php

use Hanoi\Game;

require __DIR__ . '/vendor/autoload.php';

session_start();

$game = new Game();

if (isset($_SESSION['game'])) {
    $game->setState($_SESSION['game']);
} else {
    echo "Starting a new game" . PHP_EOL;

    $_SESSION['game'] = $game->getState();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && $uri === '/state') {
    $game->printTowers();

    if($game->checkWin()){
        echo PHP_EOL;
        echo '##### YOU WON #####';
        echo PHP_EOL;
    }

    exit;
}

if ($method === 'POST' && preg_match('#^/move/(\d+)/(\d+)$#', $uri, $matches)) {
    [$fullMatch, $from, $to] = $matches;

    $from = (int)$from;
    $to = (int)$to;

    $game->move($from, $to);
    $game->printTowers();

    $_SESSION['game'] = $game->getState();

    exit;
}

echo "Not found";