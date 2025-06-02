<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\User\UserService;

class User extends Controller
{
    public function index(UserService $userService) {
        $users = $userService->getUser();

        return view('content.users.index', compact('users'));
    }

    public function detail(Request $request, UserService $userService) {
        $id = $request->id;
        $user = $userService->getUserById($id);

        return view('content.users.detail', compact('user'));
    }
}
