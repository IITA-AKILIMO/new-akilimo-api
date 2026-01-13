<?php

namespace App\Repositories;

use App\Models\OperationCost;
use App\Models\StarchFactory;
use App\Models\UserFeedback;


class UserFeedBackRepo extends BaseRepo
{
    protected function model(): string
    {
        return UserFeedback::class;
    }
}
