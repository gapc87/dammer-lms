<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', \request()->user()->id)->get();

        $users->load('role');

        return response()->json([
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role
        ]);

        return response()->json([
            'data' => $user
        ]);
    }

    public function update(User $user, Request $request)
    {
        if ($request->has('password')) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        } else {
            $user->update($request->all());
        }

        return response()->json([
            'data' => $user
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'data' => $user
        ]);
    }
}
