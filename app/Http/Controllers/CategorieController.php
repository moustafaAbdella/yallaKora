<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Facades\Cache;

class CategorieController extends Controller
{
    // returns all genres for the api
    public function index(Request $request)
    {
        $categories = Category::query();

        if($request->type == 'subcategories'){
            $categories = $categories->whereNotNull('parent_id')->with('parent');
        }else if($request->type == 'categories'){
            $categories = $categories->whereNull('parent_id');
        }else {
            $categories = $categories->with('parent');
        }

        return response()->json($categories->get(), 200);
    }

    // returns all genres for the admin panel
    public function data(Request $request)
    {
        $categories = Category::query();

        if($request->type == 'subcategories'){
            $categories = $categories->whereNotNull('parent_id')->with('parent');
        }else if($request->type == 'categories'){
            $categories = $categories->whereNull('parent_id');
        }else {
            $categories = $categories->with('parent');
        }

        return response()->json($categories->
        orderByRaw('COALESCE(position, 999999) ASC, created_at ASC')
        // orderBy(DB::raw('ISNULL(position), position'), 'ASC')
        ->paginate(13), 200);
    }

     /**
     * Search genres by name or id
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $categories = Category::query();
        $categories = $categories->where('name', 'like', '%'. $request->input('q') . '%');

        if($request->type == 'subcategories'){
            $categories = $categories->whereNotNull('parent_id')->with('parent');
        }else if($request->type == 'categories'){
            $categories = $categories->whereNull('parent_id');
        }else {
            $categories = $categories->with('parent');
        }

        $categories = $categories->limit(6)->get();

        return response()->json($categories, 200);
    }
        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Livetv  $livetv
     * @return \Illuminate\Http\Response
     */
    public function changeFeatured(Request $request, $category)
    {

        $category = Category::find($category);

        if($category != null) {
            $category->active = $request->featured === 'true'? 1: 0;
            $category->save();

            $data = [
                'status' => 200,
                'message' => 'تم تغير بنجاح',
                'body' => $category
            ];
        }else{
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد قناة',
                'body' => $category
            ];
        }
        // $this->onUpdateCache();
        return response()->json($data, $data['status']);
    }

    // create a new genre in the database
    public function store(CategoryRequest $request)
    {
        $categorie = new Category();
        $categorie->fill($request->all());
        $categorie->save();

        $data = [
            'status' => 200,
            'message' => 'successfully created',
            'body' => $categorie
        ];

        return response()->json($data, $data['status']);
    }
    // delete a genre from the database
    public function destroy( $categorie)
    {
        $categorie = Category::find($categorie);
        if($categorie != null){
            $categorie->delete();
            $data = [
                'status' => 200,
                'message' => 'successfully deleted'
            ];
        }else{
            $data = [
                'status' => 400,
                'message' => 'could not be deleted'
            ];
        }

        return response()->json($data, $data['status']);
    }

    // update a genre in the database
    public function update(CategoryRequest $request, $categorie){
        $categorie = Category::find($categorie);
        if($categorie != null){
            $categorie->fill($request->all());
            $categorie->save();
            $categorie = Category::where('id' , $categorie->id)->with('parent')->first();
            $data = [
                'status' => 200,
                'message' => 'successfully updated',
                'body' => $categorie
            ];
        }else{
            $data = [
                'status' => 400,
                'message' => 'could not be updated'
            ];
        }

        return response()->json($data, $data['status']);
    }

    // return all genres only with the id and name properties
    public function list()
    {
        return response()->json(Category::all('id', 'name'), 200);
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
                    $livetv = Category::find($request->livetv);
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
}
