<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $art = Comment::all();
        return CommentResource::collection($art);
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
            'author' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        $art = Comment::create($data);

        return response(['message' => 'Created Comment successfully', 'Comment' => new CommentResource($art)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Comment = Comment::find($id);
        if ($Comment) {
            return new CommentResource($Comment);
        }
        return "Comment Not found";
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
        $art = Comment::find($id);
        $art->update($request->all());
        return response(['message' => 'Update Comment successfully', 'Comment' => new CommentResource($art)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)

    {
        $art = Comment::findOrfail($id);
        if ($art->delete()) {
            return response(['message' => 'Comment Deleted successfully']);
        }
        return "Error while deleting";
    }


    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function CommentComments($id)
    {
        $query = DB::table('Comments')
            ->join('Comments', 'Comments.Comment', '=', 'Comments.id')
            ->where('Comments.Comment', '=', $id)
            ->get();
        return new CommentResource($query);
    }
}
