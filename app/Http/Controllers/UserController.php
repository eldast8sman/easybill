<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('firstname', 'asc')->orderBy('lastname', 'asc')->paginate(10);

        return response([
            'status' => 'success',
            'message' => 'Users fetched successfully',
            'data' => UserResource::collection($users)->response()->getData(true)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        if(!$user = User::create($request->all())){
            return response([
                'status' => 'failed',
                'message' => 'User creation Failed'
            ], 409);
        }

        return response([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => new UserResource($user)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response([
            'status' => 'success',
            'message' => 'Uer fetched successfully',
            'data' => new UserResource($user)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        if(empty($user)){
            return response([
                'status' => 'failed',
                'message' => 'No User was fetched'
            ], 404);
        }

        $user->update($request->all());

        return response([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => new UserResource($user)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ], 200);
    }
}
