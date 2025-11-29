<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class OperationsDoneData extends Data
{
    public bool $ploughingDone;
    public bool $harrowingDone;
    public bool $ridgingDone;
}
