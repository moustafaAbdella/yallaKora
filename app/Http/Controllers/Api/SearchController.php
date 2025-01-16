<?php

namespace App\Http\Controllers\Api;


use App\Models\Movie;
use App\Models\Serie;
use App\Models\Livetv;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use Auth;
use Carbon\Carbon;
class SearchController extends BaseController
{
     /**
     * Store Report Movie
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required',
        ]);
        // if(env('hidden_movie_serie') == 1){
        //     return $this->sendError('Not found',[],400);
        // }

        $livetvs = $this->getLiveTvs($request);

        return $this->sendResponse([
            'livetvs'=> $livetvs,
//            'media' =>$array
        ], '');

    }

    public function getLiveTvs(Request $request)
    {

        // //   ],
        // $series = Serie::query();
        // // $series =  DB::table('series');where('title', 'LIKE', "%$query%")
        // $series = $series->select('series.name','series.id','series.poster_path','series.vote_average','series.first_air_date', 'series.created_at','series.views')
        // ->addSelect(DB::raw("'serie' as type"))->where('active', '=', 1);

        // if($request->isKids == 1){
        //     $series = $series->whereHas('genres', function ($query) use($request){
        //         $query->where('genre_id', 10762);
        //     });
        // }

        return Livetv::where('name', 'LIKE', "%$request->q%")->where('active', 1)->limit(6)->get();
    }
}
