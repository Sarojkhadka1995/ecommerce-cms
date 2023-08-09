<?php

namespace App\Http\Requests\system;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class userRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $validate = [
            'name' => 'required',
            'role_id' => 'required',
        ];

        if ($request->method() == 'POST') {
            $validate = array_merge($validate, [
                'username' => ['required',
                    Rule::unique('users')->where(function ($query) {
                        return $query->where('username', strtolower(request()->username));
                    })
                ],
                'email' => ['required', 'email',
                    Rule::unique('users')->where(function ($query) {
                        return $query->where('email', strtolower(request()->email));
                    })
                ],
            ]);
        }
        if ($request->method() == 'PUT') {
            $validate = array_merge($validate, [
                'username' => 'required|unique:users,username,' . $request->user,
                'email' => 'required|email|unique:users,email,' . $request->user,
            ]);
        }

        if ($request->set_password_status == 1) {
            $validate = array_merge($validate, [
                'password' => 'required|confirmed|min:6',
                'password_confirmation' => 'required',
            ]);
        }

        return $validate;
    }
}
