<?php

namespace Database\Seeders;

use App\Models\PlayerSetting;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['name'=>'app_name','val'=>'1', 'type' => 'string'],
            ['name'=>'app_privacy','val'=>'1', 'type' => 'string'],
            ['name'=>'app_terms','val'=>'1', 'type' => 'string'],
            ['name'=>'play_video','val'=>'1', 'type' => 'bool'],
            ['name'=>'send_notification_auto','val'=>'1', 'type' => 'bool'],
            ['name'=>'exp_subscriptions','val'=>'1', 'type' => 'string'],
            ['name'=>'twitter','val'=>'1', 'type' => 'string'],
            ['name'=>'fb','val'=>'1', 'type' => 'string'],
            ['name'=>'telegram','val'=>'1', 'type' => 'string'],
            ['name'=>'email','val'=>'1', 'type' => 'string'],
            ['name'=>'authorization','val'=>'', 'type' => 'string'],
            ['name'=>'ad_enable_banner','val'=>'1', 'type' => 'bool'],
            ['name'=>'ad_enable_int','val'=>'1', 'type' => 'bool'],
            ['name'=>'ad_enable_banner_ios','val'=>'1', 'type' => 'bool'],
            ['name'=>'ad_enable_int_ios','val'=>'1', 'type' => 'bool'],
            ['name'=>'ad_app_id','val'=>'', 'type' => 'string'],
            ['name'=>'ad_unit_id_banner','val'=>'', 'type' => 'string'],
            ['name'=>'ad_unit_id_interstitial','val'=>'1', 'type' => 'string'],
            ['name'=>'ad_ios_app_id','val'=>'', 'type' => 'string'],
            ['name'=>'ad_ios_unit_id_banner','val'=>'', 'type' => 'string'],
            ['name'=>'ad_ios_unit_id_interstitial','val'=>'', 'type' => 'string'],
            ['name'=>'app_url_android','val'=>'', 'type' => 'string'],
            ['name'=>'app_url_ios','val'=>'', 'type' => 'string'],
        ];

        PlayerSetting::insert($settings); // Eloquent
       // \DB::table('users')->insert($createMultipleUsers);
    }
}
