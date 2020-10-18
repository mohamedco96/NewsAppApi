<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $art = Category::all();
        return CategoryResource::collection($art);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'tittle' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $art = Category::create($data);

        return response(['message' => 'Created Category successfully', 'Category' => new CategoryResource($art)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Category = Category::find($id);
        if ($Category) {
            return new CategoryResource($Category);
        }
        return "Category Not found";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $art = Category::find($id);
        $art->update($request->all());
        return response(['message' => 'Update Category successfully', 'Category' => new CategoryResource($art)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)

    {
        $art = Category::findOrfail($id);
        if ($art->delete()) {
            return response(['message' => 'Category Deleted successfully']);
        }
        return "Error while deleting";
    }


    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function GetPostByCateory($id)
    {
        $query = DB::table('posts')
            ->join('post_category_pivots', 'posts.id', '=', 'post_category_pivots.post_id')
            ->where('post_category_pivots.category_id', '=', $id)
            ->get();
        return new CategoryResource($query);
    }



    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function GetPostByCateorySort($id)
    {
        if ($id == 1) {
            $query = DB::table('posts')
                ->join('post_category_pivots', 'posts.id', '=', 'post_category_pivots.post_id')
                ->orderBy('created_at', 'desc')
                ->get();
            return new CategoryResource($query);
        }

        if ($id == 2) {
            $query = DB::table('posts')
                ->join('post_category_pivots', 'posts.id', '=', 'post_category_pivots.post_id')
                ->orderBy('updated_at', 'desc')
                ->get();
            return new CategoryResource($query);
        } else {
            $query = DB::table('posts')->get();
            return new CategoryResource($query);
        }
    }
}
