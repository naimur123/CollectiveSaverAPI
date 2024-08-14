<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SystemResources;
use App\Models\System;
use Exception;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function index(Request $request){
        try{
            $system = System::where('is_active', 1)->first();
            $this->data = new SystemResources($system);
            $this->apiSuccess("System Loaded Successfully");
            return $this->apiOutput();

        }catch(Exception $e){
            return $this->apiOutput($this->getError($e), 500);
        }
    }
}
