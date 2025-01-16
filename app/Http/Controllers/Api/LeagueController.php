<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\LeagueResource;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LeagueController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $query = $request->q;

        if(isset($query)) {
            $leagues = Cache::remember('searche_leagues_query_' . $query, 6000, function () use ($query) {
                return League::where('name', 'LIKE', "%$query%")->orWhere('name_ar', 'LIKE', "%$query%")->limit(6)->get();
            });
           if(auth()->guard('api')->check())
           {
              auth()->guard('api')->user()->attachFavoriteStatus($leagues);
           }
        }else{
            return $this->sendError('حدث خطا');
        }
        if($leagues != null && !$leagues->isEmpty()) {
            return $this->sendResponse(LeagueResource::collection($leagues),null);
        }else{
            return $this->sendError('لم نتمكن من العثور على أي نتائج لبحثك');
        }
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        $currentPage = request()->get('page', 1);

        $leagues = League::orderBy(DB::raw('ISNULL(position), position'), 'ASC')
            ->paginate(10);
//        $leagues = Cache::remember('leagues_data_page_' . $currentPage, 6000, function () {
//            return
//        });
        if(auth()->guard('api')->check())
        {
           auth()->guard('api')->user()->attachFavoriteStatus($leagues);
        }
        $leagues = LeagueResource::collection($leagues)->response()->getData(true);
        return $this->sendResponse($leagues,null);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setFavorite(Request $request)
    {
        $user = auth()->user();
        $leaguse = League::find($request->id);

        if($leaguse != null) {
            // $user->favorite($leaguse);

            $user->toggleFavorite($leaguse);
            $this->onUpdateCacheFavorite($user);
            return $this->sendResponse([],'تم ايضافة في مفضلة بنجاح');
        }else {
            return $this->sendError('حدث خطا تعذر ايجاد بطولة');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeFavorite(Request $request)
    {
        $user = auth()->user();
        $leaguse = League::find($request->id);

        if($leaguse != null) {
             $user->unfavorite($leaguse);
             $this->onUpdateCacheFavorite($user);
           // $user->toggleFavorite($leaguse);

           return $this->sendResponse([],'تم حذف من مفضلة بنجاح');
        }else {
            return $this->sendError('حدث خطا تعذر ايجاد بطولة');
        }
    }

         /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFavorite(Request $request)
    {
        $user = auth()->user();

        if($request->type == 'all'){
            $favorte = Cache::remember('leagues_favorte_all_userId_' . $user->id, 6000, function () use($user){
                return $user->getFavoriteItems(League::class)->get();
            });
            $favorte = LeagueResource::collection($favorte);
            return $this->sendResponse($favorte,null);
        }
        $currentPage = request()->get('page', 1);

        $favorte = Cache::remember('leagues_favorte_page_'.$currentPage.'_userId_' . $user->id, 6000, function () use($user){
            return $user->getFavoriteItems(League::class)->paginate(6);
        });

        $favorte = LeagueResource::collection($favorte)->response()->getData(true);
        return $this->sendResponse($favorte,null);
    }

    public function onUpdateCacheFavorite($user)
    {
        if (Cache::has('leagues_favorte_all_userId_' . $user->id)) {
            Cache::forget('leagues_favorte_all_userId_' . $user->id);
        }
        if(Cache::has('leagues_favorte_page_1_userId_' . $user->id)){
            for ($i=0; $i < 1000; $i++) {
                $key = 'leagues_favorte_page_' . $i . '_userId_' . $user->id;
                if (Cache::has($key)) {
                    Cache::forget($key);
                } else {
                    break;
                }
            }
        }
    }
}
