<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
    public function rules()
    {
        $rules = [
            'id'        => 'numeric|required',
            'name'      => 'required|min:2|max:30',
            'model'     => 'required|min:2|max:30',
            'code'      => 'required|min:2|max:30',
        ];

        return $rules;
    }

    /**
     * 验证信息
     * @author Sheldon
     * @date   2017-04-21T14:52:55+0800
     * @return [type]                   [description]
     */
    public function messages()
    {
        return [
            'required'  => trans('validation.required'),
            'min'       => trans('validation.min'),
            'max'       => trans('validation.max'),
            'numeric'   => trans('validation.numeric'),
        ];
    }
    /**
     * 字段名称
     * @author Sheldon
     * @date   2017-04-21T14:52:38+0800
     * @return [type]                   [description]
     */
    public function attributes()
    {
        return [
            'id'            => trans('admin/product.model.id'),
            'name'          => trans('admin/product.model.name'),
            'model'         => trans('admin/product.model.slug'),
            'code'          => trans('admin/product.model.url'),
        ];
    }
}
