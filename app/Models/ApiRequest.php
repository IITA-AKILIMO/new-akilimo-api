<?php

namespace App\Models;

use App\Models\Base\ApiRequest as BaseApiRequest;

/**
 * @property int $id
 * @property string $request_id
 * @property string|null $droid_request
 * @property string|null $plumber_request
 * @property string|null $plumber_response
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
class ApiRequest extends BaseApiRequest {}
