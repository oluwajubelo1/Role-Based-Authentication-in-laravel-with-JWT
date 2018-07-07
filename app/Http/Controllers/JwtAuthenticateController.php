<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class JwtAuthenticateController extends Controller
{
    public function index(){
        return response()->json([
            'auth'=>Auth::user(),
            'users'=>User::all()
        ]);
    }

    public function authenticate(Request $request){
        $credentials=$request->only('email','password');
        try{
            // verify the credentials and create a token for the user
            if (!$token=JWTAuth::attempt($credentials)){
                return response()->json([
                    'error'=>'invalid credentials'
                ],JsonResponse::HTTP_UNAUTHORIZED);
            }
        }catch (JWTException $e){
            // something went wrong
            return response()->json([
                'error'=>'could not create token'
            ],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        // if no errors are encountered we can return a JWT
        return response()->json([
            'token'=>$token
        ],JsonResponse::HTTP_OK);
    }

    public function createRole(Request $request){
        $role=new Role;
        $role->name=$request->input('name'); // name of the new role
        $role->save();

        return response()->json([
            'success'=> $role->name.' Role created successfully'
        ],JsonResponse::HTTP_OK);
    }

    public function createPermission(Request $request){
    // to create permission, NB: kindly do some protective checking before saving, visit the Entrust documentation
        // for more available options
        $viewUsers=new Permission;
        $viewUsers->name=$request->input('name');
        $viewUsers->save();

        return response()->json([
            'success'=> $viewUsers->name.' Permission saved'
        ],JsonResponse::HTTP_OK);
    }

    public function assignRole(Request $request){
        // responsible for assigning a given role to a user.
        // It needs a role ID and a user object
        $user=User::whereEmail($request->input('email'))->first();
        $role=Role::where('name',$request->input('role'))->first();
        $user->roles()->attach($role->id);

        return response()->json([
            'success'=>'Role successfully assigned'
        ],JsonResponse::HTTP_OK);
    }

    public function attachPermission(Request $request){
        // adds permission to a role
        $role=Role::where('name',$request->input('role'))->first();
        $permission=Permission::where('name',$request->input('name'))->first();
        $role->attachPermission($permission);

        return response()->json([
            'success'=>'Permission added to role'
        ],JsonResponse::HTTP_OK);
    }
}
