<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matche;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\MatcheResource;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Artisan;
use App\Notification\NotificationTe;

class MatcheController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function search(Request $request)
    // {
    //     $query = $request->q;

    //     if(isset($query)) {
    //        $leagues = League::where('name', 'LIKE', "%$query%")->orWhere('name_ar', 'LIKE', "%$query%")->limit(6)->get();
    //     }else{
    //         return $this->sendResponse([],null);

    //     }
    //     return $this->sendResponse($leagues,null);
    // }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function live(Request $request){

    // }

    public function data(Request $request)
    {
        // matche query
        $matches = Matche::query();

        if($request->date){
            $matches->whereDate('startTime','=', $request->date);
        }



        $matches->where(function($query) use($request){
            if($request->com && $request->com != '[]'){
                $replace = array(
                    '[' => '',
                    ']' => ''
                );
                $myArray = str_replace(array_keys($replace), array_values($replace), $request->com);
                $myArray = explode(',', $myArray);
                $query->orWhereIn('com_id', $myArray);
            }
            if($request->team && $request->team!= '[]'){
                $replace = array(
                    '[' => '',
                    ']' => ''
                );
                $myArray = str_replace(array_keys($replace), array_values($replace), $request->team);
                $myArray = explode(',', $myArray);
                $query->orWhereIn('team_id_a',$myArray);
                $query->orWhereIn('team_id_b',$myArray);
            }

        });



        $matches->where('active', 1);
        // matche query
        $matches = $matches->orderBy('startTime');
        $matches = $matches->orderByDesc('com_id');

        if($request->live){
            $matches= $matches->where('statusGroup', '=' ,3)->get();
            return $this->sendResponse(MatcheResource::collection($matches),null);
        }

        // Paginate matche results
        $matches = $matches->where('statusGroup', '!=' ,3)->paginate(10);
        // $matche = Matche::orderByDesc('created_at')
        // ->paginate(6);
        $formatedDate = null;
        if($matches->isEmpty() && !($request->live) &&
        !($request->com && $request->com != '[]') &&
        !($request->team && $request->team!= '[]'))
        {
            $date = Carbon::createFromFormat('Y-m-d', $request->date);
            $formatedDate = $date->format('d/m/Y');
            Artisan::call('matche:daily ' . $formatedDate);
        }
        $matches = MatcheResource::collection($matches,$request->timezone)->additional(['timezone' => $request->timezone])->response()->getData(true);
        return $this->sendResponse($matches,'matche:daily ' . $formatedDate);
    }

}
