<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Matche;
use App\Models\MatcheVideo;
use App\Models\TrackAds;
use App\Models\TrackAdsDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackAdsDetailsController extends Controller
{
    public function state() {
        $deviceCountOnline = Device::where('last_activity', '>=', now()->subMinutes(10))->count();
        $deviceCount = Device::count();
        $userCountOnline = User::where('last_activity', '>=', now()->subMinutes(10))->count();
        $userCount = User::count();

        return response()->json([
            'deviceCountOnline' => $deviceCountOnline,
            'deviceCount' => $deviceCount,
            'userCountOnline' => $userCountOnline,
            'userCount' => $userCount,
        ], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
           'app' => 'nullable|in:main,player,all' ,
            'filter'=> 'nullable|in:count_error,count_success' ,
        ]);
        // track query
        $trackAds = TrackAds::query();
        if($request->app && $request->app != "all"){
            $trackAds = $trackAds->where('app', $request->app);
        }
        if($request->filter){
            if($request->filter == "count_error"){
                $trackAds = $trackAds->where('count_ads_failed','>', 0);
            }else if ($request->filter == "count_success"){
                $trackAds = $trackAds->where('count_ads_success', '>', 0);
            }
        }
        if ($request->query('query')){
            $trackAds = $trackAds->where(function ($query) use ($request) {
                $searchQuery = $request->query('query');
                $query->where('user_id', 'LIKE', "%$searchQuery%")
                    ->orWhere('device_id', 'LIKE', "%$searchQuery%")
                    ->orWhere('device', 'LIKE', "%$searchQuery%")
                    ->orWhere('operating_system', 'LIKE', "%$searchQuery%")
                    ->orWhere('app_info', 'LIKE', "%$searchQuery%")
                    ->orWhere('ip_info', 'LIKE', "%$searchQuery%");
            });
        }
        // Paginate matche results
        $trackAds = $trackAds->with(['device','user'])->paginate(6);

        return response()->json($trackAds, 200);
    }




    public function show(TrackAds $track)
    {
        $ads = TrackAdsDetails::where('track_ad_id',$track->id)->paginate(6);
        return response()->json($ads, 200);
    }
    public function addBlocked($track)
    {
        $track = TrackAds::with(['device','user'])->find($track);
        if ($track != null) {
            if ($track->user_id){
                \App\Models\User::where('id',$track->user_id)->update([
                    'blocked' => DB::raw('IF(blocked = 1, 0, 1)')
                ]);
            }
            if ($track->device_id){
                \App\Models\Device::where('id',$track->device_id)->update([
                    'blocked' => DB::raw('IF(blocked = 1, 0, 1)')
                ]);
            }

            $data = [
                'status' => 200,
                'body' => $track->refresh(),
                'message' => 'successfully blocked',
            ];
        } else {
            $data = [
                'status' => 400,
                'message' => 'could not be blocked',
            ];
        }

        return response()->json($data, 200);
    }
}
