<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\TeameResource;
use Illuminate\Support\Facades\Cache;

class TeamController extends BaseController
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
            $teams = Cache::remember('searche_teams_query_' . $query, 6000, function () use ($query) {
                return Team::where('name', 'LIKE', "%$query%")->orWhere('name_ar', 'LIKE', "%$query%")->limit(6)->get();
            });
           if(auth()->guard('api')->check())
           {
              auth()->guard('api')->user()->attachFavoriteStatus($teams);
           }
        }else{
            return $this->sendError('غير صحيح');
        }
        if($teams && !$teams->isEmpty()) {
            return $this->sendResponse(TeameResource::collection($teams),null);
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


        $teams = Team::orderBy(DB::raw('ISNULL(position), position'), 'ASC')
            ->paginate(10);
//        $teams = Cache::remember('teams_data_page_' . $currentPage, 6000, function () {
//            return Team::orderBy(DB::raw('ISNULL(position), position'), 'ASC')
//            ->paginate(10);
//        });
        if(auth()->guard('api')->check())
        {
           auth()->guard('api')->user()->attachFavoriteStatus($teams);
        }
        $teams = TeameResource::collection($teams)->response()->getData(true);
        return $this->sendResponse($teams,'تم ايضافة في مفضلة بنجاح');

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
        $team = Team::find($request->id);

        if($team != null) {
            //$user->favorite($team);
            $user->toggleFavorite($team);
            $this->onUpdateCacheFavorite($user);

            return $this->sendResponse([],'تم ايضافة في مفضلة بنجاح');
        }else {
            return $this->sendError('حدث خطا تعذر ايجاد فريق');
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
        $team = Team::find($request->id);

        if($team != null) {
            $user->unfavorite($team);
             $this->onUpdateCacheFavorite($user);
            return $this->sendResponse([],'تم حذف من مفضلة بنجاح');
        }else {
            return $this->sendError('حدث خطا تعذر ايجاد فريق');
        }
    }

         /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFavorite(Request $request)
    {
//        return $this->sendError('حدث خطا تعذر ايجاد فريق');
        $user = auth()->user();

        if($request->type == 'all'){
            $favorte = Cache::remember('teams_favorte_all_userId_' . $user->id, 6000, function () use($user){
                return $user->getFavoriteItems(Team::class)->get();
            });
            $favorte = TeameResource::collection($favorte);
            return $this->sendResponse($favorte,null);
        }
        $currentPage = request()->get('page', 1);

        $favorte = Cache::remember('teams_favorte_all_page_'.$currentPage.'_userId_' . $user->id, 6000, function () use($user){
            return $user->getFavoriteItems(Team::class)->paginate(6);
        });
        $favorte = TeameResource::collection($favorte)->response()->getData(true);
        return $this->sendResponse($favorte,null);
    }

    public function onUpdateCacheFavorite($user)
    {
        if (Cache::has('teams_favorte_all_userId_' . $user->id)) {
            Cache::forget('teams_favorte_all_userId_' . $user->id);
        }
        if(Cache::has('teams_favorte_all_page_1_userId_' . $user->id)){
            for ($i=1; $i < 1000; $i++) {
                $key = 'teams_favorte_all_page_' . $i . '_userId_' . $user->id;
                if (Cache::has($key)) {
                    Cache::forget($key);
                } else {
                    break;
                }
            }
        }
    }
}
