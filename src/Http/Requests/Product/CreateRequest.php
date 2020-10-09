<?php

namespace Kakhura\CheckRequest\Http\Requests\Product;

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
            'image' => 'required|array|min:1',
            'category_id' => 'nullable|integer|exists:categories,id,deleted_at,NULL',
            'price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'published' => 'nullable|string',
            'video' => 'nullable|string',
            'video_image' => 'array|min:1',
            'images' => 'array|min:1',
        ], $this->translationsValidation([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]));
    }
}
