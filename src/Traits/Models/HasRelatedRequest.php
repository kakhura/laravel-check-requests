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
        return RequestIdentifier::create([
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'model_type' => self::class,
            'model_id' => $this->id,
            'request_id' => $requestId,
        ]);
    }
}
