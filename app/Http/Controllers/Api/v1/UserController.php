<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return UserResource::collection($this->model->with('roles')->paginate(10));
    }

}
