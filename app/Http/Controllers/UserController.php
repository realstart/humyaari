<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Http\Resources\UserCollection;
use App\Http\Resources\InvoiceCollection;
use App\Model\Invoice;

class UserController extends Controller
{
    //
    public function check_existing_user($email)
    {
        # code...
        $check = DB::select(DB::raw("SELECT * FROM users WHERE email = '$email'"));
        if(!empty($check)){

            return response()->json([

                'data' => "yes"
            ]);

        }else{

            return response()->json([

                'data' => "no"
            ]); 
        }
    }
    public function make(Request $request)
    {
        # code...
        $inputs = $request->all();

        $inputs['name'] = $inputs['name'];
        $inputs['email'] = $inputs['email'];
        $inputs['password'] = bcrypt($inputs['password']);
        $inputs['role'] = $inputs['role'];
        $inputs['firstname'] = $inputs['firstname'];
        $inputs['lastname'] = $inputs['lastname'];
        $inputs['phone'] = $inputs['phone'];
        
        $user = User::create($inputs);

        return response()->json([
            'data' => $user
        ]);
    }

    public function delete_user($id)
    {
        # code...
        $user = User::findOrFail($id);

        $user->delete();
    
        return response()->json([

            'data' => $user
        ]);
    }

    public function edit_user($id)
    {
        # code...
        $request = User::find($id);

        return response()->json([

            'data' => $request
        ]);

    }
    public function update_users_info(Request $request) // for super admin only
    {
        # code...
        $id = $request->post('id');
        $UserData = [


            'name' => $request->post('name'),
            'firstname' => $request->post('firstname'),
            'lastname' => $request->post('lastname'),
            'phone' => $request->post('phone'),
            'email' => $request->post('email'),
            'role' => $request->post('role'),

        ];
        $res = User::find($id)->update($UserData);
        return response()->json([

            'data' => $res
        ]);

    }
    public function show()
    {
        # code...
        return new UserCollection(User::latest()->paginate(10));    
    }
    public function getUser($id)
    {
        # code...
        $res = User::findOrFail($id);

        return response()->json([

            'data' => $res
        ]);
    }
    public function update_user_info(Request $request)
    {
        # code...
        $id = $request->post('id');
        $UserData = [


            'name' => $request->post('name'),
            'firstname' => $request->post('firstname'),
            'lastname' => $request->post('lastname'),
            'phone' => $request->post('phone'),

        ];
        $res = User::find($id)->update($UserData);
        return response()->json([

            'data' => $res
        ]);
    }
    public function check_user_password($id,$password)
    {
        # code...
        $hashPassword = bcrypt($password);
        return response()->json([

            'data' => $hashPassword
        ]);
        
    }
    public function update_user_password(Request $request)
    {
        # code...
        $id = $request->post('id');
        $password = $request->post('password');

        $UserData = [

            'password' => bcrypt($password),
        ];
        $res = User::find($id)->update($UserData);
        return response()->json([

            'data' => $res
        ]);
    }
    public function search_invoice_for_user($field,$query,$currentUserID)
    {
        # code...
        return new InvoiceCollection(Invoice::where('invoice_created_by ',$currentUserID)->where($field,'LIKE',"%$query%",'')->orderBy('id','DESC')->paginate(10));
    }
}
