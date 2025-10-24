<?php

namespace App\Models;

use App\Models\Base\VAppRequestStatsView as BaseVAppRequestStatsView;

class VAppRequestStatsView extends BaseVAppRequestStatsView
{
    protected $hidden = [
        'device_token',
    ];
}
