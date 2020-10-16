<?php

namespace Kakhura\CheckRequest\Services\RequestIdentifier;

use App\Models\User;
use Kakhura\CheckRequest\Models\Request\RequestIdentifier;
use Kakhura\CheckRequest\Services\Service;

class RequestIdentifierService extends Service
{
    /**
     * This method returns request identifier or null.
     *
     * @param string $requestId
     * @param User|null $user
     * @return RequestIdentifier|null
     */
    public function getRequest(string $requestId, User $user = null): ?RequestIdentifier
    {
        $query = RequestIdentifier::query();
        if (!is_null($user)) {
            $query = $query->where('user_id', $user->id);
        }
        return $query->where('request_id', $requestId)->first();
    }
}
