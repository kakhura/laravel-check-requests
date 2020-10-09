<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\Page;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\Page\Page;

class PageController extends Controller
{
    public function pages()
    {
        $pages = Page::where('published', true)
            ->orderBy('ordering', 'asc')
            ->with([
                'detail' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
            ])->paginate(config('kakhura.site-bases.pagination_mapper.pages'));
        return view('vendor.site-bases.website.pages.main', compact('pages'));
    }

    public function page(Page $page)
    {
        $page->load([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ]);
        return view('vendor.site-bases.website.pages.item', compact('page'));
    }
}
