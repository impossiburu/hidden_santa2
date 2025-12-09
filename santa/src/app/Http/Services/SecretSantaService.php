<?php

namespace Sda\Santa\Http\Services;

use Sda\Santa\Http\Models\User;
use Sda\Santa\Http\Models\SantaPair;
use Illuminate\Support\Collection;

class SecretSantaService
{
    public function generate(): array
    {
        // Получаем пользователей
        $users = User::all();

        if ($users->count() < 2) {
            throw new \Exception("Not enough users for Secret Santa");
        }

        $users = $users->shuffle();

        // max попыток чтобы не попасть в дедлок
        $maxAttempts = 10;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {

            $shuffled = $users->shuffle();

            $pairs = $this->makeRingPairs($shuffled);

            if ($this->isValidPairs($pairs)) {
                // Все ок
                return $pairs;
            }
        }

        throw new \Exception("Unable to generate valid Secret Santa pairs");
    }

    private function makeRingPairs(Collection $users): array
    {
        $pairs = [];

        $count = count($users);

        for ($i = 0; $i < $count; $i++) {
            $giver = $users[$i];
            $receiver = $users[($i + 1) % $count]; // кольцо
            $pairs[] = [$giver, $receiver];
        }

        return $pairs;
    }

    private function isValidPairs(array $pairs): bool
    {
        foreach ($pairs as [$giver, $receiver]) {

            if ($giver->id === $receiver->id) {
                return false;
            }
            // проверяем метки ограничений
            $restricted = $giver->restrictedUsers()->pluck('restricted_user_id')->toArray();

            if (in_array($receiver->id, $restricted)) {
                return false;
            }
        }

        return true;
    }

    public function savePairs(array $pairs)
    {
        SantaPair::truncate(); // сбрасываем предыдущие пары

        foreach ($pairs as [$giver, $receiver]) {
            SantaPair::create([
                'giver_id' => $giver->id,
                'receiver_id' => $receiver->id
            ]);
        }
    }
}
