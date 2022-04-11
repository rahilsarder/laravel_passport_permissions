<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole(['Super Admin', 'Write'])) {
            return response()->json(User::all());
        }
        return response()->json([
            'message' => 'You are not authorized to access this resource',
        ], 403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);



        if (!$user) {
            return response()->json([
                'message' => 'Invalid Credentials',
            ]);
        }


        $user = Auth::user();


        /** @var \App\Models\User $user */

        $accessToken = $user->createToken('authToken')->plainTextToken;


        return response([
            'user' => $user, 'access_token' => $accessToken
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password'])

        ]);

        $accessToken = $user->createToken('authToken')->plainTextToken;

        return response(['user' => $user, 'access_token' => $accessToken]);
    }



    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'role' => 'required'
        ]);

        $user = User::find($request->user_id);

        $user->assignRole($request->role);

        return response()->json($user);
    }


    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->hasRole(['Super Admin', 'Write']))
            return response()->json([
                'message' => 'You are not authorized to access this resource',
            ], 403);

        $deleteUser = User::find($id);

        if ($deleteUser == null)
            return response()->json([
                'message' => 'User not found',
            ], 404);

        $deleteUser->delete();

        return response()->json([
            'message' => 'The user has been deleted Successfully',
        ]);
    }
}
