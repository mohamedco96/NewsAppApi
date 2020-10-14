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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function authorPosts($id)
    {
        $query = DB::table('posts')
            ->join('authors', 'posts.author', '=', 'authors.id')
            ->where('posts.author', '=', $id)
            ->get();
        return new AuthorResource($query);
    }
}
