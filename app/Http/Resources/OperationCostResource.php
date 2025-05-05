<?php

namespace App\Http\Resources;

use App\Models\OperationCost;
use Illuminate\Support\Str;

class OperationCostResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @var OperationCost $this ->resource
     */
    public function toArray($request): array
    {
        /** @var OperationCost $model */
        $model = $this->resource;

        $avgCost = ($model->min_cost + $model->max_cost) / 2;
        $tag = "{$model->id}";
        if ($avgCost === -1.0) {
            $tag = 'exact';
        }

        return [
            'id' => $model->id,
            'item_tag' => $tag,
            'operation_name' => Str::upper($model->operation_name),
            'operation_type' => Str::upper($model->operation_type),
            'country_code' => $model->country_code,
            'min_cost' => $model->min_cost,
            'max_cost' => $model->max_cost,
            'average_cost' => $avgCost,
            'is_active' => $model->is_active,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ];
    }
}
