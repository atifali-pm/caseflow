<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isProvider();
    }

    public function view(User $user, Client $client): bool
    {
        if ($user->isAdmin()) return true;
        return $client->provider_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isProvider();
    }

    public function update(User $user, Client $client): bool
    {
        if ($user->isAdmin()) return true;
        return $client->provider_id === $user->id;
    }

    public function delete(User $user, Client $client): bool
    {
        if ($user->isAdmin()) return true;
        return $client->provider_id === $user->id;
    }
}
