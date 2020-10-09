<?php

namespace Kakhura\CheckRequest\Http\Controllers\Admin\Page;

use Kakhura\CheckRequest\Http\Controllers\Admin\Controller;
use Kakhura\CheckRequest\Models\About\About;
use Kakhura\CheckRequest\Http\Requests\About\Request;
use Kakhura\CheckRequest\Services\About\AboutService;

class AboutController extends Controller
{
    public function about()
    {
        $about = About::first();
        return view('vendor.site-bases.admin.about.edit', compact('about'));
    }

    public function storeAbout(Request $request, AboutService $aboutService)
    {
        $about = About::first();
        if (!is_null($about)) {
            $aboutService->update($request->validated(), $about);
        } else {
            $aboutService->create($request->validated());
        }
        return back()->with(['success' => 'ინფორმაცია წარმატებით შეიცვალა']);
    }
}
