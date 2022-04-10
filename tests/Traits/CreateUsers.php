<?php

namespace Tests\Traits;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait CreateUsers
{
    protected function createUser()
    {
        return User::factory()->create();
    }

    protected function authUser($user = null)
    {
        $user = is_null($user) ? $this->createUser() : $user;

        return Sanctum::actingAs($user);
    }
}
