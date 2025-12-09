<?php

namespace Sda\Santa\Http\Middleware;

use Sda\Santa\Http\Models\AuthToken;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request)
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return new Response("Отсутствует токен", Response::HTTP_UNAUTHORIZED);
        }

        $tokenStr = substr($authHeader, 7);

        $token = AuthToken::where('token', $tokenStr)
            ->where('expires_at', '>', Carbon::now('Europe/Moscow')->shiftTimezone('UTC'))
            ->first();

        if (!$token) {
            return new Response("Неверный токен", Response::HTTP_UNAUTHORIZED);
        }

        // Добавляем текущего пользователя в Request атрибуты
        $request->attributes->set('user', $token->user);

        return null;
    }
}
