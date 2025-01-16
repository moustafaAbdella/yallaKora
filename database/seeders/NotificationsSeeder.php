<?php

namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use App\Models\Notifications;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notifications = [
            ['key'=>'start_match','value'=>' بداية المبارة team_a ضد team_b '],
            ['key'=>'done_match','value'=>' انتهت المبارة team_a ضد team_b '],
            ['key'=>'shoot_a_goal','value'=>' تسديد هدف لصالح team '],
        ];
        
        Notifications::insert($notifications); 
    }
}
