<?php

namespace App\Http\Requests;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class StoreInvitationRequest extends FormRequest
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
            'email' => "required|email|max:255|unique:invitations",
            'invited_by' => "required|integer",
            'token' => "required|string|max:255",
            'expiration' => "required|date"
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'invited_by' => Auth::id(),
            'token' => Str::uuid()->toString(),
            'expiration' => Carbon::now()->addHour(1)
        ]);
    }

    public function messages()
    {
        return [
            'email.unique' => "Invitation Sent Already"
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                //check if already user exits
                $user = User::where("email",$this->email)->first();
                if ($user) {
                    $validator->errors()->add('email','Already a member');
                }
            }
        ];
    }
}
