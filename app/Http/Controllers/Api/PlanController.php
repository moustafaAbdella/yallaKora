<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\PlanResource;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Artisan;

class PlanController extends BaseController
{


    public function data(Request $request)
    {
        $plan = Subscription::orderByDesc('created_at')->where('active',1)->get();
        return $this->sendResponse(PlanResource::collection($plan),null);
    }

    public function my(Request $request)
    {
        $userId = Auth()->user()->subscription_id;
        if(!$userId && Auth()->user()->premuim ==1){
            return $this->sendResponse([]);
        }else if(!$userId){
            return $this->sendError('غير مشترك');
        }
        $plan = SubscriptionHistory::find($userId);
        if($plan){
            return $this->sendResponse($plan);
        }else{
            return $this->sendError('حدث خطا');
        }
        //$plan
    }

}
