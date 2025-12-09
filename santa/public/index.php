<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sda\Santa\Http\Middleware\CsrfMiddleware;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__.'/../bootstrap/database.php';

$request = Request::createFromGlobals();
$response = null;

// ====== GLOBAL MIDDLEWARE PIPELINE ======
$globalMiddleware = [
    new CsrfMiddleware(),  // для POST
];

foreach ($globalMiddleware as $middleware) {
    $res = $middleware->handle($request);
    if ($res instanceof Response) {
        $res->send();
        exit;
    }
}
// ========================================

$routes = require __DIR__.'/../router.php';

$method = $request->getMethod();
$path   = $request->getPathInfo();

$key = "$method $path";

if (!isset($routes[$key])) {
    (new Response("Not found", Response::HTTP_NOT_FOUND))->send();
    exit;
}

$response = $routes[$key]($request);
// CsrfMiddleware::attachToken($response);
$response->send();
