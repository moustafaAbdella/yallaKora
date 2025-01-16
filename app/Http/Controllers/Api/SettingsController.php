<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UserSystemInfoHelper;
use App\Http\Controllers\Controller;
use App\Models\PlayerSetting;
use App\Models\Server;
use App\Models\Setting;
use Database\Seeders\SettingsPlayersSeeder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\SettingResource;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingsController extends BaseController
{

    public function virefyUrl(Request $request)
    {
        // dd($data);
        $settings = Cache::remember('settings_mobile', 3500, function () {
           // $data = Setting::whereIn('name', ['virefy_url', 'virefy_url_status'])->get();
                       $data = Setting::getAllSettings();
 
            return new SettingResource($data);
        });

        return $this->sendResponse($settings,null);
    }



    public function data(Request $request)
    {
        $settings = Cache::remember('settings_mobile', 3500, function () {
            $data = Setting::getAllSettings();
            return new SettingResource($data);
        });

        return $this->sendResponse($settings,null);
    }

    public function config(Request $request)
    {
        $settings = Cache::remember('player_settings_mobile', 3500, function () {
            $data = PlayerSetting::getAllSettings();
            return new SettingResource($data);
        });

        return $this->sendResponse($settings,null);
    }

    public function servers(Request $request)
    {
        $servers = Cache::remember('mobile_servers', 3500, function () {
            return Server::all();
        });

        return $this->sendResponse($servers,null);
    }

    public function device(Request $request)
    {
        $device =  UserSystemInfoHelper::createDevice();
        return $this->sendResponse($device, null);
    }
}
