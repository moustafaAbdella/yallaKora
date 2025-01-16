<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlayerSetting;
use Illuminate\Support\Facades\Artisan;

class PlayerSettingController extends Controller
{
    // return the settings by hiding the sensitive fields for the api
    public function index()
    {
        $settings = PlayerSetting::getAllSettings();

        return response()->json($settings, 200);
    }

    public function update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $setting = PlayerSetting::where('name', $type)->first();

            if($setting!=null){
                $setting->val = $request[$type];
                $setting->save();
            }
            else{
                $setting = new PlayerSetting;
                $setting->name = $type;
                $setting->val = $request[$type];
                $setting->save();
            }
        }

        Artisan::call('cache:clear');
        $data = [
            'status' => 200,
            'message' => 'تم تحديث اعدادات  بنجاح',
            'body' => $setting
        ];
        return response()->json($data, $data['status']);
    }
}
