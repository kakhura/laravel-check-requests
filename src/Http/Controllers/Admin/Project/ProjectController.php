<?php

namespace Kakhura\CheckRequest\Http\Controllers\Admin\Project;

use Illuminate\Http\Request;
use Kakhura\CheckRequest\Http\Controllers\Admin\Controller;
use Kakhura\CheckRequest\Http\Requests\Project\CreateRequest;
use Kakhura\CheckRequest\Http\Requests\Project\UpdateRequest;
use Kakhura\CheckRequest\Models\Project\Project;
use Kakhura\CheckRequest\Services\Project\ProjectService;

class ProjectController extends Controller
{
    public function projects()
    {
        $projects = Project::orderBy('ordering', 'asc')->paginate($limit = 100000);
        return view('vendor.site-bases.admin.projects.items', compact('projects', 'limit'));
    }

    public function createProject()
    {
        return view('vendor.site-bases.admin.projects.create');
    }

    public function storeProject(CreateRequest $request, ProjectService $projectService)
    {
        $projectService->create($request->validated());
        return redirect('/admin/projects')->with(['success' => 'ინფორმაცია წარმატებით შეიცვალა']);
    }

    public function editProject(Project $project)
    {
        return view('vendor.site-bases.admin.projects.update', compact('project'));
    }

    public function updateProject(UpdateRequest $request, ProjectService $projectService, Project $project)
    {
        $update = $projectService->update($request->validated(), $project);

        if ($update) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით განახლდა');
            return redirect('/admin/projects');
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return redirect('/admin/projects');
    }

    public function deleteProject(Request $request, ProjectService $projectService, Project $project)
    {
        if ($projectService->delete($project)) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით წაიშალა');
            return back();
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return back();
    }

    public function projectDeleteImg(Request $request, ProjectService $projectService)
    {
        $status = array('status' => 'error');
        if ($projectService->deleteImg($request)) {
            $status['status'] = 'success';
        }
        return json_encode($status);
    }
}
