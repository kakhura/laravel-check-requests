<?php

namespace Kakhura\LaravelCheckRequest\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Kakhura\LaravelCheckRequest\Models\Request\RequestIdentifier;

trait HasRelatedRequest
{
    public function reletadRequest()
    {
        /** @var Model $this */
        return $this->morphOne(RequestIdentifier::class, 'model');
    }

    /**
     * @param string $requestId
     * @return RequestIdentifier
     */
    public function createRequestIdentifier(string $requestId): RequestIdentifier
    {
        return $this->reletadRequest()->create([
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'request_id' => $requestId,
        ]);
    }
}
