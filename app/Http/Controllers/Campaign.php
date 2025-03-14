<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign as CampaignModel;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class Campaign extends Controller
{
  const ITEM_PER_PAGE = 20;

  public function index() {
    $campaigns = CampaignModel::query()
    ->paginate(self::ITEM_PER_PAGE);

    return view('content.campaigns.index', compact('campaigns'));
  }

  public function edit(Request $request) {
    $id = $request->id;

    $campaignDetail = CampaignModel::find($id);

    $categories = Category::where('status', 1)->get();

    return view('content.campaigns.edit', compact('campaignDetail', 'categories'));
  }

  public function store(Request $request) {
    $id = $request->id;

    $campaign = CampaignModel::find($id);

    $campaign->name = $request->name;
    $campaign->cp_type = $request->cp_type;
    $campaign->commission_type = $request->commission_type;
    $campaign->commission_text = $request->commission_text;
    $campaign->status = $request->status;
    $campaign->category->id = $request->category_id;
    $campaign->url = $request->url;
    $campaign->tracking_url = $request->tracking_url;
    $campaign->detail = $request->detail;

    try {
      $campaign->save();
      $campaign->category->save();
    } catch (\Exception $e) {
      // TODO: log error
      Log::error("--------------");
      Log::error($e->getMessage());
      Log::error("--------------");

      return redirect()->route('campaigns')->with('error', 'Xảy ra lỗi rồi :((');
    }

    return redirect()->route('campaigns')->with('success', 'Cập nhật thành công!');
  }
}
