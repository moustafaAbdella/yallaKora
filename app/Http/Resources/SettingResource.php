<?php

namespace App\Http\Resources;

use App\Helpers\UserSystemInfoHelper;
use App\Models\Device;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SettingResource extends ResourceCollection
{
    //php artisan make:resource MatcheResource
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $a = [];
        foreach ($this->collection as $key => $data) {
            if ($data->name != 'send_notification_auto' && $data->name != 'exp_subscriptions'
                && $data->name != 'authorization' && $data->name != 'te_token' && $data->name != 'te_chat_id_admin'
                && $data->name != 'te_chat_id_payment')
                $a[$data->name] = $data->val;
        }
        $device = $request->header('idDevice') ?: null;
        if (auth()->guard('api')->check()) {
            $a['cbc_type'] = 'user';
            $a['cbc_id'] = auth()->guard('api')->user()->id;
            $a['is_cbc'] = auth()->guard('api')->user()->blocked;
        } else if($device){
            $findDevice = Device::findByUuid($device);
            $a['cbc_type'] = 'device';
            $a['cbc_id'] = $findDevice->id;
            $a['is_cbc'] = $findDevice ? $findDevice->blocked : 0;
        } else {
            $a['cbc_type'] = 'guest';
            $a['cbc_id'] = null;
            $a['is_cbc'] = 0;
        }
//        $a['dda'] = auth()->guard('api')->user();
        return $a;
    }
}

