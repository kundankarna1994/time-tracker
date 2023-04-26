<?php

namespace App\Http\Requests;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ResentInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => "required|email|max:255",
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                //check if invitation exists
                $invitation = Invitation::where("email",$this->email)
                    ->inactive()
                    ->first();
                if (!$invitation) {
                    $validator->errors()->add('email','No invitation found for this email');
                }
                //check if already user exits
                $user = User::where("email",$this->email)->first();
                if ($user) {
                    $validator->errors()->add('email','Member is already registered');
                }
            }
        ];
    }
}
