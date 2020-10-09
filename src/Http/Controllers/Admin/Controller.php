<?php

namespace Kakhura\CheckRequest\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Helpers\UploadHelper;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @return void
     */
    public function index()
    {
        return view('vendor.site-bases.admin.index');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function uploadFromRedactor(Request $request)
    {
        if ($request->hasFile('file')) {
            config(['kakhura.site-bases.images_thumbs.generate_thumb_for_images' => false]);
            $image = UploadHelper::uploadFile($request->file, '/upload/redactor/');
            $file = [
                'filelink' => asset(Arr::get($image, 'fileName')),
            ];
            return response()->json($file);
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    public function ordering(Request $request)
    {
        $className = $request->get('className');
        foreach (json_decode($request->ordering) as $value) {
            $object = $className::find(Arr::get($value, 0));
            if ($object) {
                $object->update([
                    'ordering' => Arr::get($value, 1, 0),
                ]);
            }
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function publish(Request $request): JsonResponse
    {
        $className = $request->get('className');
        $status = array('status' => 'error');
        $object = $className::findOrFail($request->id);
        $update = $object->update([
            'published' => $request->published,
        ]);
        if ($update) {
            $status['status'] = 'success';
        }
        return response()->json($status);
    }
}
