<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\LivetvRequest;
use App\Http\Requests\Admin\LivetvUpdateRequest;
use App\Models\Livetv;
use App\Models\Category;
use App\Models\LivetvGenre;
use App\Models\LivetvVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Facades\Cache;

class LivetvController extends Controller
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
           $livetvs = Livetv::where('name', 'LIKE', "%$query%")->limit(6)->get();
        }else{
            return [];
        }
        return response()->json($livetvs, 200);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        return response()->json(Livetv::
        orderByRaw('COALESCE(position, 999999) ASC, created_at ASC')
        // orderByRaw('ISNULL(position), position NULLS LAST, created_at')
        //orderBy(DB::raw('ISNULL(position), position'), 'ASC')
        ->paginate(6), 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function count()
    {
        return response()->json(['count'=>Livetv::count()], 200);
    }

    // create a new genre in the database
    public function store(LivetvRequest $request)
    {
        $livetv = new Livetv();
         
        $livetv->fill($request->livetv);
          
        $livetv->save();
        if ($request->livetv['genres']) {
            foreach ($request->livetv['genres'] as $genre) {
                $find = Category::find($genre['id']);
                if ($find == null) {
                    $find = new Category();
                    $find->fill($genre);
                    $find->save();
                }
                $movieGenre = new LivetvGenre();
                $movieGenre->category_id = $genre['id'];
                $movieGenre->livetv_id = $livetv->id;
                $movieGenre->save();
            }
        }
        if ($request->livetv['videos']) {
            foreach ($request->livetv['videos'] as $link) {
                $livetvVideo = new LivetvVideo();
                $livetvVideo->fill($link);
                $livetvVideo->livetv_id = $livetv->id;
                $livetvVideo->hd = $link['hd'] == 'true'? 1: 0;
                $livetvVideo->save();
            }
        }
        $livetv = Livetv::find($livetv->id);

        $data = [
            'status' => 200,
            'message' => 'successfully created',
            'body' => $livetv
        ];
        $this->onUpdateCache();
        return response()->json($data, $data['status']);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Livetv  $livetv
     * @return \Illuminate\Http\Response
     */
    public function changeFeatured(Request $request, $livetv)
    {
        
        $livetv = Livetv::find($livetv);

        if($livetv != null) {
            $livetv->active = $request->featured === 'true'? 1: 0;
            $livetv->save();
    
            $data = [
                'status' => 200,
                'message' => 'تم تغير بنجاح',
                'body' => $livetv
            ];
        }else{
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد قناة',
                'body' => $livetv
            ];
        }
        $this->onUpdateCache();
        return response()->json($data, $data['status']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Livetv  $livetv
     * @return \Illuminate\Http\Response
     */
    public function update(LivetvUpdateRequest $request, $livetv)
    {
        $livetv = Livetv::find($livetv);
        if($livetv != null) {
            $livetv->fill($request->livetv);
            $livetv->save(); 
            if ($request->livetv['genres']) {
                foreach ($request->livetv['genres'] as $genre) {
                    if (!isset($genre['category_id'])) {
                        $find = Category::find($genre['id'] ?? 0) ?? new Category();
                        $find->fill($genre);
                        $find->save();
                        $livetvGenre = LivetvGenre::where('livetv_id', $livetv->id)
                            ->where('category_id', $genre['id'])->get();
                        if (count($livetvGenre) < 1) {
                            $livetvGenre = new LivetvGenre();
                            $livetvGenre->category_id = $genre['id'];
                            $livetvGenre->livetv_id = $livetv->id;
                            $livetvGenre->save();
                        }
                    }
                }
            }
            if ($request->livetv['videos']) {
                foreach ($request->livetv['videos'] as $link) {
                    if (!isset($link['id'])) {
                        $livetvVideo = new LivetvVideo();
                        $livetvVideo->fill($link);
                        $livetvVideo->livetv_id = $livetv->id;
                        $livetvVideo->hd = $link['hd'] == 'true'? 1: 0;
                        $livetvVideo->save();
                    }
                }
            }
            $livetv = Livetv::find($livetv->id);
            $data = [
                'status' => 200,
                'message' => 'تم تحديث قناة بنجاح',
                'body' => $livetv
            ];
        }else {
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد قناة',
                'body' => $livetv
            ];   
        }

        $this->onUpdateCache();

        return response()->json($data, $data['status']);
    }

    public function onUpdateCache()
    {
        if(Cache::has('livetv_page_1')){
            for ($i=1; $i < 1000; $i++) {
                $key = 'livetv_page_' . $i;
                if (Cache::has($key)) {
                    Cache::forget($key);
                } else {
                    break;
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Livetv  $livetv
     * @return \Illuminate\Http\Response
     */
    public function destroy($livetv)
    {
        $livetv = Livetv::find($livetv);

        if ($livetv != null) {
            if($livetv->logo != null && $livetv->logo != 'placeholder.png' &&Storage::disk('livetv')->exists($livetv->logo)){
                $remove = Storage::disk('livetv')->delete($livetv->logo);
            }

            $livetv->delete();

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
        $this->onUpdateCache();
        return response()->json($data, $data['status']);

    }

    public function videoDestroy($video)
    {
        if ($video != null) {
            LivetvVideo::find($video)->delete();
            $data = ['status' => 200, 'message' => 'successfully deleted',];
        } else {
            $data = ['status' => 400, 'message' => 'could not be deleted',];
        }
        return response()->json($data, 200);
    }

    // remove the genre of a movie from the database
    public function destroyGenre($genre)
    {
        if ($genre != null) {
            LivetvGenre::find($genre)->delete();
            $data = ['status' => 200, 'message' => 'successfully deleted',];
        } else {
            $data = ['status' => 400, 'message' => 'could not be deleted',];
        }
        return response()->json($data, 200);
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $logo = $request->image;
            
            $path = $logo->getClientOriginalName();
            $ext =  $logo->getClientOriginalExtension();

            $filename = Storage::disk('livetv')->put('', $request->image);
           // $filename = $request->file('image')->storeAs('Livetvs/', $path);

            $data = [
                'status' => 200,
                'path' => $filename,
                'ext' => $ext,
                'image_path' => $request->root() . '/storage/livetv/' . $filename,
                'message' => 'successfully uploaded'
            ];
        } else {
            $data = [
                'status' => 400,
                'message' => 'could not be uploaded'
            ];
        }
        // $image = Storage::disk('livetv')->get($filename);

        // $mime = Storage::disk('livetv')->mimeType($filename);

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
        if(isset($request->logo)) {
            if(Storage::disk('livetv')->exists($request->logo)){
                $remove = Storage::disk('livetv')->delete($request->logo);
                if(isset($request->livetv) && $request->livetv != null) {
                    $livetv = Livetv::find($request->livetv);
                    $livetv->logo = 'placeholder.png';
                    $livetv->save();
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
     * @param  \App\Models\Livetv  $livetv
     * @return \Illuminate\Http\Response
     */
    public function getImg($filename)
    {
        $image = Storage::disk('livetv')->get($filename);

        $mime = Storage::disk('livetv')->mimeType($filename);

        return (new Response($image, 200))
            ->header('Content-Type', $mime);
    }
}