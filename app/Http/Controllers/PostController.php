<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $art = Post::all();
        return PostResource::collection($art);
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
            'author' => 'required|numeric',
            'content' => 'required',
            'image' => 'required',
            'vote_up' => 'numeric',
            'vote_down' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $art = Post::create($data);

        return response(['message' => 'Created Post successfully', 'Post' => new PostResource($art)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Post = Post::find($id);
        if ($Post) {
            return new PostResource($Post);
        }
        return "Post Not found";
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
        $art = Post::find($id);
        $art->update($request->all());
        return response(['message' => 'Update Post successfully', 'Post' => new PostResource($art)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)

    {
        $art = Post::findOrfail($id);
        if ($art->delete()) {
            return response(['message' => 'Post Deleted successfully']);
        }
        return "Error while deleting";
    }


    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function PostPosts($id)
    {
        $query = DB::table('posts')
            ->join('Posts', 'posts.Post', '=', 'Posts.id')
            ->where('posts.Post', '=', $id)
            ->get();
        return new PostResource($query);
    }
}
