<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Users\GetAllUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(GetAllUsers $action)
    {
        $users = $action->get();
        return inertia('admin/users/Index', [
            'users' => $users,
        ]);
    }
}
