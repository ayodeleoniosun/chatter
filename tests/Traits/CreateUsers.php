<?php

namespace Tests\Traits;

use App\Models\User;

trait CreateUsers
{
    protected function createUser()
    {
        return User::factory()->create();
    }
}
