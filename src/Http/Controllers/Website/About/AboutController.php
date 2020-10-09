<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\About;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\About\About;

class AboutController extends Controller
{
    public function about()
    {
        $about = About::with([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ])->first();
        return view('vendor.site-bases.website.about.main', compact('about'));
    }
}
