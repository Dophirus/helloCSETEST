<?php

namespace App\Enums;

enum ProductStatus: string
{
    case ONLINE = 'en ligne';
    case DISABLED = 'désactivée';
    case DRAFT = 'brouillon';
}
