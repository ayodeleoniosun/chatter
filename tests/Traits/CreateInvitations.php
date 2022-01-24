<?php

namespace Tests\Traits;

use App\Models\Invitation;

trait CreateInvitations
{
    protected function createInvitation()
    {
        return Invitation::factory()->create();
    }
}
