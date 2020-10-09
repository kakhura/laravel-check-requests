<?php

namespace Kakhura\CheckRequest\Http\Requests\Category;

use Kakhura\CheckRequest\Http\Requests\Request as BaseRequest;

class CreateRequest extends BaseRequest
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
        return array_merge([
            'image' => 'nullable|array|min:1',
            'parent_id' => 'nullable|integer|exists:categories,id,deleted_at,NULL',
            'published' => 'nullable|string',
        ], $this->translationsValidation([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));
    }
}
