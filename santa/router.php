<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sda\Santa\Http\Controllers\AuthController;
use Sda\Santa\Http\Controllers\SantaController;
use Sda\Santa\Http\Middleware\AuthMiddleware;
use Sda\Santa\Http\Middleware\CsrfMiddleware;

return [

    'GET /register' => function (Request $req) {
        return (new AuthController)->register($req);
    },

    'POST /register' => function (Request $req) {
        $csrf = (new CsrfMiddleware)->handle($req);
        if ($csrf instanceof Response) {
            return $csrf;
        }

        return (new AuthController)->register($req);
    },

    'GET /login' => fn(Request $req) => (new AuthController)->login($req),
    'POST /login' => function (Request $req) {
        $csrf = (new CsrfMiddleware)->handle($req);
        if ($csrf instanceof Response) {
            return $csrf;
        }

        return (new AuthController)->login($req);
    },

    'GET /profile' => function (Request $req) {
        $auth = (new AuthMiddleware)->handle($req);
        if ($auth instanceof Response) {
            return $auth;
        }

        return (new AuthController)->profile($req);
    },

    'GET /admin/santa/generate' => function (Request $req) {
        (new AuthMiddleware)->handle($req);
        // + проверка admin если надо
        return (new SantaController)->generate($req);
    },

    'GET /my-santa' => function (Request $req) {
       (new AuthMiddleware)->handle($req);
        return (new SantaController)->mySanta($req);
    },

];
