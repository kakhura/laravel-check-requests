<?php

namespace Kakhura\CheckRequest\Http\Requests\Contact;

use Kakhura\CheckRequest\Http\Requests\Request as BaseRequest;

class Request extends BaseRequest
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
            'phone' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'long' => 'nullable|string|max:255',
            'lat' => 'nullable|string|max:255',
        ], $this->generateSocialsRules(), $this->translationsValidation([
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));
    }

    /**
     * @return array
     */
    protected function generateSocialsRules(): array
    {
        $result = [];
        if (!is_null(config('kakhura.site-bases.contact_socials')) && is_array(config('kakhura.site-bases.contact_socials'))) {
            foreach (config('kakhura.site-bases.contact_socials') as $social) {
                $result[$social] = 'nullable|string|max:255';
            }
        }
        return $result;
    }
}
