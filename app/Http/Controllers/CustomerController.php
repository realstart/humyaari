<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Customer;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\InvoiceCollection;
use App\Model\Invoice;

class CustomerController extends Controller
{

    
    public function search(Request $request) {
        return response()->json([
            'customers' => Customer::where('name', 'like', '%' . $request->name . '%')->limit(20)->get()
        ]);
    }


    public function make(Request $request)
    {
        # code...
        $inputs = $request->all();

        $inputs['name'] = $inputs['name'];
        $inputs['phone'] = $inputs['phone'];
        $inputs['email'] = $inputs['email'];
        $inputs['address1'] = $inputs['address1'];
        $inputs['address2'] = $inputs['address2'];
        // if( $inputs['address2'] == ''){

        //     $inputs['address2'] = null;
        // }else{
        //     $inputs['address2'] = $inputs['address2'];
        // }
        $inputs['customer_status'] = "active";
        


        $customer = Customer::create($inputs);

        return response()->json([
            'customer' => $customer
        ]);
    }

    public function show()
    {
       
        return new CustomerCollection(Customer::where('customer_status','active')->latest()->paginate(10));    
       
    }
    public function search_customer($field,$query)
    {
        # code...
        return new CustomerCollection(Customer::where($field,'LIKE',"%$query%")->where('customer_status','active')->orderBy('id','DESC')->paginate(10));
    }
    public function customer_report(Request $request)
    {
        # code...
      

        return new InvoiceCollection(Customer::select('customers.*')->join('invoices','invoices.customer_id','=','customers.id')->groupBy('customers.id','invoices.customer_id','customers.name','customers.email','customers.phone','customers.address1')->orderBy('customers.id','DESC')->paginate(10));

    }
    public function user_invoice_report(Request $request, $id)
    {
        # code...
      

        return new InvoiceCollection(Invoice::select('*')->where('invoice_created_by','=',$id)->orderBy('id','DESC')->paginate(10));

    }

    public function customer_detils_with_invoice($customer_id)
    {
        # code...
        $basic_info = Customer::where('id', '=', $customer_id)->get();
        $total_purchase =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_purchase FROM invoices WHERE customer_id = $customer_id"));

        $total_paid =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_paid FROM invoices WHERE customer_id = '$customer_id' AND status = 'paid'"));

        $total_due =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_due FROM invoices WHERE customer_id = '$customer_id' AND status = 'due'"));

        return response()->json([
            'info' => $basic_info,
            'total_purchase' => $total_purchase,
            'total_paid' => $total_paid,
            'total_due' => $total_due,
        ]);
       
    }
    public function customers_invoice($customer_id)
    {
        # code...
        return new InvoiceCollection(Customer::select('invoices.invoice_no','invoices.id','invoices.status','invoices.invoice_date')->join('invoices','invoices.customer_id','=','customers.id')->where('invoices.customer_id','=',$customer_id)->where('invoices.invoices_status','active')->orderBy('invoices.invoice_date','DESC')->paginate(1000)); 

    }

    public function soft_delete_customer($id)
    {
        # code...
        $status = "deactive";
        DB::update('update customers set customer_status = ? where id = ?',[$status,$id]);
        return response()->json("Done");
    }
    public function edit_customer(Request $request, $id)
    {
        # code...
        $request = Customer::find($id);

        return response()->json([
            'data' => $request
        ]);
       
    }
    public function update_customer_info(Request $request)
    {
        # code...
            
        $id = $request->post('id');

        $CustomerData = [

            'name' => $request->post('name'),
            'address1' => $request->post('address1'),
            'address2' => $request->post('address2'),
            'phone' => $request->post('phone'),
            'email' => $request->post('email'),

        ];
        $updateOrder = Customer::find($id)->update($CustomerData);
       
    }
    public function check_customer_email(Request $request, $email)
    {
        # code...
       // $email = $request->post('email');
        $res =  DB::select( DB::raw("SELECT * FROM customers WHERE email = '$email' "));


        if($res != null){

            return response()->json([
                'data' => true
            ]);

        }else{

            return response()->json([
                'data' => false
            ]);
        }

    }

    
}
