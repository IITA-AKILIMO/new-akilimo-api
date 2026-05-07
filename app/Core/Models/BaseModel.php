<?php

/**
 * (c) 2026 Munywele Consulting LTD — https://munywele.co.ke
 *
 * For license information, see the LICENSE file.
 */

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class BaseModel extends EloquentModel
{
    protected array $commonCasts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getCasts(): array
    {
        return array_merge($this->casts, $this->commonCasts);
    }
}
