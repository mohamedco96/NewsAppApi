<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    public function auth(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }
        // register
        if (!auth()->attempt($data)) {
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $accessToken = $user->createToken('authToken')->accessToken;
            return response(['message' => 'Register successfully','user' => $user, 'access_token' => $accessToken]);
        }

        //login
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        $user = User::find(auth()->user()->id);
        // $user->status = 'online';
        // $user->update();
        return response(['message' => 'Login successfully','user' => auth()->user(), 'access_token' => $accessToken]);
    }

    public function logout(Request $request)
    {
        $userInfo = auth('api')->user();
        if ($userInfo !== null) {
            $user = User::find($userInfo->id);
            $user->status = 'offline';
            $user->update();
            return "User is logout successfully";
        } else {
            return "User is not logged in.";
        }
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
        $data['password'] = bcrypt($request->password);
        $validator = Validator::make($data, [
            'email' => 'required|unique:users',
            'password' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $User = User::create($data);

        return response(['message' => 'Created User successfully', 'user' => new UserResource($User)], 200);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = QueryBuilder::for(User::class)
            ->allowedFilters([
                'name',
                AllowedFilter::exact('email'),
            ])
            ->paginate()
            ->appends(request()->query());

        return UserResource::collection($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return new UserResource($user);
        }
        return "user Not found";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $validator = Validator::make($data, [
            'email' => 'email'
        ]);


        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $user->update($data);

        return response(['user' => new UserResource($user), 'message' => 'Update User successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrfail($id);
        if ($user->delete()) {
            return response(['message' => 'User Deleted successfully']);
        }
        return response(['message' => 'Error while deleting']);
        // return "Error while deleting";
    }

}
