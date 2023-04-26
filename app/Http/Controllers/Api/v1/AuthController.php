<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    private User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function register(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = $this->model->create($data);
        $token = $user->createToken('API Token of ' . $user->name)->plainTextToken;
        return $this->success([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        $data = $request->validated();
        if(!Auth::attempt($data)){
            return $this->error([],'Credentials do not match',404);
        }
        $user = Auth::user();
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
       $user = Auth::user();
       $user->currentAccessToken()->delete();
        return $this->success([],"Successfully Logged Out");
    }
}
