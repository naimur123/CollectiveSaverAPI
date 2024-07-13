<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FundResources;
use App\Models\Fund;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FundController extends Controller
{
        /* Get Model */
        public function getModel(){
            return new Fund();
        }

        /* Get Fund */
        public function index(Request $request){
            try{

                $fund = Fund::find($request->id);
                $this->data = new FundResources($fund);
                $this->apiSuccess("Fund Loaded Successfully");
                return $this->apiOutput();

            }catch(Exception $e){
                return $this->apiOutput($this->getError($e), 500);
            }
        }

        /* Create/Edit Fund */
        public function store_fund(Request $request){
            try{
                $validator = Validator::make($request->all(), [
                    "fund_info"  => ["required"]
                ]);
                if($validator->fails()){
                    return $this->apiOutput($this->getValidationError($validator), 400);
                }
                try{
                    $fund_info = [];
                    $totalAmount = 0;
                    if (!empty($request->fund_info)) {
                        $decodedFundInfo = json_decode($request->fund_info, true);
                        if (is_array($decodedFundInfo)) {
                            $fund_info = array_merge($fund_info, $decodedFundInfo);
                        }

                        foreach ($decodedFundInfo as $info) {
                            if (isset($info['amount'])) {
                                $totalAmount += $info['amount'];
                            }
                        }
                    }

                    DB::beginTransaction();
                    if(!empty($request->id)){
                        $data = Fund::find($request->id);
                    }
                    else{
                        $data = $this->getModel();
                    }

                    if(!isset($request->group_id) || empty($request->group_id)){
                        $data->user_id =  $request->user()->id;
                    }
                    else{
                        $data->group_id = $request->group_id;
                    }

                    $data->year             = date('Y');
                    $data->month            = date('m');
                    $data->day              = date('d');
                    $data->fund_info        = json_encode($fund_info);
                    $data->interest_amount  = $request->interest_amount;
                    $data->total_amount     = $totalAmount;

                    $data->save();
                    DB::commit();

                }catch(Exception $e){
                    return $this->apiOutput($this->getError( $e), 500);
                    DB::rollBack();
                }
                if(!empty($request->id))  $this->apiSuccess("Fund Updated Successfully");
                else $this->apiSuccess("Fund Created Successfully");
                $this->data = (new FundResources($data));
                return $this->apiOutput();

            }catch(Exception $e){
                return $this->apiOutput($this->getError($e), 500);
            }
        }

}
