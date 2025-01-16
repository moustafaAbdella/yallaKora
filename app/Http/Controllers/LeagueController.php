<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\LeagueRequest;
use App\Http\Requests\Admin\LeagueUpdateRequest;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use DB;

class LeagueController extends Controller
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
           $leagues = League::where('name', 'LIKE', "%$query%")->orWhere('name_ar', 'LIKE', "%$query%")->limit(6)->get();
        }else{
            return [];
        }
        return response()->json($leagues, 200);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        return response()->json(League::orderBy(DB::raw('ISNULL(position), position'), 'ASC')
        ->paginate(6), 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function count()
    {
        return response()->json(['count'=>League::count()], 200);
    }

    // create a new genre in the database
    public function store(LeagueRequest $request)
    {
        $league = new League();
         
        $league->fill($request->league);
  
        $league->api_slug = str_replace(' ', '-', $league->name);
        
        $league->save();
        $league = League::find($league->id);

        $data = [
            'status' => 200,
            'message' => 'successfully created',
            'body' => $league
        ];

        return response()->json($data, $data['status']);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function changeFeatured(Request $request, $league)
    {
        
        $league = League::find($league);

        if($league != null) {
            $league->featured = $request->featured === 'true'? 1: 0;
            $league->save();
    
            $data = [
                'status' => 200,
                'message' => 'تم تغير بنجاح',
                'body' => $league
            ];
        }else{
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد بطولة',
                'body' => $league
            ];
        }
        return response()->json($data, $data['status']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $league)
    {
        $league = League::find($league);
        if($league != null) {
            $league->fill($request->league);
            $league->save(); 
            $data = [
                'status' => 200,
                'message' => 'تم تحديث بطولة بنجاح',
                'body' => $league
            ];
        }else {
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد بطولة',
                'body' => $league
            ];   
        }

        return response()->json($data, $data['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function destroy($league)
    {
        $league = League::find($league);

        if ($league != null) {
            if($league->img != null && $league->img != 'placeholder.png' &&Storage::disk('leagues')->exists($league->img)){
                $remove = Storage::disk('leagues')->delete($league->img);
            }

            $league->delete();

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

            $filename = Storage::disk('leagues')->put('', $request->image);
           // $filename = $request->file('image')->storeAs('leagues/', $path);

            $data = [
                'status' => 200,
                'path' => $filename,
                'ext' => $ext,
                'image_path' => $request->root() . '/api/image/comp-logo/' . $filename,
                'message' => 'successfully uploaded'
            ];
        } else {
            $data = [
                'status' => 400,
                'message' => 'could not be uploaded'
            ];
        }
        // $image = Storage::disk('leagues')->get($filename);

        // $mime = Storage::disk('leagues')->mimeType($filename);

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
            if(Storage::disk('leagues')->exists($request->img)){
                $remove = Storage::disk('leagues')->delete($request->img);
                if(isset($request->League) && $request->League != null) {
                    $league = League::find($request->League);
                    $league->img = null;
                    $league->save();
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
        $image = Storage::disk('leagues')->get($filename);

        $mime = Storage::disk('leagues')->mimeType($filename);

        return (new Response($image, 200))
            ->header('Content-Type', $mime);
    }
}
