<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientController extends Controller
{
    use HttpResponses;

    private Client $model;

    public function __construct(Client $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     */
    public function index() : ResourceCollection
    {
        return ClientResource::collection($this->model->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request) : ClientResource
    {
        $data = $request->validated();
        $client = $this->model->create($data);
        return new ClientResource($client);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client) : ClientResource
    {
        return new ClientResource($client->load("projects"));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): ClientResource
    {
        $data = $request->validated();
        $client->update($data);
        return new ClientResource($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client) : JsonResponse
    {
        $client->delete();
        return $this->success([]);
    }
}
