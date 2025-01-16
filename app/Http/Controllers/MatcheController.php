<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\MatcheRequest;
use App\Http\Requests\Admin\MatcheUpdateRequest;
use App\Models\Matche;
use App\Models\League;
use App\Models\Team;
use App\Models\Season;
use App\Models\MatcheVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MatcheController extends Controller
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
           $matches = Matche::where('name', 'LIKE', "%$query%")->orWhere('name_ar', 'LIKE', "%$query%")->limit(6)->get();
        }else{
            return [];
        }
        return response()->json($matches, 200);
    }

     /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        // matche query
        $matches = Matche::query();

        if($request->date){
            $matches->whereDate('startTime','=', $request->date);
        }

        if($request->status == 'live'){
            $matches->where('statusGroup', '=' , 3);
        }else if($request->status == 'coming'){
            $matches->where('statusGroup', '=' , 2);
        }else if($request->status == 'done'){
            $matches->where('statusGroup', '=' , 4);
        }

        // matche query
        $matches = $matches->orderByDesc('created_at');

        // Paginate matche results
        $matches = $matches->paginate(6);

        return response()->json($matches, 200);
    }

    public function getVideos(Matche $matche) {
        return $matche->videos;
    }
    // remove a video from a matche from the database
    public function videoDestroy($video)
    {
        $video = MatcheVideo::find($video);
        if ($video != null) {
            $video->delete();

            $data = [
                'status' => 200,
                'message' => 'successfully deleted',
            ];
        } else {
            $data = [
                'status' => 400,
                'message' => 'could not be deleted',
            ];
        }

        return response()->json($data, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function count()
    {
        $date = Carbon::now();
        $fdate = $date->format('Y-m-d');
//        {ended:4,started:3,scheduled:2,anticipated:1}
        return response()->json(
            [
            'todaycount' => Matche::whereDate('startTime','=', $fdate)->count(),
            'comingcount' => Matche::whereDate('startTime','=', $fdate)->where('statusGroup', '=' , 2)->where('statusText', '=' , 'Scheduled')->count(),
            'livecount' => Matche::whereDate('startTime','=', $fdate)->where('statusGroup', '=' , 3)->count()
        ], 200);
    }

    // create a new genre in the database
    public function store(MatcheRequest $request)
    {
        $matche = new Matche();

        $matche->fill($request->matche);

        if ($request->league) {
            $league = League::find($request->league['value']);
            if($league!= null){
                $matche->com_id = $league->id;
            }else {
                $data = [
                    'status' => 400,
                    'message' => 'تاكد من اختيار بطولة بشكل صحيح',
                ];

                return response()->json($data, $data['status']);
            }
        }

        if ($request->team_a) {
            $team_a = Team::find($request->team_a['value']);

            if($team_a!= null){
                $matche->team_id_a  = $team_a->id;
            }else {
                $data = [
                    'status' => 400,
                    'message' => 'تاكد من اختيار الفريق الاول بشكل صحيح',
                ];

                return response()->json($data, $data['status']);
            }
        }

        if ($request->team_b) {
            $team_b = Team::find($request->team_b['value']);
            if($team_b!= null){
                $matche->team_id_b  = $team_b->id;
            }else {
                $data = [
                    'status' => 400,
                    'message' => 'تاكد من اختيار الفريق الثاني بشكل صحيح',
                ];

                return response()->json($data, $data['status']);
            }
        }
        if ($request->season) {
            $season = Season::find($request->season['value']);
            if($season !=null) {
                $matche->season_id = $season->id;
            }
        }

        $matche->save();

        $matche = Matche::find($matche->id);

        if ($request->links) {
            foreach ($request->links as $link) {
                $movieVideo = new MatcheVideo();
                $movieVideo->fill($link);
                $movieVideo->matche_id = $matche->id;
                $movieVideo->save();
            }
        }



        $data = [
            'status' => 200,
            'message' => 'successfully created',
            'body' => $matche
        ];

        return response()->json($data, $data['status']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matche  $matche
     * @return \Illuminate\Http\Response
     */
    public function changeActive(Request $request, $matche)
    {

        $matche = Matche::find($matche);

        if($matche != null) {
            $matche->active = $request->active === 'true'? 1: 0;
            $matche->save();

            $data = [
                'status' => 200,
                'message' => 'تم تغير بنجاح',
                'body' => $matche
            ];
        }else{
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد بطولة',
                'body' => $matche
            ];
        }
        return response()->json($data, $data['status']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matche  $matche
     * @return \Illuminate\Http\Response
     */
    public function update(MatcheUpdateRequest $request, $matche)
    {
        $matche = Matche::find($matche);
        if($matche != null) {

            if ($request->league) {
                $league = League::find($request->league['value']);
                if($league!= null){
                    $matche->com_id = $league->id;
                }else {
                    $data = [
                        'status' => 400,
                        'message' => 'تاكد من اختيار بطولة بشكل صحيح',
                    ];

                    return response()->json($data, $data['status']);
                }
            }

            if ($request->team_a) {
                $team_a = Team::find($request->team_a['value']);

                if($team_a!= null){
                    $matche->team_id_a  = $team_a->id;
                }else {
                    $data = [
                        'status' => 400,
                        'message' => 'تاكد من اختيار الفريق الاول بشكل صحيح',
                    ];

                    return response()->json($data, $data['status']);
                }
            }

            if ($request->team_b) {
                $team_b = Team::find($request->team_b['value']);
                if($team_b!= null){
                    $matche->team_id_b  = $team_b->id;
                }else {
                    $data = [
                        'status' => 400,
                        'message' => 'تاكد من اختيار الفريق الثاني بشكل صحيح',
                    ];

                    return response()->json($data, $data['status']);
                }
            }
            if ($request->season) {
                $season = Season::find($request->season['value']);
                if($season !=null) {
                    $matche->season_id = $season->id;
                }
            }

            $matche->fill($request->matche);
            $matche->save();

            $matche = Matche::find($matche->id);

            if ($request->links) {
                foreach ($request->links as $link) {
                    if (!isset($link['id'])) {
                        $movieVideo = new MatcheVideo();
                        $movieVideo->fill($link);
                        $movieVideo->matche_id = $matche->id;
                        $movieVideo->save();
                    }
                }
            }

            $data = [
                'status' => 200,
                'message' => 'تم تحديث مباراه بنجاح',
                'body' => $matche
            ];
        }else {
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد مباراه',
                'body' => $matche
            ];
        }

        return response()->json($data, $data['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Matche  $matche
     * @return \Illuminate\Http\Response
     */
    public function destroy($matche)
    {
        $matche = Matche::find($matche);

        if ($matche != null) {

            $matche->delete();

            $data = [
                'status' => 200,
                'message' => 'successfully removed',
            ];
        } else {
            $data = [
                'status' => 400,
                'message' => 'could not be deleted',
            ];
        }

        return response()->json($data, $data['status']);

    }


}
