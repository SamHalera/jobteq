<?php

namespace App\Entity;

enum StatusApplicationEnum: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case UNDER_STUDY = 'under study';
}
