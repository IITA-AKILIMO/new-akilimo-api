<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedBackRequest;
use App\Http\Resources\Collections\CassavaPriceResourceCollection;
use App\Http\Resources\Collections\UserFeedbackResourceCollection;
use App\Http\Resources\UserFeedbackResource;
use App\Repositories\CassavaPriceRepo;
use App\Repositories\UserFeedBackRepo;
use Illuminate\Http\Request;

class UserFeedBackController extends Controller
{
    public function __construct(protected UserFeedBackRepo $repo)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50);
        $orderBy = $request->input('order_by', 'sort_order');
        $sort = $request->input('sort', 'asc');

        $userFeedbackData = $this->repo->paginateWithSort(
            perPage: $perPage,
            orderBy: $orderBy,
            direction: $sort,
        );

        return UserFeedbackResourceCollection::make($userFeedbackData);
    }

    public function store(FeedBackRequest $request)
    {
        $data = $request->toPersistenceArray();
        $feedBack = $this->repo->create($data);
        return Userfeedbackresource::make($feedBack);
    }
}
