<?php

namespace Sda\Santa\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use  Sda\Santa\Http\Services\SecretSantaService;

class SantaController
{
    public function generate(Request $request): Response
    {
        try {
            $service = new SecretSantaService();
            $pairs = $service->generate();
            $service->savePairs($pairs);

            return new Response("Secret Santa generated successfully!");
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    public function mySanta(Request $request): Response
    {
        $user = $request->attributes->get('user');

        $pair = $user->santaReceiver;

        if (!$pair) {
            return new Response("Pairs are not generated yet.");
        }

        $receiver = $pair->receiver;

        return new Response("You must give a gift to: " . $receiver->email);
    }
}
