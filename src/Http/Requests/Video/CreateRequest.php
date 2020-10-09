<?php

namespace Kakhura\CheckRequest\Http\Requests\Video;

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
            'video_url' => 'required|string',
            'published' => 'nullable|string',
        ], $this->translationsValidation([
            'title' => 'required|string|max:255',
        ]));
    }
}
