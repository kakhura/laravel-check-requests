<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\Project;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\Project\Project;

class ProjectController extends Controller
{
    public function projects()
    {
        $projects = Project::where('published', true)
            ->orderBy('ordering', 'asc')
            ->with([
                'detail' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
            ])->paginate(config('kakhura.site-bases.pagination_mapper.projects'));
        return view('vendor.site-bases.website.projects.main', compact('projects'));
    }

    public function project(Project $project)
    {
        $project->load([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ]);
        return view('vendor.site-bases.website.projects.item', compact('project'));
    }
}
