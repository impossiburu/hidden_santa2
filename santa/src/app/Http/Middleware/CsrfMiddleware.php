<?php

namespace Sda\Santa\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CsrfMiddleware
{
    public function handle(Request $request)
    {
        if ($request->getMethod() !== 'POST') {
            return null;
        }

        $cookie = $request->cookies->get('csrf_token');
        $form   = $request->request->get('csrf_token');

        if (!$cookie || !$form || !hash_equals($cookie, $form)) {
            return new Response("Доступ запрещен", Response::HTTP_FORBIDDEN);
        }

        return null;
    }

    public static function attachToken(Response $response)
    {
        if (!isset($_COOKIE['csrf_token'])) {
            $token = bin2hex(random_bytes(32));
            $response->headers->setCookie(
                new \Symfony\Component\HttpFoundation\Cookie(
                    'csrf_token', $token,
                    time() + 3600,
                    '/',
                    null,
                    false, true, false, 'Lax'
                )
            );
        }
    }
}
