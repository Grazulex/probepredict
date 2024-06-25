<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

class CreateUserAction
{
    public function handle(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $user->assignRole('user');

        return $user;
    }
}
