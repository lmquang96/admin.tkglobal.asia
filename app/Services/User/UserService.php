<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserService
{

  public function getUser()
  {
    try {
      return User::query()
      ->join('profiles', 'users.id', '=', 'profiles.user_id')
      ->whereNotNull('profiles.affiliate_id')
      ->whereNotNull('users.email_verified_at')
      ->get();
    } catch (\Throwable $th) {
      Log::error('Lỗi xảy ra khi select user: ' . $th->getMessage());
    }
  }

  public function getUserById($id)
  {
    try {
      return User::query()
      ->join('profiles', 'users.id', '=', 'profiles.user_id')
      ->whereNotNull('profiles.affiliate_id')
      ->whereNotNull('users.email_verified_at')
      ->where('users.id', '=', $id)
      ->first();
    } catch (\Throwable $th) {
      Log::error('Lỗi xảy ra khi select user by id: ' . $th->getMessage());
    }
  }
}
