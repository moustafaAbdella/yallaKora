<?php

namespace App\Http\Controllers\Api;

use App\Helpers\IpLocationHelper;
use App\Helpers\UserSystemInfoHelper;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Device;
use App\Models\TrackAds;
use App\Models\TrackAdsDetails;
use Illuminate\Http\Request;

class TrackAdsController extends BaseController
{
    public function myIp()
    {
        $location = new IpLocationHelper();
        $location =  $location->getLocation(UserSystemInfoHelper::get_ip());

        return $this->sendResponse($location, 'done');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $device = Device::findByUuid($request->header('idDevice'));
//        $trackAds = TrackAds::where('app', $request->app)->where(function ($query) use ($device) {
//            if(auth()->guard('api')->check())
//                $query->where('user_id', auth()->guard('api')->id())->whereNull('device_id');
//            else
//                $query->where('device_id', $device->id)->whereNull('user_id');
//        })->first();

        $location = new IpLocationHelper();
        $location = $location->getLocation(UserSystemInfoHelper::get_ip());

        $trackAds = TrackAds::updateOrCreate(
            [
                'app' => $request->app,
                'user_id' => auth()->guard('api')->check() ? auth()->guard('api')->id() : null,
                'device_id' => auth()->guard('api')->check() ? null : (!$device ? null : $device->id),
            ],
            [
                'app_info' => $request->app_info,
                'ip_info'  => $location,
                'user_agent' => request()->header('User-Agent'),
                'browser' => UserSystemInfoHelper::get_browsers(),
                'device' => UserSystemInfoHelper::get_device(),
                'operating_system' => UserSystemInfoHelper::get_os(),
            ]
        );

        // استخدام updateOrCreate لتحديث أو إنشاء سجل TrackAdsDetails
        $trackAdsDetails = TrackAdsDetails::updateOrCreate(
            [
                'track_ad_id' => $trackAds->id,
                'provider' => $request->provider,
                'ad_type' => $request->ad_type,
            ],
            [
                'app_info' => $request->app_info,
                'ip_info'  => $location,
                'user_agent' => request()->header('User-Agent'),
                'browser' => UserSystemInfoHelper::get_browsers(),
                'device' => UserSystemInfoHelper::get_device(),
                'operating_system' => UserSystemInfoHelper::get_os(),
            ]
        );
//        if (!$trackAds) {
//            $trackAds = new TrackAds();
//            if(auth()->guard('api')->check()){
//                $trackAds->user_id = auth()->guard('api')->id();
//            }else if ($device) {
//                $trackAds->device_id = $device->id;
//            }
//            $trackAds->app      = $request->app;
//            $trackAds->app_info = $request->app_info;
//            $trackAds->ip_info  = $location;
//            $trackAds->user_agent       = request()->header('User-Agent');
//            $trackAds->browser          = UserSystemInfoHelper::get_browsers();
//            $trackAds->device           = UserSystemInfoHelper::get_device();
//            $trackAds->operating_system = UserSystemInfoHelper::get_os();
//            $trackAds->save();
//
//            $trackAdsDetails = new TrackAdsDetails();
//            $trackAdsDetails->track_ad_id = $trackAds->id;
//            $trackAdsDetails->provider = $request->provider;
//            $trackAdsDetails->ad_type  = $request->ad_type;
//            $trackAdsDetails->app_info = $request->app_info;
//            $trackAdsDetails->ip_info  = $location;
//            $trackAdsDetails->user_agent       = request()->header('User-Agent');
//            $trackAdsDetails->browser          = UserSystemInfoHelper::get_browsers();
//            $trackAdsDetails->device           = UserSystemInfoHelper::get_device();
//            $trackAdsDetails->operating_system = UserSystemInfoHelper::get_os();
//            $trackAdsDetails->save();
//        } else {
//            $trackAds->app_info = $request->app_info;
//            $trackAds->ip_info  = $location;
//            $trackAds->user_agent       = request()->header('User-Agent');
//            $trackAds->browser          = UserSystemInfoHelper::get_browsers();
//            $trackAds->device           = UserSystemInfoHelper::get_device();
//            $trackAds->operating_system = UserSystemInfoHelper::get_os();
//            $trackAds->save();
//
//            $trackAdsDetails = new TrackAdsDetails();
//            $trackAdsDetails->track_ad_id = $trackAds->id;
//            $trackAdsDetails->provider = $request->provider;
//            $trackAdsDetails->ad_type  = $request->ad_type;
//            $trackAdsDetails->app_info = $request->app_info;
//            $trackAdsDetails->ip_info  = $location;
//            $trackAdsDetails->user_agent       = request()->header('User-Agent');
//            $trackAdsDetails->browser          = UserSystemInfoHelper::get_browsers();
//            $trackAdsDetails->device           = UserSystemInfoHelper::get_device();
//            $trackAdsDetails->operating_system = UserSystemInfoHelper::get_os();
//            $trackAdsDetails->save();
//        }
        return $this->sendResponse($trackAdsDetails, 'done');
    }

    public function update(Request $request)
    {
        $track = TrackAdsDetails::with('track_ad')->findOrFail($request->id);

        if ($request->ad_shown ==1) {
            $track->track_ad->count_ads_success = $track->track_ad->count_ads_success + 1;
            $track->track_ad->last_success_ads_at = now();
            $track->track_ad->save();
            $track->shown_at = $track->track_ad->last_success_ads_at;
        } else {
            $track->track_ad->count_ads_failed = $track->track_ad->count_ads_failed + 1;
            $track->track_ad->last_failed_ads_at = now();
            $track->track_ad->save();
        }
        $track->ad_info = [
            'adId' => $request->adId,
            'error' => $request->error,
        ];
        $track->ad_shown = $request->ad_shown;
        $track->save();

        return $this->sendResponse($track, 'done');
    }


}
