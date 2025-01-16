<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Artisan;

class SettingsController extends Controller
{
    // return the settings by hiding the sensitive fields for the api
    public function index()
    {
        $settings = Setting::getAllSettings();

        return response()->json($settings, 200);
    }

    public function update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $setting = Setting::where('name', $type)->first();

            if($setting!=null){
                $setting->val = $request[$type];
                $setting->save();
            }
            else{
                $setting = new Setting;
                $setting->name = $type;
                $setting->val = $request[$type];
                $setting->save();
            }

            if($type === 'authorization' || $type === 'te_token' || $type === 'te_chat_id_payment' || $type === 'te_chat_id_admin' ||
                $type === 'send_notification_auto' || $type === 'payment_paypal_enable')
            {
                $this->overWriteEnvFile($type,$request[$type]);
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
    /**
    * overWrite the Env File values.
    * @param  String type
    * @param  String value
    * @return \Illuminate\Http\Response
    */
    public function overWriteEnvFile($type, $val)
    {
        if(env('DEMO_MODE') != 'On'){
            $path = base_path('.env');
            if (file_exists($path)) {
                $val = '"'.trim($val).'"';
                if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
                    file_put_contents($path, str_replace(
                        $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
                    ));
                }
                else{
                    file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
                }
            }
        }
    }
}    
