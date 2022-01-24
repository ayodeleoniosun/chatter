<?php

namespace Tests\Traits;

use App\Models\PasswordReset;

trait CreatePasswordResets
{
    protected function createPasswordReset()
    {
        return PasswordReset::factory()->create();
    }
}
