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
    // dd($request->all());

    $campaign = new CampaignModel();

    // dd($request->all());

    $campaign->name = $request->name;
    $campaign->code = sha1(time());
    $campaign->image = $request->image;
    $campaign->image_square = $request->image_square;
    $campaign->category_id = $request->category_id;
    $campaign->tracking_url = $request->tracking_url;
    $campaign->commission = $request->commission;
    $campaign->commission_type = $request->commission_type;
    $campaign->commission_text = $request->commission_text;
    $campaign->status = $request->status;
    $campaign->url = $request->url;
    $campaign->detail = $request->detail;
    $campaign->allowed_rule = json_encode($request->allowed_rule);
    $campaign->not_allowed_rule = json_encode($request->not_allowed_rule);
    $campaign->display_geo = $request->display_geo;
    $campaign->cp_type = $request->cp_type;
    $campaign->device = json_encode($request->device);
    $campaign->os = $request->os;
    $campaign->conversion_flow = $request->conversion_flow;
    $campaign->commission_structure = $request->commission_structure;
    $campaign->terms = $request->terms;

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

    $allowedRule = json_decode($campaignDetail->allowed_rule, TRUE);

    $notAllowedRule = json_decode($campaignDetail->not_allowed_rule, TRUE);

    $devices = json_decode($campaignDetail->device, TRUE);

    $categories = Category::where('status', 1)->get();

    $trafficRules = 'Cashback, Direct Linking, Email Marketing, Incentived traffic / Loyalty, Pop up, Popunder & Tabunder, Search Engine Marketing, Social Messenger App, Coupon & Discount Codes, Display Banner, Extension & Software, Push Notification, Sub-network, Seeding community, Adult/Pornographic, Gambling, Brand bidding';

    $trafficRules = explode(', ', $trafficRules);

    return view('content.campaigns.edit', compact('campaignDetail', 'categories', 'trafficRules', 'allowedRule', 'notAllowedRule', 'devices'));
  }

  public function update(Request $request) {
    $id = $request->id;

    $campaign = CampaignModel::find($id);

    $campaign->name = $request->name;
    $campaign->category_id = $request->category_id;
    $campaign->tracking_url = $request->tracking_url;
    $campaign->commission = $request->commission;
    $campaign->commission_type = $request->commission_type;
    $campaign->commission_text = $request->commission_text;
    $campaign->cp_type = $request->cp_type;
    $campaign->status = $request->status;
    $campaign->url = $request->url;
    $campaign->detail = $request->detail;
    $campaign->allowed_rule = json_encode($request->allowed_rule);
    $campaign->not_allowed_rule = json_encode($request->not_allowed_rule);
    $campaign->display_geo = $request->display_geo;
    $campaign->cp_type = $request->cp_type;
    $campaign->device = json_encode($request->device);
    $campaign->os = $request->os;
    $campaign->conversion_flow = $request->conversion_flow;
    $campaign->commission_structure = $request->commission_structure;
    $campaign->terms = $request->terms;

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
