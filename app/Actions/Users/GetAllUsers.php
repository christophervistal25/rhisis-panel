<?php

namespace App\Actions\Users;

use App\Models\User;

final class GetAllUsers
{
    public function get()
    {
        $users = User::orderBy('created_at')->get();
        return $users;
    }
}
