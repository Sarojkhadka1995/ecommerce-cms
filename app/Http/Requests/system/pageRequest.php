<?php

namespace App\Http\Requests\system;

use Illuminate\Http\Request;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class pageRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|max:60000',
            'meta_title' => 'required',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg',
        ];
        if ($request->method() == "POST") {
            $validate = array_merge($validate, [
                'slug' => ['required', 'string', 'max:255', Rule::unique('pages', 'slug')],
            ]);
        }
        if ($request->method() == "PUT") {
            $validate = array_merge($validate, [
                'slug' => ['required', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($request->id)],
            ]);
        }
        return $validate;
    }
}
