<?php

namespace App\Http\Requests\system;

use App\Model\Config;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ConfigRequest extends FormRequest
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
        $config = Config::where('id', $request->config)->first();

        $validate = [];
        if ($request->method() == 'POST') {
            $validate = [
                'label' => 'required|unique:configs,label',
                'type' => 'required|in:text,textarea,file,number',
            ];
            if ($request->type == 'file') {
                $validate = array_merge($validate, ['value' => 'required|image|mimes:jpg,png,jpeg,bmp']);
            } else {
                $validate = array_merge($validate, ['value' => 'required']);
            }
        } else {
            if ($config->type == 'file') {
                $validate = array_merge($validate, ['value' => 'required|image|mimes:jpg,png,jpeg,bmp']);
            } else {
                if($config->label == 'cms title'){
                    $validate = array_merge($validate, ['value' => 'nullable']);

                }else{
                    $validate = array_merge($validate, ['value' => 'required']);
                }
            }
        }
        return $validate;
    }
}
