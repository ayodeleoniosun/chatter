<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GroupMemberStatus extends Enum
{
    const ACTIVE = 'active';
    const REMOVED = 'removed';
    const BLOCKED = 'blocked';
    const exited = 'exited';
}
