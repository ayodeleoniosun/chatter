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

    public function getToken(string $token): ?Invitation
    {
        return $this->invitation->where([
            'token' => $token,
            'used'  => false
        ])->first();
    }

    public function invalidateToken(Invitation $invitation): void
    {
        $invitation->used = true;
        $invitation->save();
    }

}
