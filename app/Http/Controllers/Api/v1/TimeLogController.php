<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EndTimeLogRequest;
use App\Http\Requests\StartTimeLogRequest;
use App\Http\Resources\TimeLogResource;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    private TimeLog $model;

    public function __construct(TimeLog $model)
    {
        $this->model = $model;
    }

    public function index(Request $request,User $user)
    {
        $queries = $request->query();
        $model = $this->model->query();
        if(isset($queries['project'])){
            $model = $this->model->where('project_id',$queries['project']);
        }
        return TimeLogResource::collection($model->where("user_id",$user->id)->get());
    }

    public function start(StartTimeLogRequest $request)
    {
        $data =  $request->validated();
        $timeLog = $this->model->create($data);
        return new TimeLogResource($timeLog->load("project"));
    }

    public function end(EndTimeLogRequest $request,TimeLog $timeLog)
    {
        $data =  $request->validated();
        $data['total'] = $timeLog->start->diffInMinutes(Carbon::parse($data['end']));
        $timeLog->update($data);
        return new TimeLogResource($timeLog->load("project"));
    }
}
