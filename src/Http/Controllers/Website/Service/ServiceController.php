<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\Service;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\Service\Service;

class ServiceController extends Controller
{
    public function services()
    {
        $services = Service::where('published', true)
            ->orderBy('ordering', 'asc')
            ->with([
                'detail' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
            ])->paginate(config('kakhura.site-bases.pagination_mapper.services'));
        return view('vendor.site-bases.website.services.main', compact('services'));
    }

    public function service(Service $service)
    {
        $service->load([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ]);
        return view('vendor.site-bases.website.services.item', compact('service'));
    }
}
