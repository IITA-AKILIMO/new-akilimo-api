<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ApiRequestRepo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class RequestLogController extends Controller
{
    public function __construct(protected ApiRequestRepo $repo) {}

    public function index(Request $request): Response
    {
        $perPage = min((int) $request->get('per_page', 25), 100);
        $orderBy = $request->get('sort_by', 'created_at');
        $direction = $request->get('sort_dir', 'desc');

        $filters = [
            'country'   => (string) $request->get('country', ''),
            'use_case'  => (string) $request->get('use_case', ''),
            'excluded'  => $request->get('excluded', ''),
            'date_from' => (string) $request->get('date_from', ''),
            'date_to'   => (string) $request->get('date_to', ''),
            'search'    => (string) $request->get('search', ''),
        ];

        $paginator = $this->repo->paginate($perPage, $orderBy, $direction, $filters);

        return Inertia::render('Requests/Index', [
            'requests' => [
                'data' => collect($paginator->items())->map(fn ($r) => [
                    'id'          => $r->id,
                    'request_id'  => $r->request_id,
                    'device_token' => $r->device_token ? substr($r->device_token, 0, 8).'****' : null,
                    'country_code' => $r->country_code,
                    'use_case'    => $r->use_case,
                    'excluded'    => (bool) $r->excluded,
                    'duration_ms' => $r->request_duration_ms,
                    'created_at'  => $r->created_at?->toIso8601String(),
                ])->all(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                    'from'         => $paginator->firstItem(),
                    'to'           => $paginator->lastItem(),
                ],
                'links' => [
                    'first' => $paginator->url(1),
                    'last'  => $paginator->url($paginator->lastPage()),
                    'prev'  => $paginator->previousPageUrl(),
                    'next'  => $paginator->nextPageUrl(),
                ],
            ],
            'filters'   => array_merge($filters, ['sort_by' => $orderBy, 'sort_dir' => $direction]),
            'use_cases' => $this->repo->useCases(),
        ]);
    }

    public function show(int $id): Response
    {
        $r = $this->repo->findOrFail($id);

        return Inertia::render('Requests/Show', [
            'request' => [
                'id'               => $r->id,
                'request_id'       => $r->request_id,
                'device_token'     => $r->device_token,
                'country_code'     => $r->country_code,
                'full_names'       => $r->full_names,
                'phone_number'     => $r->phone_number,
                'gender_code'      => $r->gender_code,
                'use_case'         => $r->use_case,
                'excluded'         => (bool) $r->excluded,
                'duration_ms'      => $r->request_duration_ms,
                'created_at'       => $r->created_at?->toIso8601String(),
                'droid_request'    => $r->droid_request,
                'plumber_request'  => $r->plumber_request,
                'plumber_response' => $r->plumber_response,
            ],
        ]);
    }
}
