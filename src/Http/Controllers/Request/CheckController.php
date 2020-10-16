<?php

namespace Kakhura\CheckRequest\Http\Controllers\Request;

use Illuminate\Support\Str;
use Kakhura\CheckRequest\Helpers\Helper;
use Kakhura\CheckRequest\Http\Controllers\Admin\Controller;
use Kakhura\CheckRequest\Models\Request\RequestIdentifier;
use Kakhura\CheckRequest\Services\RequestIdentifier\RequestIdentifierService;

class CheckController extends Controller
{
    public function check(string $requestId, RequestIdentifierService $requestIdentifierService)
    {
        /** @var RequestIdentifier $request */
        $request = $requestIdentifierService->getRequest($requestId, config('kakhura.check-requests.use_auth_user_check') ? auth()->user() : null);
        if (is_null($request)) {
            return Helper::response(false, [], 404, ['messasge' => trans('messages.request_identifier_not_found')]);
        }
        $request->transform(function (RequestIdentifier $request) {
            return [
                'model' => Str::snake(Str::afterLast($request->model_type, "\\")),
                'id' => $request->model->uuid ?: $request->model->id,
            ];
        });
        return Helper::response(true, ['request' => $request]);
    }
}
