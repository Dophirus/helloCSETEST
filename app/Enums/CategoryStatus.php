<?php

namespace App\Enums;

enum CategoryStatus: string
{
    case ONLINE = 'en ligne';
    case DISABLED = 'désactivée';
    case ARCHIVED = 'archivée';
}
