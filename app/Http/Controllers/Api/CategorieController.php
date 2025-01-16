<?php

namespace App\Http\Controllers\Api;

use App\Models\Livetv;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use Auth;
use Carbon\Carbon;
class CategorieController extends BaseController
{
     /**
     * Store Report Movie
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategories(Request $request)
    {
        if(env('en_live') == 1){
            return $this->sendError('Not found',[],400);
        }

        $categories = Category::query();

        if($request->type == 'subcategories'){
            $categories = $categories->whereNotNull('parent_id')->with('parent');
        }else if($request->type == 'categories'){
            $categories = $categories->whereNull('parent_id');
        }else {
            $categories = $categories->whereNull('parent_id')->with('children');
        }
        $categories = $categories->where('active', 1)->orderBy(DB::raw('ISNULL(position), position'), 'ASC')->get();

        if($categories){
            return $this->sendResponse($categories, '');
        }
        return $this->sendError('Not found',[],400);

    }

    public function getLiveTv(Request $request)
    {
         if(env('en_live') == 1){
             return $this->sendError('Not found',[],400);
         }

        $liveTvs = Livetv::query();

        if($request->genre_id != null) {
            $liveTvs = $liveTvs->whereHas('genres', function ($query) use($request){
                $ids = explode(',', $request->genre_id);
                $query->whereIn('category_id', $ids);
            });
        }
        $liveTvs = $liveTvs->where('active', 1)->orderBy(DB::raw('ISNULL(position), position'), 'ASC');

        $liveTvs = $liveTvs->paginate(20);

        if($liveTvs){
            return $this->sendResponse($liveTvs, '');
        }
        return $this->sendError('Not found',[],400);

    }
}
