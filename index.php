<?php

require __DIR__ . '/vendor/autoload.php';

use Hanoi\Controller\GameController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

if (!$request->hasSession()) {
    $session = new \Symfony\Component\HttpFoundation\Session\Session();
    $session->start();
    $request->setSession($session); // Set the session in the Request
} else {
    $session = $request->getSession();
}

$routes = new RouteCollection();
$routes->add('new', new Route('/new', [
    '_controller' => [
        new GameController($session), 'new'
    ],
]));
$routes->add(
    'state',
    new Route('/state', [
        '_controller' => [
            new GameController($session), 'state'
        ]
    ])
);
$routes->add('move', new Route('/move/{from}/{to}', [
    '_controller' => [
        new GameController($session), 'move'
    ],
], [], [], '', [], ['POST']));

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    $attributes = $matcher->match($request->getPathInfo());
    $request->attributes->add($attributes);

    $controllerResolver = new ControllerResolver();
    $argumentResolver = new ArgumentResolver();

    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);
} catch (Exception $e) {
    $response = new Response('Not found', Response::HTTP_NOT_FOUND);
}

$response->send();
