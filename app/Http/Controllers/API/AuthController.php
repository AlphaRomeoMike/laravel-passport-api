<?php

namespace App\Http\Controllers\API;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * Register Function
     *
     * @author Abdul Rafay Modi
     * @category Authentication
     * @param Request $request
     * @return Response::json
     * @method POST
     */
    public function Register(Request $request)
    {
        $data = $request->validate([
            'name'      =>  ['required', 'string'],
            'email'     =>  ['required', 'email', 'unique:users,email'],
            'password'  =>  ['required', 'alpha_num', 'min:5', 'confirmed']
        ]);


        try 
        {
            $data['password']       = Hash::make($request->password);
            $user                   = User::Create($data);
            $token                  = $user->createToken('authtoken')->accessToken;

            if($request->password != 0 || $request->email != null)
            {
                return response()->json([
                    'data'          =>  $user,
                    'token'         =>  $token,
                    'msg'           =>  'User was created',
                    'success'       =>  true
                ], 201);
            }
            else
            {
                return response()->json([
                    'data'          =>  [],
                    'token'         =>  null,
                    'msg'           =>  'User was not created',
                    'success'       =>  false
                ], 418);
            }
        } 
        catch (Throwable $e) 
        {
            report($e);
        }
    }

    /**
     * Login Function
     *
     * @author Abdul Rafay Modi
     * @category Authentication
     * @param Request $request
     * @return Response::json
     * @method POST
     */
    public function Login(Request $request)
    {
        $data = $request->validate([
            'email'         =>  ['required', 'string'],
            'password'      =>  ['required', 'alpha_num', 'min:5']
        ]);

        try 
        {
            if($data['email'] != null || $data['password'] != null)
            {
                $user = User::Where('email', $data['email'])->first();

                if($user != null)
                {
                    dd($token = $user->createToken('authtoken')->accessToken);
                    if(Hash::check($data['password'], $user->password))
                    {
                        return response()->json([
                            'data'          =>  $user,
                            'token'         =>  $token,
                            'msg'           =>  'User was retrieved',
                            'success'       =>  true
                        ], 202);
                    }
                    else
                    {
                        return response()->json([
                            'data'          =>  [],
                            'token'         =>  null,
                            'msg'           =>  'Email or password do not match',
                            'success'       =>  true
                        ], 401);
                    }
                }
                else
                {
                    return response()->json([
                        'data'          =>  [],
                        'token'         =>  $token,
                        'msg'           =>  'No such user exists',
                        'success'       =>  true
                    ], 302);
                }
            }
        } 
        catch (Throwable $e) 
        {
            report($e);
        }
    }

    /**
     * Register Function
     *
     * @author Abdul Rafay Modi
     * @category Authentication
     * @param Request $request
     * @return Response::json
     * @method POST
     */
    public function Logout(Request $request)
    {
        $token  = $request->user()->token();
        $token->revoke();

        return response()->json([
            'msg'           =>  'You have been logged out',
            'success'       =>  true
        ], 200);
    }
}
