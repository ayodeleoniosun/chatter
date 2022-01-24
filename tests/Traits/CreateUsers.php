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

    protected function authUser()
    {
        return Sanctum::actingAs($this->createUser());
    }
}
