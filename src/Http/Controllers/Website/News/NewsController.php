<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\News;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\News\News;

class NewsController extends Controller
{
    public function news()
    {
        $news = News::where('published', true)
            ->orderBy('published_at', 'desc')
            ->with([
                'detail' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
            ])->paginate(config('kakhura.site-bases.pagination_mapper.news'));
        return view('vendor.site-bases.website.news.main', compact('news'));
    }

    public function news_in(News $news)
    {
        $news->load([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ]);
        return view('vendor.site-bases.website.news.item', compact('news'));
    }
}
