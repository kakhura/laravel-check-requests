<?php

namespace Kakhura\CheckRequest\Http\Controllers\Admin\Photo;

use Illuminate\Http\Request;
use Kakhura\CheckRequest\Http\Controllers\Admin\Controller;
use Kakhura\CheckRequest\Http\Requests\Photo\CreateRequest;
use Kakhura\CheckRequest\Http\Requests\Photo\UpdateRequest;
use Kakhura\CheckRequest\Models\Photo\Photo;
use Kakhura\CheckRequest\Services\Photo\PhotoService;

class PhotoController extends Controller
{
    public function photos()
    {
        $photos = Photo::orderBy('ordering', 'asc')->paginate(15);
        return view('vendor.site-bases.admin.photos.items', compact('photos'));
    }

    public function createPhoto()
    {
        return view('vendor.site-bases.admin.photos.create');
    }

    public function storePhoto(CreateRequest $request, PhotoService $photoService)
    {
        $photoService->create($request->validated());
        return redirect('/admin/photos')->with(['success' => 'ინფორმაცია წარმატებით შეიცვალა']);
    }

    public function editPhoto(Photo $photo)
    {
        return view('vendor.site-bases.admin.photos.update', compact('photo'));
    }

    public function updatePhoto(UpdateRequest $request, PhotoService $photoService, Photo $photo)
    {
        $update = $photoService->update($request->validated(), $photo);

        if ($update) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით განახლდა');
            return redirect('/admin/photos');
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return redirect('/admin/photos');
    }

    public function deletePhoto(Request $request, PhotoService $photoService, Photo $photo)
    {
        if ($photoService->delete($photo)) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით წაიშალა');
            return back();
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return back();
    }

    public function photoDeleteImg(Request $request, PhotoService $photoService)
    {
        $status = array('status' => 'error');
        if ($photoService->deleteImg($request)) {
            $status['status'] = 'success';
        }
        return json_encode($status);
    }
}
