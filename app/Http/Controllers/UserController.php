<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

use Illuminate\Http\Request;

class UserController extends Controller
{
     private $paginate = 10;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new UserCollection(User::paginate($this->paginate));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        
        $user->save();

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->only($this->getUpdateFields($request)));

        return new UserResource($user);
    }

    /**
     * return fields to update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Array
     */
    private function getUpdateFields($request)
    {
        $update_fields = ['name', 'email'];

        if ($request->password)
            $update_fields[] = 'password';

        return $update_fields;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
     public function destroy(User $user)
    {
        $user->delete();
        
        return response()->json([
            'message' => 'user deleted!'
        ], 204);
    }

}
