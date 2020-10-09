<?php

namespace Kakhura\CheckRequest\Services\Admin;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Kakhura\CheckRequest\Services\Service;

class AdminService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        User::create([
            'name' => Arr::get($data, 'name'),
            'email' => Arr::get($data, 'email'),
            'password' => Hash::make(Arr::get($data, 'password')),
        ]);
    }

    /**
     * @param array $data
     * @param User $admin
     * @return bool
     */
    public function update(array $data, User $admin): bool
    {
        $data = array_merge([
            'name' => Arr::get($data, 'name'),
            'email' => Arr::get($data, 'email'),
        ], Arr::get($data, 'password', false) ? ['password' => Hash::make(Arr::get($data, 'password'))] : []);
        $update = $admin->update($data);
        return $update;
    }
}
