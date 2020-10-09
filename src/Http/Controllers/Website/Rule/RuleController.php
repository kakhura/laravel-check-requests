<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\Rule;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\Rule\Rule;

class RuleController extends Controller
{
    public function rules()
    {
        $rules = Rule::with([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ])->first();
        return view('vendor.site-bases.website.rules.main', compact('rules'));
    }
}
