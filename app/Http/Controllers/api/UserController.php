<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use Illuminate\Validation\Rule;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        try {
            $users = User::get();
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 404);
        }

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users',
                'name' => 'required|string',
                'email' => 'required|string|unique:users',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $hashed_random_password = Hash::make($request->password);

            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => $hashed_random_password]
            ));

        } catch (\Throwable $th) {
            throw $th;
        }


        return response()->json([
            'message' => 'Register success',
            'user' => $user
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'No result'], 404);
        }

        return response()->json($user);
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
        try {
            $user = User::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'username' => ['string', Rule::unique('users')->ignore($id),],
                'name' => 'string',
                'email' => ['string', Rule::unique('users')->ignore($id),],
                'password' => 'string|min:8',
            ]);

            $user->fill($request->all())->save();

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'No result'], 404);
        }
        return response()->json([
            'message' => 'User updated',
            'user' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();

        } catch (\Throwable $th) {
            return response()->json(['message' => 'No result'], 404);
        }
        return response()->json([
            'message' => 'User deleted',
        ], 200);
    }
}
