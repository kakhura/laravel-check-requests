<?php

namespace Kakhura\CheckRequest\Http\Controllers\Admin\DynamicPage;

use Illuminate\Http\Request;
use Kakhura\CheckRequest\Http\Controllers\Admin\Controller;
use Kakhura\CheckRequest\Http\Requests\Page\CreateRequest;
use Kakhura\CheckRequest\Http\Requests\Page\UpdateRequest;
use Kakhura\CheckRequest\Models\Page\Page;
use Kakhura\CheckRequest\Services\Page\PageService;

class PageController extends Controller
{
    public function pages()
    {
        $pages = Page::orderBy('ordering', 'asc')->paginate($limit = 100000);
        return view('vendor.site-bases.admin.pages.items', compact('pages', 'limit'));
    }

    public function createPage()
    {
        return view('vendor.site-bases.admin.pages.create');
    }

    public function storePage(CreateRequest $request, PageService $pageService)
    {
        $pageService->create($request->validated());
        return redirect('/admin/pages')->with(['success' => 'ინფორმაცია წარმატებით შეიცვალა']);
    }

    public function editPage(Page $page)
    {
        return view('vendor.site-bases.admin.pages.update', compact('page'));
    }

    public function updatePage(UpdateRequest $request, PageService $pageService, Page $page)
    {
        $update = $pageService->update($request->validated(), $page);

        if ($update) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით განახლდა');
            return redirect('/admin/pages');
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return redirect('/admin/pages');
    }

    public function deletePage(Request $request, PageService $pageService, Page $page)
    {
        if ($pageService->delete($page)) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით წაიშალა');
            return back();
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return back();
    }

    public function pageDeleteImg(Request $request, PageService $pageService)
    {
        $status = array('status' => 'error');
        if ($pageService->deleteImg($request)) {
            $status['status'] = 'success';
        }
        return json_encode($status);
    }
}
