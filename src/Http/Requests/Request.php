<?php

namespace Kakhura\CheckRequest\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Request extends FormRequest
{
    protected function translationsValidation(array $fields)
    {
        $result = [];
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            foreach ($fields as $field => $rules) {
                $result[sprintf('%s_%s', $field, $localeCode)] = $rules;
            }
        }
        return $result;
    }
}
