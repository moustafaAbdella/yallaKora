<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\TeamRequest;
use App\Http\Requests\Admin\TeamUpdateRequest;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use DB;

class TeamController extends Controller
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
           $teams = Team::where('name', 'LIKE', "%$query%")->orWhere('name_ar', 'LIKE', "%$query%")->limit(6)->get();
        }else{
            return [];
        }
        return response()->json($teams, 200);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        return response()->json(Team::orderBy(DB::raw('ISNULL(position), position'), 'ASC')
        ->paginate(6), 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function count()
    {
        return response()->json(['count'=>Team::count()], 200);
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function changeFeatured(Request $request, $team)
    {
        
        $team = Team::find($team);

        if($team != null) {
            $team->featured = $request->featured === 'true'? 1: 0;
            $team->save();
    
            $data = [
                'status' => 200,
                'message' => 'تم تغير بنجاح',
                'body' => $team
            ];
        }else{
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد بطولة',
                'body' => $team
            ];
        }
        return response()->json($data, $data['status']);
    }

    // create a new genre in the database
    public function store(TeamRequest $request)
    {
        $team = new Team();
        $team->fill($request->team);
        $team->save();
        $team = Team::find($team->id);

        $data = [
            'status' => 200,
            'message' => 'successfully created',
            'body' => $team
        ];

        return response()->json($data, $data['status']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(TeamUpdateRequest $request, Team $team)
    {
        $team->fill($request->team);
        $team->save(); 
        $team = Team::find($team->id);
        $data = [
            'status' => 200,
            'message' => 'successfully created',
            'body' => $team
        ];

        return response()->json($data, $data['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        if ($team != null) {
            if($team->img != null && $team->img != 'placeholder.png' &&Storage::disk('teams')->exists($team->img)){
                $remove = Storage::disk('teams')->delete($team->img);
            }

            $team->delete();

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

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $img = $request->image;
            
            $path = $img->getClientOriginalName();
            $ext =  $img->getClientOriginalExtension();

            $filename = Storage::disk('teams')->put('', $request->image);
           // $filename = $request->file('image')->storeAs('teams/', $path);

            $data = [
                'status' => 200,
                'path' => $filename,
                'ext' => $ext,
                'image_path' => $request->root() . '/api/image/team-logo/' . $filename,
                'message' => 'successfully uploaded'
            ];
        } else {
            $data = [
                'status' => 400,
                'message' => 'could not be uploaded'
            ];
        }
        // $image = Storage::disk('teams')->get($filename);

        // $mime = Storage::disk('teams')->mimeType($filename);

        // return (new Response($image, 200))
        //     ->header('Content-Type', $mime);
        return response()->json($data, $data['status']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeImage(Request $request)
    {
        if(isset($request->img)) {
            if(Storage::disk('teams')->exists($request->img)){
                $remove = Storage::disk('teams')->delete($request->img);
                if(isset($request->team) && $request->team != null) {
                    $team = Team::find($request->team);
                    $team->img = null;
                    $team->save();
                }
                $data = [
                    'status' => 200,
                    'message' => 'successfully remove'
                ];
            }else{
                $data = [
                    'status' => 402,
                    'message' => 'لم يتم ايجاد ملف'
                ];
            }
        } else {
            $data = [
                'status' => 400,
                'message' => 'حدث خطا'
            ];
        }
        return response()->json($data, $data['status']);

    }
    
    /**
     * return an image from the movies folder of the storage
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function getImg($filename)
    {
        $image = Storage::disk('teams')->get($filename);

        $mime = Storage::disk('teams')->mimeType($filename);

        return (new Response($image, 200))
            ->header('Content-Type', $mime);
    }
}
