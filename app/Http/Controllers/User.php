<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\User\UserService;

class User extends Controller
{
  public function index(Request $request, UserService $userService)
  {
    $users = $userService->getUser($request) ?? [];

    return view('content.users.index', compact('users'));
  }

  public function detail(Request $request, UserService $userService)
  {
    $id = $request->id;
    $user = $userService->getUserById($id);

    return view('content.users.detail', compact('user'));
  }

  public function updateProfile(Request $request, UserService $userService) {
    $doUpdate = $userService->updateUserProfile($request);

    if ($doUpdate) {
      return redirect()->back()->with('message', 'Cập nhật thành công!');
    }

    return redirect()->back()->withErrors('message', 'Cập nhật thất bại!');
  }

  public function updateBank(Request $request, UserService $userService) {
    $doUpdate = $userService->updateUserBank($request);

    if ($doUpdate) {
      return redirect()->back()->with('message', 'Cập nhật thành công!');
    }

    return redirect()->back()->withErrors('message', 'Cập nhật thất bại!');
  }

  public function payable(Request $request, UserService $userService)
  {
    $id = $request->id;
    $payable = $userService->getPayable($id);
    $user = $userService->getUserById($id);

    return view('content.users.payable', compact('payable', 'user'));
  }

  public function updateIdImage(Request $request, UserService $userService)
  {
    $request->validate([
      'file_front' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
      'file_back' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ], [
      'file_front.required' => 'Ảnh mặt trước là bắt buộc',
      'file_front.image' => 'Ảnh mặt trước phải là file ảnh',
      'file_front.mimes' => 'Ảnh mặt trước không hỗ trợ định dạng này',
      'file_front.max' => 'Ảnh mặt trước kích thước tối đa là 2MB',
      'file_back.required' => 'Ảnh mặt sau là bắt buộc',
      'file_back.image' => 'Ảnh mặt sau phải là file ảnh',
      'file_back.mimes' => 'Ảnh mặt sau không hỗ trợ định dạng này',
      'file_back.max' => 'Ảnh mặt sau kích thước tối đa là 2MB'
    ]);

    $doUpload = $userService->uploadIdImage($request);

    return redirect()->back()->with('message', 'Cập nhật thành công!');
  }
}
