<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /* Get Model */
    public function getModel(){
        return new User();
    }

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
}
