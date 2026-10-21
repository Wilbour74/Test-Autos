<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser(array $data): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->birthdate = $data['birthdate'] ?? null;
        $user->save();

        return $user;
    }
}
