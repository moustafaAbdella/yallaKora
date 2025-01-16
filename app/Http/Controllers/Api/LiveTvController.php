<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\MatcheResource;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Artisan;
use App\Models\Livetv;
use DB;
use Illuminate\Support\Facades\Cache;

class LiveTvController extends BaseController
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
        $currentPage = request()->get('page', 1);

        $livetv = Cache::remember('livetv_page_'.$currentPage, 3500, function () {
            // Livetv query
            $livetv = Livetv::query();
    
            $livetv->where('active', 1)->orderBy(DB::raw('ISNULL(position), position'), 'ASC');
    
            // Paginate Livetv results
            return $livetv->paginate(10);
        });
        return $this->sendResponse($livetv,null);
    }


}
