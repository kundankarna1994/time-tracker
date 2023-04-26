<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResentInvitationRequest;
use App\Http\Requests\StoreInvitationRequest;
use App\Models\Invitation;
use App\Notifications\MemberInviteNotification;
use App\Traits\HttpResponses;

class InvitationController extends Controller
{
    use HttpResponses;

    protected Invitation $model;

    public function __construct(Invitation $model)
    {
        $this->model = $model;
    }

    public function invite(StoreInvitationRequest $request)
    {
        $data = $request->validated();
        $invitation = $this->model->create($data);
        $invitation->notify(new MemberInviteNotification());
        return $this->success([],"Successfully Sent Invitation");
    }

    public function resend(ResentInvitationRequest $request)
    {
        $invitation = $this->model->where("email",$request->email)->first();
        $invitation->notify(new MemberInviteNotification());
        return $this->success([],"Successfully Resent Invitation");
    }
}
