<?php

namespace App\Repositories;

use App\Models\Translation;

/**
 * @extends BaseRepo<Translation>
 */
class TranslationRepo extends BaseRepo
{
    protected function model(): string
    {
        return Translation::class;
    }
}
