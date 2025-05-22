<?php

namespace App\Models;

use App\Models\Base\YieldRequest as BaseYieldRequest;

/**
 * @property int $id
 * @property float $map_lat
 * @property float $map_long
 * @property float|null $cassava_unit_weight
 * @property float|null $cassava_unit_price
 * @property float|null $max_investment
 * @property float|null $field_area
 * @property \Illuminate\Support\Carbon $planting_date
 * @property \Illuminate\Support\Carbon $harvest_date
 * @property string $country
 * @property string|null $client
 * @property string|null $area_units
 * @property string|null $user_name
 * @property string|null $user_phone_code
 * @property string|null $user_phone_number
 * @property string|null $cassava_pd
 * @property string|null $field_description
 * @property string|null $user_email
 * @property bool|null $processed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $recommendation_text
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereAreaUnits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereCassavaPd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereCassavaUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereCassavaUnitWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereFieldArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereFieldDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereHarvestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereMapLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereMapLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereMaxInvestment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest wherePlantingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereRecommendationText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereUserEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereUserPhoneCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|YieldRequest whereUserPhoneNumber($value)
 *
 * @mixin \Eloquent
 */
class YieldRequest extends BaseYieldRequest {}
