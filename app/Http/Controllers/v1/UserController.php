<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use App\Models\ActivityLog;
use App\Models\AuditTrail;
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

    /* Get User */

    public function index(Request $request){
        try{
            $user = User::find($request->id);
            $this->data = new UserResources($user);
            $this->apiSuccess("User Loaded Successfully");
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError($e), 500);
        }
    }

    /* User Registration */
    public function register(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                "name"     => ["required", "string", "min:4", "max:40"],
                "phone"     => ["required", "string", "min:11", "max:11"],
                "password"  => ["required", "string", "min:4", "max:40"]
            ]);
            if($validator->fails()){
                return $this->apiOutput($this->getValidationError($validator), 400);
            }
            /* Check phone number exist or not */
            $check_phone = User::where('phone', $request->phone)->get();
            if(!empty($check_phone)){
                return $this->apiOutput('Phone number already exists', 409);
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
            $this->apiSuccess("Successfully Registered");
            // $this->data = (new UserResources($data));
            return $this->apiOutput();
            /* Automatically login if registered */
            // return $this->login($request);

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
        $user = auth('sanctum')->user();
        foreach ($user->tokens as $token) {
            $token->delete();
        }
        $this->apiSuccess("Logout Successfull");
        return $this->apiOutput();

    }


    function saveActivity(Request $request){
        if(isset($request->for) && $request->for == 'audit_trail'){
            $ip = $request->ip(); //182.160.104.68
            if (env('APP_ENV') === 'local') {
                $ip = '103.136.230.12';
            }
            $response = user_location($ip);

            $data = new AuditTrail();
            $data->user_id = $request->user()->id;
            $data->url = $request->url;
            $data->method = $request->method;
            // $data->url = $request->fullUrl();
            // $data->method = $request->method();
            $data->ip = $request->ip();
            $data->location = $response;
            $data->user_agent = $request->user_agent;
            $data->save();

        }
        else{
            $model = getModelInstance($request->model);
            $tableName = $model ? $model->getTable() : null;

            $data = new ActivityLog();
            $data->user_id = $request->user()->id;
            $data->ip = $request->ip();
            $data->activity = $request->message;
            $data->effect_table = $tableName;
            $data->save();

        }
    }

}
