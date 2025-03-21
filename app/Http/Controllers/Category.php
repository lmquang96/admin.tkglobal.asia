<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category as CategoryModel;

class Category extends Controller
{
  public function index(){
    $categories = CategoryModel::where('status', 1)->get();

    return view('content.categories.index', compact('categories'));
  }

  public function create(){
    return view('content.categories.create');
  }

  public function store(Request $request){
    $request->validate([
      'name' => 'required',
      'status' => 'required',
    ]);

    try {
      $category = new CategoryModel();
      $category->code = sha1(time());
      $category->name = $request->name;
      $category->status = $request->status;
      $category->save();
    } catch (\Exception $e) {
      // TODO: log error
      Log::error("--------------");
      Log::error($e->getMessage());
      Log::error("--------------");

      return redirect()->route('campaigns')->with('error', 'Xảy ra lỗi rồi :((');
    }

    return redirect()->route('categories')->with('success', 'Thêm mới thành công!');
  }
}
