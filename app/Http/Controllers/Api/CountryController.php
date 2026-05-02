<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Repositories\CountryRepo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountryController extends Controller
{
    public function __construct(protected CountryRepo $repo) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $countries = $this->repo->allActive();

        return CountryResource::collection($countries);
    }
}
