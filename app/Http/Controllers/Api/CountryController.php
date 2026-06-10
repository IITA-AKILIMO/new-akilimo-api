<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Repositories\CountryRepo;
use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountryController extends Controller
{
    public function __construct(protected CountryRepo $repo) {}

    /**
     * @unauthenticated
     */
    #[Endpoint(title: 'List Countries', description: 'Retrieves a list of all active countries.')]
    public function index(Request $request): AnonymousResourceCollection
    {
        $countries = $this->repo->allActive();

        return CountryResource::collection($countries);
    }
}
