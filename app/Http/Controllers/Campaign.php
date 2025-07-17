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

  public function create() {
    $categories = Category::where('status', 1)->get();

    $trafficRules = 'Cashback, Direct Linking, Email Marketing, Incentived traffic / Loyalty, Pop up, Popunder & Tabunder, Search Engine Marketing, Social Messenger App, Coupon & Discount Codes, Display Banner, Extension & Software, Push Notification, Sub-network, Seeding community, Adult/Pornographic, Gambling, Brand bidding';

    $trafficRules = explode(', ', $trafficRules);

    return view('content.campaigns.create', compact('categories', 'trafficRules'));
  }

  public function store(Request $request) {
    $campaign = new CampaignModel();

    // dd($request->all());

    $campaign->name = $request->name;
    $campaign->code = sha1(time());
    $campaign->cp_type = $request->cp_type;
    $campaign->commission_type = $request->commission_type;
    $campaign->commission_text = $request->commission_text;
    $campaign->commission = $request->commission;
    $campaign->status = $request->status;
    $campaign->category_id = $request->category_id;
    $campaign->url = $request->url;
    $campaign->tracking_url = $request->tracking_url;
    $campaign->detail = $request->detail;
    $campaign->image = $request->image;
    $campaign->image_square = $request->image_square;

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

    return redirect()->route('campaigns')->with('success', 'Thêm mới thành công!');
  }

  public function edit(Request $request) {
    $id = $request->id;

    $campaignDetail = CampaignModel::find($id);

    $categories = Category::where('status', 1)->get();

    return view('content.campaigns.edit', compact('campaignDetail', 'categories'));
  }

  public function update(Request $request) {
    $id = $request->id;

    $campaign = CampaignModel::find($id);

    $campaign->name = $request->name;
    $campaign->cp_type = $request->cp_type;
    $campaign->commission_type = $request->commission_type;
    $campaign->commission_text = $request->commission_text;
    $campaign->status = $request->status;
    $campaign->category_id = $request->category_id;
    $campaign->url = $request->url;
    $campaign->tracking_url = $request->tracking_url;
    $campaign->detail = $request->detail;
    $campaign->commission = $request->commission;

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
