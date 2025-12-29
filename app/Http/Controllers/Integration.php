<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SetupRedirect;
use App\Models\Campaign;
use Carbon\Carbon;

class Integration extends Controller
{
  public function index() {
    $data = SetupRedirect::join('campaigns', 'campaigns.code', '=', 'setup_redirects.campaign_code')
    ->orderBy('setup_redirects.id', 'desc')
    ->get();
    $campaigns = Campaign::leftJoin('setup_redirects', 'campaigns.code', '=', 'setup_redirects.campaign_code')
    ->whereNull('deactived_at')
    ->whereNull('platform_url')
    ->select('code', 'name', 'platform_url')
    ->get();

    return view('content.integration.campaign', compact('data', 'campaigns'));
  }

  public function create(Request $request) {
    $platform_id = null;
    try {
      $path = parse_url($request->platform_url, PHP_URL_PATH);
      $query = parse_url($request->platform_url, PHP_URL_QUERY);
      switch ($request->platform) {
        case 'involve':
          $platform_id = substr($path, 1);
          break;

        case 'goodaff':
          $pathArray = explode('/', $path);
          $platform_id = $pathArray[1];
          break;

        case 'travelpayouts':
          parse_str($query, $params);
          $platform_id = $params['campaign_id'];
          break;

        default:
          # code...
          break;
      }

      if (is_null($platform_id)) {
        return response()->json([], 400, []);
      }

      SetupRedirect::insert([
        'campaign_code' => $request->campaign_code,
        'platform' => $request->platform,
        'platform_id' => $platform_id,
        'platform_url' => $request->platform_url,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
      ]);

      return response()->json([], 200, []);
    } catch (\Throwable $th) {
      return response()->json([], 400, []);
    }
  }
}
