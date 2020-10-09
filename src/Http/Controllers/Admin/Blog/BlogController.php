<?php

namespace Kakhura\CheckRequest\Http\Controllers\Admin\Blog;

use Illuminate\Http\Request;
use Kakhura\CheckRequest\Http\Controllers\Admin\Controller;
use Kakhura\CheckRequest\Http\Requests\Blog\CreateRequest;
use Kakhura\CheckRequest\Http\Requests\Blog\UpdateRequest;
use Kakhura\CheckRequest\Models\Blog\Blog;
use Kakhura\CheckRequest\Models\Photo\Photo;
use Kakhura\CheckRequest\Services\Blog\BlogService;

class BlogController extends Controller
{
    public function blogs()
    {
        $blogs = Blog::orderBy('ordering', 'asc')->paginate(15);
        return view('vendor.site-bases.admin.blogs.items', compact('blogs'));
    }

    public function createBlog()
    {
        $photos = Photo::orderBy('ordering', 'asc')->get();
        return view('vendor.site-bases.admin.blogs.create', compact('photos'));
    }

    public function storeBlog(CreateRequest $request, BlogService $blogService)
    {
        $blogService->create($request->validated());
        return redirect('/admin/blogs')->with(['success' => 'ინფორმაცია წარმატებით შეიცვალა']);
    }

    public function editBlog(Blog $blog)
    {
        $photos = Photo::orderBy('ordering', 'asc')->get();
        return view('vendor.site-bases.admin.blogs.update', compact('blog', 'photos'));
    }

    public function updateBlog(UpdateRequest $request, BlogService $blogService, Blog $blog)
    {
        $update = $blogService->update($request->validated(), $blog);

        if ($update) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით განახლდა');
            return redirect('/admin/blogs');
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return redirect('/admin/blogs');
    }

    public function deleteBlog(Request $request, BlogService $blogService, Blog $blog)
    {
        if ($blogService->delete($blog)) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით წაიშალა');
            return back();
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return back();
    }

    public function blogDeleteImg(Request $request, BlogService $blogService)
    {
        $status = array('status' => 'error');
        if ($blogService->deleteImg($request)) {
            $status['status'] = 'success';
        }
        return json_encode($status);
    }
}
