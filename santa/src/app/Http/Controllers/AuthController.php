<?php

namespace Sda\Santa\Http\Controllers;

use Sda\Santa\Http\Models\User;
use Sda\Santa\Http\Models\AuthToken;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function register(Request $request): Response
    {
        if ($request->getMethod() === 'GET') {
            ob_start();
            require __DIR__.'/../../../../views/register.php';
            $html = ob_get_clean();
            return new Response($html);
        }

        $email = htmlspecialchars(filter_var($request->query->get('email'), FILTER_VALIDATE_EMAIL));
        $password = htmlspecialchars(is_string($request->query->get('password')) ?? '');

        if (!$email || !$password) {
            return new Response("Неверные данные введены", Response::HTTP_BAD_REQUEST); 
        }

        if (User::where('email', $email)->exists()) {
            return new Response("Такой уже есть", Response::HTTP_BAD_REQUEST);
        }

        User::create([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return new Response("OK");
    }


    public function login(Request $request): Response
    {
        if ($request->getMethod() === 'GET') {
            ob_start();
            require __DIR__.'/../../../../views/login.php';
            $html = ob_get_clean();
            return new Response($html);
        }

        $email = $request->query->get('email');
        $password = $request->query->get('password');

        $user = User::where('email', $email)->first();

        if (!$user || !password_verify($password, $user->password)) {
            return new Response("Неверный логин", Response::HTTP_UNAUTHORIZED);
        }

        $token = bin2hex(random_bytes(32));

        AuthToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => Carbon::now('Europe/Moscow')->shiftTimezone('UTC')->addDay(),
        ]);

        return new Response(json_encode([
            'token' => $token,
            'user_id' => $user->id,
        ]), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


    public function profile(Request $request): Response
    {
        $user = $request->attributes->get('user');

        return new Response("Здарова, {$user->name}");
    }
}
