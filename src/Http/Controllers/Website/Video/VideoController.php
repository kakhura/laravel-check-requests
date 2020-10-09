<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\Video;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\Video\Video;

class VideoController extends Controller
{
    public function videos()
    {
        $videos = Video::where('published', true)
            ->orderBy('ordering', 'asc')
            ->with([
                'detail' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
            ])->paginate(config('kakhura.site-bases.pagination_mapper.videos'));
        return view('vendor.site-bases.website.videos.main', compact('videos'));
    }

    public function video(Video $video)
    {
        $video->load([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ]);
        return view('vendor.site-bases.website.videos.item', compact('video'));
    }
}
