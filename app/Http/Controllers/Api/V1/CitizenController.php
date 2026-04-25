<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCitizenRequest;
use App\Http\Requests\V1\UpdateCitizenRequest;
use App\Http\Resources\V1\CitizenResource;
use App\Models\Citizen;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CitizenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $query = Citizen::query();

        // Scoping: OperatorDesa only see their desa
        if ($user->role->slug === 'operatordesa') {
            $query->where('desa_id', $user->desa_id);
        }

        $citizens = $query->paginate($request->input('per_page', 15));

        return CitizenResource::collection($citizens);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCitizenRequest $request): CitizenResource
    {
        $citizen = Citizen::create($request->validated());

        return new CitizenResource($citizen);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Citizen $citizen): CitizenResource
    {
        $user = $request->user();

        // Scoping: OperatorDesa only see their desa
        if ($user->role->slug === 'operatordesa' && $user->desa_id !== $citizen->desa_id) {
            abort(403, 'Unauthorized access to citizen data in another village.');
        }

        return new CitizenResource($citizen);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCitizenRequest $request, Citizen $citizen): CitizenResource
    {
        $citizen->update($request->validated());

        return new CitizenResource($citizen);
    }
}
