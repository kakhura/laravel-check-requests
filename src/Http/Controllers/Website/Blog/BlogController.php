<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\Blog;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\Blog\Blog;

class BlogController extends Controller
{
    public function blogs()
    {
        $blogs = Blog::where('published', true)
            ->orderBy('published_at', 'desc')
            ->with([
                'detail' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
            ])->paginate(config('kakhura.site-bases.pagination_mapper.blogs'));
        return view('vendor.site-bases.website.blogs.main', compact('blogs'));
    }

    public function blog(Blog $blog)
    {
        $blog->load([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ]);
        return view('vendor.site-bases.website.blogs.item', compact('blog'));
    }
}
