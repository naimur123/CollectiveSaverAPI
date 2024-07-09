<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /* Get Model */
    public function getModel(){
        return new User();
    }

    /* test purpose */
    public function testApiResult(){
        return $this->apiOutput('Test result Successfull');
    }

    /* User Registration */
    public function register(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                "name"     => ["required", "string", "min:4", "max:40"],
                "phone"     => ["required", "string", "min:4", "max:40"],
                "password"  => ["required", "string", "min:4", "max:40"]
            ]);
            if($validator->fails()){
                return $this->apiOutput($this->getValidationError($validator), 400);
            }
            try{
                DB::beginTransaction();
                $data = $this->getModel();
                $data->name = $request->name;
                $data->phone = $request->phone;
                $data->password = bcrypt($request->password);
                $data->save();
                DB::commit();

            }catch(Exception $e){
                return $this->apiOutput($this->getError( $e), 500);
                DB::rollBack();
            }
            $this->apiSuccess("User Added Successfully");
            $this->data = (new UserResources($data));
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError( $e), 500);
        }

    }

    /* User Login */
    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                "phone"     => ["required", "string", "min:11", "max:11"],
                "password"  => ["required", "string", "min:4", "max:40"]
            ]); 
            if($validator->fails()){
                return $this->apiOutput($this->getValidationError($validator), 400);
            }
            $user = User::where("phone", $request->phone)->first();
            if( empty($user) ){
                return $this->apiOutput("User not found", 401);
            }
            if( !Hash::check($request->password, $user->password) ){
                return $this->apiOutput("Sorry! Password Dosen't Match", 401);
            }
            if($user->is_active != 1){
                return $this->apiOutput("Sorry! your account is inactive", 401);
            }
            // Issueing Access Token
            $this->access_token = $user->createToken($request->ip() ?? "user_access_token")->plainTextToken;
            Session::put('access_token',$this->access_token);
            $this->apiSuccess("Login Successfully", $user);
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError($e), 500);
        }
    }

    public function logout(Request $request){
        
        // Session::flush('access_token');
        // // $user = $request->user();
        // // $request->user()->access_token->delete();
        // $this->apiSuccess("Logout Successfull");
        // return $this->apiOutput();
        // $user = auth('sanctum')->user();
        $user = $request->user()->tokens();
        $this->data = $user;
        // 
    //     foreach ($user->tokens as $token) {
    //         $token->delete();
    //    }
    //    $this->apiSuccess("Logout Successfull");
       return $this->apiOutput();
   
    }
}
