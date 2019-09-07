<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Banking;
use App\Model\Banks;
use App\Http\Resources\BankCollection;
use Illuminate\Support\Facades\DB;

class BankingController extends Controller
{
    
    public function make(Request $request)
    {
        # code...
        $inputs = $request->all();

        $inputs['ac_holder_name'] = $inputs['ac_holder_name']; 
        $inputs['ac_no'] = $inputs['ac_no'];
        $inputs['bank_id'] = $inputs['bank_id'];
        $inputs['ac_type'] = $inputs['ac_type'];
        $inputs['opening_balance'] = $inputs['opening_balance'];
        
       
        $banking = Banking::create($inputs);

        return response()->json([
            'banking' => $banking
        ]);
    }
    public function make_new_bank(Request $request)
    {
        # code...
        $inputs = $request->all();

        // $inputs['bank_name'] = $inputs['bank_name']; 

        // if($inputs['bank_details'] == ''){

        //     $inputs['bank_details'] = " ";
        // }else{

        //     $inputs['bank_details'] = $inputs['bank_details'];
        // }
       
        
       
        $banking = Banks::create($inputs);

        return response()->json([
            'banking' => $banking
        ]);
    }
    public function show()
    {
        # code...
        return new BankCollection(Banks::latest()->paginate(10));    
    }
    public function edit_bank($id)
    {
        # code...
        $request = Banks::find($id);

        return response()->json([

            'data' => $request
        ]);

    }
    public function update_bank_info(Request $request) // for super admin only
    {
        # code...
        $id = $request->post('id');
        $BankData = [


            'bank_name' => $request->post('bank_name'),
            'bank_details' => $request->post('bank_details'),
   

        ];
        $res = Banks::find($id)->update($BankData);
        return response()->json([

            'data' => $res
        ]);

    }
    public function delete_bank($id)
    {
        # code...
        $bank = Banks::findOrFail($id);

        $bank->delete();
    
        return response()->json([

            'data' => $bank
        ]);
    }
    public function all_bank_list()
    {
        # code...
        $banks = DB::select(DB::raw("SELECT * FROM banks "));
        return response()->json([
            'data' => $banks
        ]);


    }

}
