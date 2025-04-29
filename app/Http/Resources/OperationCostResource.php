<?php

namespace App\Http\Resources;

use App\Models\OperationCost;

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

        $averageCost = $model->min_usd + ($model->max_usd - $model->min_usd) / 2;

        return [
            'id' => $model->id,
            'operation_name' => $model->operation_name,
            'operation_type' => $model->operation_type,
            'average_cost' => $averageCost,
//            'min_usd' => $model->min_usd,
//            'max_usd' => $model->max_usd,
//            'min_ngn' => $model->min_ngn,
//            'max_ngn' => $model->max_ngn,
//            'max_tzs' => $model->max_tzs,
//            'min_tzs' => $model->min_tzs,
            'active' => $model->active,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,

        ];
    }
}
