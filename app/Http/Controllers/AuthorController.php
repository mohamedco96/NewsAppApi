<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $art = Author::all();
        return AuthorResource::collection($art);
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
            'name' => 'required',
            'avatar' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $art = Author::create($data);

        return response(['message' => 'Created Author successfully', 'Author' => new AuthorResource($art)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Author = Author::find($id);
        if ($Author) {
            return new AuthorResource($Author);
        }
        return "Author Not found";
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
        $art = Author::find($id);
        $art->update($request->all());
        return response(['message' => 'Update Author successfully', 'Author' => new AuthorResource($art)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)

    {
        $art = Author::findOrfail($id);
        if ($art->delete()) {
            return response(['message' => 'Author Deleted successfully']);
        }
        return "Error while deleting";
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function addToFavorites(Request $request)
    {
        $userInfo = auth('api')->user();
        if ($userInfo !== null) {
            $Author = Author::find($request->id);
            $Author->favorites()->create([
                'user_id' => $userInfo->id,
            ]);
            return "Author is added for user:" . $userInfo->social_id;
        } else {
            return "User is not logged in.";
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function removeFromFavorites(Request $request)
    {
        $userInfo = auth('api')->user();
        if ($userInfo !== null) {
            $Author = DB::table('favorites')
                ->where('favorites.user_id', '=', $userInfo->id)
                ->where('favorites.favoritable_id', '=', $request->id)
                ->where('favorites.favoritable_type', '=', 'App\Models\Author')
                ->delete();
            // return new AuthorResource($Author);
            return "Author is delete from favorites for user:" . $userInfo->social_id;
        } else {
            return "User is not logged in.";
        }
    }



    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function AuthorFillter(Request $request)
    {

        $query = DB::table('Authors')
            ->join('Authors_catagory_pivots', 'Authors.id', '=', 'Authors_catagory_pivots.Author_id')
            ->join('Author_tag_pivots', 'Authors.id', '=', 'Author_tag_pivots.Author_id');
        $result = $query->get();
        /****************************************************************************************************************/
        if ($request->category != null) {
            $query->where('Authors_catagory_pivots.Authors_catagory_id', '=', $request->category);
            $result = $query->get();
        }

        if ($request->tag != null) {
            $query->where('Author_tag_pivots.Author_tag_id', '=', $request->tag);
            $result = $query->get();
        }
        /****************************************************************************************************************/
        return new AuthorResource($result);
    }
}
