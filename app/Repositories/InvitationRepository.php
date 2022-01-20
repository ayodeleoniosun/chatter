<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Invitation;

class InvitationRepository
{
    private Invitation $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function create(array $data): Invitation
    {
        return $this->invitation->create($data);
    }
}
