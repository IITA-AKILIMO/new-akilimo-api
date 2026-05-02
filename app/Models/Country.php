<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property bool $active
 * @property int|null $sort_order
 */
class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = [
        'code',
        'name',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
