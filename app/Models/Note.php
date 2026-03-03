<?php

declare(strict_types=1);

namespace App\Models;

use Lubix\Orm\Model\Model;

final class Note extends Model
{
    protected static string $table = 'notes';
    protected static string $primaryKey = 'id';
}
