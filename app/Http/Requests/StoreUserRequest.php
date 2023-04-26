<?php

namespace App\Http\Requests;

use App\Models\Invitation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreUserRequest extends FormRequest
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
            'name' => "required|string|max:255",
            'email' => "required|email|max:255|unique:users",
            'password' => "required|string|max:255|confirmed",
            'token' => "required|string|max:255"
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $invitation = Invitation::where("email",$this->email)
                        ->inactive()
                        ->first();
                if (!$invitation) {
                    $validator->errors()->add('email','No invitation found for this email');
                }
                if($invitation && $invitation->token !== $this->token){
                    $validator->errors()->add('token','Invitation Token MisMatch');
                }
                if($invitation && $invitation->expiration > Carbon::now()){
                    $validator->errors()->add('email','Invitation expired for this email address');
                }
            }
        ];
    }
}
