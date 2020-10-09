<?php

namespace Kakhura\CheckRequest\Http\Controllers\Admin\Service;

use Illuminate\Http\Request;
use Kakhura\CheckRequest\Http\Controllers\Admin\Controller;
use Kakhura\CheckRequest\Http\Requests\Service\CreateRequest;
use Kakhura\CheckRequest\Http\Requests\Service\UpdateRequest;
use Kakhura\CheckRequest\Models\Service\Service;
use Kakhura\CheckRequest\Services\Service\ServiceService;

class ServiceController extends Controller
{
    public function services()
    {
        $services = Service::orderBy('ordering', 'asc')->paginate($limit = 100000);
        return view('vendor.site-bases.admin.services.items', compact('services', 'limit'));
    }

    public function createService()
    {
        return view('vendor.site-bases.admin.services.create');
    }

    public function storeService(CreateRequest $request, ServiceService $serviceService)
    {
        $serviceService->create($request->validated());
        return redirect('/admin/services')->with(['success' => 'ინფორმაცია წარმატებით შეიცვალა']);
    }

    public function editService(Service $service)
    {
        return view('vendor.site-bases.admin.services.update', compact('service'));
    }

    public function updateService(UpdateRequest $request, ServiceService $serviceService, Service $service)
    {
        $update = $serviceService->update($request->validated(), $service);

        if ($update) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით განახლდა');
            return redirect('/admin/services');
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return redirect('/admin/services');
    }

    public function deleteService(Request $request, ServiceService $serviceService, Service $service)
    {
        if ($serviceService->delete($service)) {
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', 'ინფორმაცია წარმატებით წაიშალა');
            return back();
        }
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', 'დაფიქსირდა შეცდომა');
        return back();
    }
}
