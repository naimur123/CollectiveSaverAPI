<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResources;
use App\Http\Resources\UserResources;
use App\Models\Groups;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /* Get Model */
    public function getModel(){
        return new Groups();
    }

    /* Get Group */
    public function index(Request $request){
        try{
            if(!empty($request->id)){
                $groups = Groups::find($request->id);
                $this->data = new GroupResources($groups);
            }
            else{
                $user = User::with('user_groups')->find($request->user()->id);
                $this->data = new UserResources($user);
            }
            $this->apiSuccess("Groups Loaded Successfully");
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError($e), 500);
        }
    }

    /* Create Group */
    public function create_group(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                "name"     => ["required", "string", "min:4", "max:40"],
                "members"  => ["required"]
            ]);
            if($validator->fails()){
                return $this->apiOutput($this->getValidationError($validator), 400);
            }
            try{
                $membrs = [];
                if (!empty($request->members)) {
                    $decodedMembers = json_decode($request->members, true);
                    if (is_array($decodedMembers)) {
                        $members = array_merge($membrs, $decodedMembers);
                    }
                }

                DB::beginTransaction();
                $data = $this->getModel();
                $data->user_id               = $request->user()->id;
                $data->name                  = $request->name;
                $data->account_type          = $request->account_type;
                $data->account_name          = $request->account_name;
                $data->account_number        = $request->account_number;
                $data->members               = json_encode($members);
                $data->details               = $request->details;
                $data->image                 = $request->image;

                $data->save();
                DB::commit();

            }catch(Exception $e){
                return $this->apiOutput($this->getError( $e), 500);
                DB::rollBack();
            }
            $this->apiSuccess("Group Created Successfully");
            $this->data = (new GroupResources($data));
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError($e), 500);
        }
    }
}
