<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Vendor;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\VendorCollection;

class VendorController extends Controller
{
    public function all() {
        return response()->json([
            'vendors' => Vendor::all()->where('vendor_status','active')
        ]);
    }

    public function make(Request $request)
    {
        //

        $inputs = $request->all();

       
        $inputs['name'] = $inputs['name'];
        $inputs['phone'] = $inputs['phone'];
        $inputs['email'] = $inputs['email'];
        $inputs['contact_person'] = $inputs['contact_person'];
        $inputs['address'] = $inputs['address'];
        $inputs['vendor_status'] = 'active';

        if ($request->hasFile('logo')) {

            $file = $request->file('logo');
            $filename =  $inputs['name']. '.' . $file->getClientOriginalExtension();

            $directoryName = public_path("uploads/vendors/");
            
            if (!file_exists($directoryName)) {
                mkdir($directoryName);
            }

            $file->move($directoryName, $filename);
        }else{

            $filename = "";
        }

        $inputs['logo'] = $filename;

        $vendor = Vendor::create($inputs);

        return response()->json(null, 200);
    }
    public function show()
    {
        
        return new VendorCollection(Vendor::where('vendor_status','=','active')->latest()->paginate(10));    
       
    }
    public function search_vendor($field,$query)
    {
        # code...
        return new VendorCollection(Vendor::where($field,'LIKE',"%$query%")->orderBy('id','DESC')->paginate(10));
    }
    public function soft_delete_vendor($id)
    {
        # code..

        $status = "deactive";

        DB::update('update vendors set vendor_status = ? where id = ?',[$status,$id]);
        
        return response()->json("Done");
        
    }

    public function edit_vendor(Request $request, $id)
    {
        # code...
        $request = Vendor::find($id);

        return response()->json([
            'data' => $request
        ]);
       
    }
    public function get_single_vendor_details(Request $request, $vendor_id)
    {
        # code...
        $request = Vendor::find($vendor_id);

        return response()->json([
            'data' => $request
        ]);
       
    }
    public function update_vendor_info(Request $request)
    {
        # code...
            
        $id = $request->post('id');

        $default_logo_name = $request->post('hiddenLogo');

        if ($request->hasFile('logo')) 
        {

            $file = $request->file('logo');
            $filename =  $request->post('name'). '.' . $file->getClientOriginalExtension();

            $directoryName = public_path("uploads/vendors/");
            
            if (!file_exists($directoryName)) {
                mkdir($directoryName);
            }

            $file->move($directoryName, $filename);

        }else{

            $filename = $default_logo_name;
        }

        // return response()->json([
        //     'data' => $default_logo_name
        // ]);

        $CustomerData = [

            'name' => $request->post('name'),
            'address' => $request->post('address'),
            'company' => $request->post('company'),
            'contact_person' => $request->post('contact_person'),
            'phone' => $request->post('phone'),
            'email' => $request->post('email'),
            'logo' => $filename,


        ];
        $updateOrder = Vendor::find($id)->update($CustomerData);
        return response()->json([
            'data' => $updateOrder
        ]);
    }
    public function vendor_report()
    {
        # code...
        return new VendorCollection(Vendor::select('vendors.*')->join('invoices','invoices.vendor_id','=','vendors.id')->groupBy('vendors.id','invoices.vendor_id','vendors.name')->orderBy('vendors.id','DESC')->paginate(10));
    }
    public function vendor_name()
    {
        # code...
        $vendor_names =  DB::select( DB::raw("SELECT  id,name FROM vendors WHERE vendor_status='active'"));
        return response()->json([

            'vendor_names' => $vendor_names,
            
        ]);
    }
  
    public function vendor_detils_with_invoice($vendor_id)
    {
        # code...
        $basic_info = Vendor::where('id', '=', $vendor_id)->get();
        $total_purchase =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_purchase FROM invoices WHERE customer_id = $vendor_id"));

        $total_paid =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_paid FROM invoices WHERE customer_id = '$vendor_id' AND status = 'paid'"));

        $total_due =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_due FROM invoices WHERE customer_id = '$vendor_id' AND status = 'due'"));

        return response()->json([
            'info' => $basic_info,
            'total_purchase' => $total_purchase,
            'total_paid' => $total_paid,
            'total_due' => $total_due,
        ]);
    }
    
    public function vendor_gds(Request $request, $vendor_id)
    {
        # code...
        $total_invoices =  DB::select( DB::raw("SELECT  COUNT(invoice_no) AS total_invoice FROM invoices WHERE vendor_id = $vendor_id AND  flag='GDS'"));

        $total_amount =  DB::select( DB::raw("SELECT  SUM(airline_payment) AS total_amount FROM invoices WHERE vendor_id = '$vendor_id' AND 
        flag='GDS'"));

        $total_paid =  DB::select( DB::raw("SELECT  SUM(airline_payment) AS total_paid FROM invoices WHERE vendor_id = '$vendor_id' AND status = 'paid' AND flag='GDS'"));

        $total_due =  DB::select( DB::raw("SELECT  SUM(airline_payment) AS total_due FROM invoices WHERE vendor_id = '$vendor_id' AND status = 'due' AND flag='GDS'"));

        $total_partial =  DB::select( DB::raw("SELECT  SUM(airline_payment) AS total_due FROM invoices WHERE vendor_id = '$vendor_id' AND status = 'partial' AND flag='GDS'"));

        return response()->json([
           
            'total_invoice' => $total_invoices,
            'total_amount' => $total_amount,
            'total_paid' => $total_paid,
            'total_due' => $total_due,
            'total_partial' => $total_partial,
        ]);
    }
    public function vendor_service(Request $request, $vendor_id)
    {
        # code...
        $total_invoices =  DB::select( DB::raw("SELECT  COUNT(invoice_no) AS total_invoice FROM invoices WHERE vendor_id = $vendor_id AND  flag='Service'"));

        $total_amount =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_amount FROM invoices WHERE vendor_id = '$vendor_id' AND 
        flag='Service'"));

        $total_paid =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_paid FROM invoices WHERE vendor_id = '$vendor_id' AND status = 'paid' AND flag='Service'"));

        $total_due =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_due FROM invoices WHERE vendor_id = '$vendor_id' AND status = 'due' AND flag='Service'"));

        $total_partial =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_due FROM invoices WHERE vendor_id = '$vendor_id' AND status = 'partial' AND flag='Service'"));

        return response()->json([
           
            'total_invoice' => $total_invoices,
            'total_amount' => $total_amount,
            'total_paid' => $total_paid,
            'total_due' => $total_due,
            'total_partial' => $total_partial,
        ]);
    }
    public function vendor_third_party(Request $request, $vendor_id)
    {
        # code...
        $total_invoices =  DB::select( DB::raw("SELECT  COUNT(invoice_no) AS total_invoice FROM invoices WHERE vendor_id = $vendor_id AND  flag='third party'"));

        $total_amount =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_amount FROM invoices WHERE vendor_id = '$vendor_id' AND 
        flag='third party'"));

        $total_paid =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_paid FROM invoices WHERE vendor_id = '$vendor_id' AND status = 'paid' AND flag='third party'"));

        $total_due =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_due FROM invoices WHERE vendor_id = '$vendor_id' AND status = 'due' AND flag='third party'"));

        $total_partial =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS total_due FROM invoices WHERE vendor_id = '$vendor_id' AND status = 'partial' AND flag='third party'"));

        return response()->json([
           
            'total_invoice' => $total_invoices,
            'total_amount' => $total_amount,
            'total_paid' => $total_paid,
            'total_due' => $total_due,
            'total_partial' => $total_partial,
        ]);
    }
   
}
