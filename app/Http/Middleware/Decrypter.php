<?php

namespace App\Http\Middleware;

use App\Helpers\UserSystemInfoHelper;
use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class Decrypter
{
    public function handle($request, Closure $next, ...$guards)
    {

        //   $token = $request->bearerToken();

        $agent = new Agent();

        $header = $request->header('ik');
        $device = $request->header('idDevice') ?: null;
        if($device){
            $findDevice = Device::findByUuid($device);
            if($findDevice){
                $findDevice->update(['last_activity'=>now()]);
            } else{
                UserSystemInfoHelper::createDevice($device);
            }
        }
        if(auth()->check())
            \App\Models\User::where('id',auth()->user()->id)->update(['last_activity'=>now()]);

        //   if (Str::startsWith($header, 'Bearer ')) {
        //     $token = Str::substr($header, 7);
        //   }

        //   return response()->json("$token", 401);
        if (
            //($agent->isAndroidOS() || $agent->isiOS()) &&
            "cDJsYmdXa3lrQTR5RWd5YmVzdEJ5Rmxhc2h1" == $header) {

            return $next($request);
        } else {

            return response()->json("Unauthorized", 401);
        }
    }
}
