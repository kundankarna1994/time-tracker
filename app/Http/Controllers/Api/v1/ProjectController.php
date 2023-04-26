<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectController extends Controller
{
    use HttpResponses;

    private Project $model;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     */
    public function index() : ResourceCollection
    {
        return ProjectResource::collection($this->model->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request) : ProjectResource
    {
        $data = $request->validated();
        $client = $this->model->create($data);
        return new ProjectResource($client);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project) : ProjectResource
    {
        return new ProjectResource($project->load("client"));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): ProjectResource
    {
        $data = $request->validated();
        $project->update($data);
        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project) : JsonResponse
    {
        $project->delete();
        return $this->success([]);
    }
}
