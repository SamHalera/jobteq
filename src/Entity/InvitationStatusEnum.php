<?php

namespace App\Entity;

enum InvitationStatusEnum: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';
}
