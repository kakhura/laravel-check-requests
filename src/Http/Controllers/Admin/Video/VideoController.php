<?php

namespace Kakhura\CheckRequest\Http\Controllers\Admin\Video;

use Illuminate\Http\Request;
use Kakhura\CheckRequest\Http\Controllers\Admin\Controller;
use Kakhura\CheckRequest\Http\Requests\Video\CreateRequest;
use Kakhura\CheckRequest\Http\Requests\Video\UpdateRequest;
use Kakhura\CheckRequest\Models\Video\Video;
use Kakhura\CheckRequest\Services\Video\VideoService;

class VideoController extends Controller
{
    public function videos()
    {
        $videos = Video::orderBy('ordering', 'asc')->paginate($limit = 100000);
        return view('vendor.site-bases.admin.videos.items', compact('videos', 'limit'));
    }

    public function createVideo()
    {
        return view('vendor.site-bases.admin.videos.create');
    }

    public function storeVideo(CreateRequest $request, VideoService $videoService)
    {
        $videoService->create($request->validated());
        return redirect('/admin/videos')->with(['success' => 'ინფორმაცია წარმატებით შეიცვალა']);
    }

    public function editVideo(Video $video)
    {
        return view('vendor.site-bases.admin.videos.update', compact('video'));
    }

    public function updateVideo(UpdateRequest $request, VideoService $videoService, Video $video)
    {
        $update = $videoService->update($request->validated(), $video);

        if ($update) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით განახლდა');
            return redirect('/admin/videos');
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return redirect('/admin/videos');
    }

    public function deleteVideo(Request $request, VideoService $videoService, Video $video)
    {
        if ($videoService->delete($video)) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით წაიშალა');
            return back();
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return back();
    }
}
