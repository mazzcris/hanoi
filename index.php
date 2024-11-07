<?php

use Hanoi\Game;

require __DIR__ . '/../vendor/autoload.php';

$game = new Game();

printTowers($game->getState());


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

if ($method === 'GET' && $uri === '/state') {
    echo json_encode($game->getState());
    exit;
}

if ($method === 'POST' && preg_match('#^/move/(\d+)/(\d+)$#', $uri, $matches)) {
    [$fullMatch, $from, $to] = $matches;
    $from = (int) $from;
    $to = (int) $to;

    $success = $game->move($from, $to);
    echo json_encode([
        'success' => $success,
        'state' => $game->getState()
    ]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Not found']);


function printTowers(array $towers)
{
    $towers = array_map(function (array $tower) {
        return array_pad($tower, -7, 0);
    }, $towers);

    $lines = array_map(null, ...$towers);

    foreach ($lines as $line) {
        foreach ($line as $diskSize) {
            $string = $diskSize == 0 ? '|' : str_repeat('=', $diskSize);

            echo str_pad($string, 20, " ", STR_PAD_BOTH);
        }

        echo PHP_EOL;
    }
}

