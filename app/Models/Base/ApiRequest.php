<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiRequest
 *
 * @property int $id
 * @property string $request_id
 * @property array $droid_request
 * @property array $plumber_request
 * @property array $plumber_response
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereDroidRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest wherePlumberRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest wherePlumberResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiRequest whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ApiRequest extends Model
{
    protected $table = 'api_requests';

    protected $columns = [
        'id',
        'request_id',
        'droid_request',
        'plumber_request',
        'plumber_response',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'droid_request' => 'json',
        'plumber_request' => 'json',
        'plumber_response' => 'json',
    ];

    protected $fillable = [
        'request_id',
        'droid_request',
        'plumber_request',
        'plumber_response',
    ];
}
