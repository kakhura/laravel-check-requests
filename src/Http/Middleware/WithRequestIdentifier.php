<?php

namespace Kakhura\CheckRequest\Http\Middleware;

use Kakhura\CheckRequest\Exceptions\RequestIdentifierFoundedException;
use Kakhura\CheckRequest\Exceptions\RequestIdentifierRequiredException;
use Kakhura\CheckRequest\Models\Request\RequestIdentifier;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WithRequestIdentifier
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure  $next
     * @param string|null  $guard
     * @return mixed
     *
     * @throws RequestIdentifierRequiredException
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array(Str::lower($request->method()), ['post', 'put'])) {
            if (!$request->has('request_id')) {
                throw new RequestIdentifierRequiredException(trans('message.request_identifier_required'));
            }
            $this->checkIfRequestExists($request);
        }
        return $next($request);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     *
     * @throws RequestIdentifierFoundedException
     */
    protected function checkIfRequestExists(Request $request)
    {
        $exists = RequestIdentifier::where('request_id', $request->get('request_id'))->exists();
        if ($exists) {
            throw new RequestIdentifierFoundedException(trans('message.request_identifier_founded'));
        }
    }
}
