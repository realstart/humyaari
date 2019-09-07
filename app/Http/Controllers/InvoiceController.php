<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;
use App\Model\Invoice;
use App\Model\InvoiceItem;
use App\Model\Customer;
use App\Model\Vendor;
use Dompdf\Dompdf;
use DB;
use App\Http\Resources\InvoiceCollection;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Mail;
use function GuzzleHttp\json_decode;
use App\Http\Resources\VendorCollection;
use App\Model\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class InvoiceController extends Controller
{
    public function make(Request $request)
    {
        $inputs = $request->all();

        $inputs['invoice_date'] = date('Y-m-d', strtotime($inputs['invoice_date']));
        $inputs['invoice_due_date'] = date('Y-m-d', strtotime($inputs['invoice_due_date']));
        $inputs['invoice_created_by'] = $inputs['currentUserID'];
        $inputs['invoices_status'] = "active";
        $inputs['partial_payment'] = 0.00;
        $inputs['customer']['customer_status'] = 'active';
        
        // return response()->json([
        //     'data' =>  $inputs
        // ]);
        $CustomerEmail = $inputs['customer']['email'];
       
   
        $customer_id =  DB::select( DB::raw("SELECT id FROM customers WHERE email = '$CustomerEmail' "));

        $getCustomerID = 0;

        foreach($customer_id as $c){

            $getCustomerID = $c->id;
        }

        
    //     return response()->json([

    //        'data' => $customer_id
    //    ]);

        if(empty($customer_id)){

            $customer = Customer::create($inputs['customer']);

        }
        // if (!array_key_exists('id', $inputs['customer'])) {

        //     $customer = Customer::create($inputs['customer']);

        else{

            $customer = Customer::find($getCustomerID);
        }


        if ($inputs['edit'] === false) {
            $invoice = Invoice::create($inputs);
        } else {
            $invoice = Invoice::where('invoice_no', $inputs['invoice_no'])->first();
            $invoice->fill($inputs);
            $invoice->save();
            $invoice->items()->delete();
        }

        $invoice->customer()->associate($customer);
        $invoice->vendor()->associate(Vendor::find($inputs['vendor_id']));
        $invoice->save();

        if (count($inputs['products'])) {
            $invoice->items()->createMany($inputs['products']);
        }

        if (count($inputs['services'])) {
            $invoice->items()->createMany($inputs['services']);
        }

        return response()->json([
            'invoice' => $invoice
        ]);

    }
    public function get_last_invoice_no(Request $request)
    {
        # code...
        $last_id =  DB::select( DB::raw('SELECT invoice_no FROM invoices ORDER BY ID DESC LIMIT 1'));
        return response()->json([
            'invoice_id' => $last_id
        ]);
        
    }
    public function makePDF(Request $request) {


        $data = $this->getInvoiceData($request);

        $invoice  = $data['invoice'];
        $products = $data['products'];
        $services = $data['services'];
        
        $company =  DB::table('official_info')->get()->first();
        $admin_id = $invoice->invoice_created_by;
        $admin_name = '';
        $creator_id =  DB::select( DB::raw("SELECT firstname,lastname FROM users where id = '$admin_id' "));
        foreach($creator_id as $c){

            $admin_name = $c->firstname.' '.$c->lastname;
        }
        $data = array(
            'invoice' => $invoice,
            'products' => $products,
            'services' => $services,
            'official' => false,
            'company' => $company,
            'admin' => $admin_name
        );

       
      

        if ($request->print === 'office') {
            $data['official'] = true;
        }

        $dompdf = new Dompdf();
        $dompdf->set_option('defaultFont', 'Arial');
		$dompdf->loadHtml(View::make('invoice.customer', $data));
		$dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = $invoice->invoice_no . '.pdf';

        if ($request->print) {
            return new Response($dompdf->output(), 200, [
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => 'inline; filename="'. $filename . '"',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Type' => 'application/pdf',
            ]);
        }

        return new Response($dompdf->output(), 200, [
			'Content-Description' => 'File Transfer',
			'Content-Disposition' => 'attachment; filename="'. $filename . '"',
			'Content-Transfer-Encoding' => 'binary',
			'Content-Type' => 'application/pdf',
		]);
    }

    public function getInvoice(Request $request) {

        $data = $this->getInvoiceData($request);

        return response()->json([
            'data' => $data
        ]);
    }
    public function count_invoice(Request $request)
    {
        # code...
        $check = DB::select(DB::raw("SELECT COUNT(id) AS Total_IDS FROM invoices"));
        if(!empty($check)){

            return response()->json([

                'data' => $check
            ]);

        }
        
    }
    private function getInvoiceData($request) {
        $invoice = Invoice::where('invoice_no', $request->invoice_no)->with(['customer', 'vendor', 'items'])->first();

        if (!$invoice) {
            return response()->json([
                'status' => 404
            ]);
        }

        $invoice->subtotal = 0;

        $products = $invoice->items->filter(function($item) use ($invoice) {
            $invoice->subtotal = $invoice->subtotal + ($item->quantity * $item->price);
            return $item->type === 'product';
        })->values()->all();

        $services = $invoice->items->filter(function($item) {
            return $item->type === 'service';
        })->values()->all();

        return [
            'invoice'  => $invoice,
            'products' => $products,
            'services' => $services,
        ];
    }

    

    public function show()
    {
        # code...
    
        return new InvoiceCollection(Customer::select('customers.name','customers.phone','invoices.invoice_no','invoices.total_receivable','invoices.status','invoices.flag','invoices.invoice_date','invoices.id')->join('invoices','invoices.customer_id','customers.id')->where('invoices_status','active')->orderBy('invoices.id','DESC')->paginate(10));   
         

        
    }
    public function search_invoice($field,$query)
    {
        # code...
        return new InvoiceCollection(Invoice::select('customers.name','customers.phone','invoices.invoice_no','invoices.total_receivable','invoices.status','invoices.invoice_date','invoices.id')->join('invoices','invoices.customer_id','customers.id')->where($field,'LIKE',"%$query%")->orderBy('invoices.invoice_date','DESC')->paginate(10));


        
    }

    public function view_invoice($invoice_no)
    {
        # code...
        
      
        $invoice = Invoice::where('invoice_no', $invoice_no)->with(['customer', 'vendor', 'items'])->first();

        
            return response()->json([
                'data' => $invoice
            ]);
       

    }

    public function invoice_report($date_from,$date_to,$status)
    {
        # code...

           return new InvoiceCollection(Customer::select('customers.name','customers.phone','invoices.invoice_no','invoices.total_receivable','invoices.status','invoices.invoice_date','invoices.id','invoices.flag')->join('invoices','invoices.customer_id','customers.id')->whereBetween('invoices.invoice_date',array("$date_from","$date_to"))->orderBy('invoices.invoice_date','DESC')->paginate(10));  



    }
    public function invoice_report_vendor($date_from,$date_to,$vendor_id)
    {
        # code...
          return new InvoiceCollection(Customer::select('customers.name','customers.phone','invoices.invoice_no','invoices.total_receivable','invoices.status','invoices.invoice_date','invoices.id','invoices.flag','invoices.airline_payment')->join('invoices','invoices.customer_id','customers.id')->where('invoices.vendor_id',$vendor_id)->whereBetween('invoices.invoice_date',array("$date_from","$date_to"))->orderBy('invoices.invoice_date','DESC')->paginate(10)); 
             
           //return  new InvoiceCollection(Vendor::select('vendors.id','invoices.invoice_no','invoices.airline_payment','invoices.status','invoices.invoice_date','invoices.id','invoice_items.name','invoice_items.route','invoice_items.carrier')->join('invoices','invoices.vendor_id','vendors.id')->join('invoice_items','invoice_items.invoice_id','invoices.id')->whereBetween('invoices.invoice_date',array("$date_from","$date_to"))->where('invoices.invoices_status','active')->where('invoices.vendor_id',"$vendor_id")->orderBy('invoices.id','DESC')->paginate(10));
        //    $data =  new InvoiceCollection(Vendor::select('vendors.*','invoices.invoice_no','invoices.airline_payment','invoices.status','invoices.invoice_date','invoices.id','invoice_items.name','invoice_items.route','invoice_items.carrier')->join('invoices','invoices.vendor_id','vendors.id')->join('invoice_items','invoice_items.invoice_id','invoices.id')->whereBetween('invoices.invoice_date',array("$date_from","$date_to"))->where('invoices.invoices_status','active')->where('invoices.vendor_id',"$vendor_id")->orderBy('invoices.id','DESC')->paginate(10));

   

          

          

          


    }

    public function soft_delete_invoice(Request $request,$id)
    {
        # code...

         $invoices_status = "deactive";
         DB::update('update invoices set invoices_status = ? where invoice_no = ?',[$invoices_status,$id]);
        
         return response()->json("Done");
    }

    public function email_invoice(Request $request)
    {
        # code...
        $invoice = Invoice::where('invoice_no', $request->invoice_no)->with(['customer', 'vendor', 'items'])->first();

        if (!$invoice) {
            return response()->json([
                'status' => 404
            ]);
        }

        $invoice->subtotal = 0;

        $products = $invoice->items->filter(function($item) use ($invoice) {
            $invoice->subtotal = $invoice->subtotal + ($item->quantity * $item->price);
            return $item->type === 'product';
        });

        // dd($invoice);

        $services = $invoice->items->filter(function($item) {
            return $item->type === 'service';
        });

        $company =  DB::table('official_info')->get()->first();

        // $company =  DB::table('official_info')->get()->first();
        $admin_id = $invoice->invoice_created_by;
        $admin_name = '';
        $creator_id =  DB::select( DB::raw("SELECT firstname,lastname FROM users where id = '$admin_id' "));
        foreach($creator_id as $c){

            $admin_name = $c->firstname.' '.$c->lastname;
        }
        $data = array(
            'invoice' => $invoice,
            'products' => $products,
            'services' => $services,
            'official' => false,
            'company' => $company,
            'admin' => $admin_name
        );

        // $data = array(
        //     'invoice' => $invoice,
        //     'products' => $products,
        //     'services' => $services,
        //     'official' => false,
        //     'company' => $company
        // );

        if ($request->print === 'office') {
            $data['official'] = true;
        }
        
        $dompdf = new Dompdf();
        $dompdf->set_option('defaultFont', 'Arial');       
        $pdf = $dompdf->loadHtml(View::make('invoice.customer', $data));
		$dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        //$to_name = 'Test Name';
        $to_email = $request->customer_email;
    

        $filename = $invoice->invoice_no . '.pdf';
        
        $pdf = $dompdf->loadHtml('invoice.customer', $data);
        
        Mail::send('invoice.email', $data, function($message) use($pdf,$dompdf,$to_email,$filename)
        {   
            $message->attachData($dompdf->output(), $filename, [
                'mime' => 'application/pdf',
            ]);
            $message->from('testphpmaileremail@gmail.com', 'Fly Solution Travel & Tours');
        
            $message->to($to_email)->subject('Invoice');
        
            $message->attachData($pdf, "invoice.pdf");
        });


		return 200;
        
        if ($request->print) {
            return new Response($dompdf->output(), 200, [
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => 'inline; filename="'. $filename . '"',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Type' => 'application/pdf',
            ]);
        }

        return new Response($dompdf->output(), 200, [
			'Content-Description' => 'File Transfer',
			'Content-Disposition' => 'attachment; filename="'. $filename . '"',
			'Content-Transfer-Encoding' => 'binary',
			'Content-Type' => 'application/pdf',
        ]);
        
        
            
        
        
    }
    public function email_invoice_to_office(Request $request)
    {
        # code...
        $invoice = Invoice::where('invoice_no', $request->invoice_no)->with(['customer', 'vendor', 'items'])->first();

        if (!$invoice) {
            return response()->json([
                'status' => 404
            ]);
        }

        $invoice->subtotal = 0;

        $products = $invoice->items->filter(function($item) use ($invoice) {
            $invoice->subtotal = $invoice->subtotal + ($item->quantity * $item->price);
            return $item->type === 'product';
        });

        // dd($invoice);

        $services = $invoice->items->filter(function($item) {
            return $item->type === 'service';
        });

        $company =  DB::table('official_info')->get()->first();
        $data = array(
            'invoice' => $invoice,
            'products' => $products,
            'services' => $services,
            'official' => false,
            'company' => $company
        );

        if ($request->print === 'office') {
            $data['official'] = true;
        }
        
        $dompdf = new Dompdf();
        $dompdf->set_option('defaultFont', 'Arial');       
        $pdf = $dompdf->loadHtml(View::make('invoice.customer', $data));
		$dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        //$to_name = 'Test Name';
        $to_email = 'info@flysolution.com.bd';
    

        $filename = $invoice->invoice_no . '.pdf';
        
        $pdf = $dompdf->loadHtml('invoice.customer', $data);
        
        Mail::send('invoice.email', $data, function($message) use($pdf,$dompdf,$to_email,$filename)
        {   
            $message->attachData($dompdf->output(), $filename, [
                'mime' => 'application/pdf',
            ]);
            $message->from('testphpmaileremail@gmail.com', 'Office Copy || Fly Solution Travel & Tours');
        
            $message->to($to_email)->subject('Invoice');
        
            $message->attachData($pdf, "invoice.pdf");
        });


		return 200;
        
        if ($request->print) {
            return new Response($dompdf->output(), 200, [
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => 'inline; filename="'. $filename . '"',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Type' => 'application/pdf',
            ]);
        }

        return new Response($dompdf->output(), 200, [
			'Content-Description' => 'File Transfer',
			'Content-Disposition' => 'attachment; filename="'. $filename . '"',
			'Content-Transfer-Encoding' => 'binary',
			'Content-Type' => 'application/pdf',
        ]);
        
        
            
    }

    public function calculate_invoice_data(Request $request)
    {
        # code...
         
        $ids = $request->all();
       
    

       for ($x = 0; $x < count($ids); $x++) {

            $array_of_id[] = array("invoice_id"=> $ids[$x]);   
       }
      
       
    
          $idArray = array();
            
            
            foreach($array_of_id as $d => $value){
                if(isset($value['invoice_id'])){
                    array_push($idArray,$value['invoice_id']);    
                }
               
            } 
  
        
         

        // DB::enableQueryLog();
    

         $total_purchase =  DB::select( DB::raw('SELECT  SUM(total_receivable) AS total_purchase FROM invoices WHERE id IN ("' . implode('", "', $idArray) . '") '));

         //$query = DB::getQueryLog();

        //return print_r($query);

        $total_paid =  DB::select( DB::raw('SELECT  SUM(total_receivable) AS total_paid FROM invoices WHERE id IN ("' . implode('", "', $idArray) . '") AND status = "paid" '));

        $total_due =  DB::select( DB::raw('SELECT  SUM(total_receivable) AS total_due FROM invoices WHERE id IN ("' . implode('", "', $idArray) . '") AND status = "due" '));

        

        return response()->json([
           
            'total_purchase' => $total_purchase,
            'all_ids' => $idArray,
             'total_paid' => $total_paid,
             'total_due' => $total_due,
        ]);
       
    }  
    public function calculate_and_print_invoice_data(Request $request, $customer_id)
    {
        # code...
         
        $ids = $request->all();
       
       for ($x = 0; $x < count($ids); $x++) {

            $array_of_id[] = array("invoice_id"=> $ids[$x]);   
            
       }
      
       $idArray = array();
            

      foreach($array_of_id as $d => $value){
        if(isset($value['invoice_id'])){
            
            array_push($idArray,$value['invoice_id']);    
        }
               
      } 

        // DB::enableQueryLog();
    

         $invoice =  DB::select( DB::raw('SELECT  total_receivable AS total_purchase, invoice_no, invoice_date, status FROM invoices WHERE id IN ("' . implode('", "', $idArray) . '")'));

         $total = DB::select( DB::raw('SELECT SUM(total_receivable) AS total FROM invoices WHERE id IN ("' . implode('", "', $idArray) . '") '));
         //$query = DB::getQueryLog();

        //return print_r($query);

         $customer_info =  Customer::find($customer_id);

        return response()->json([
           
            'invoices' => $invoice,
            'customer' => $customer_info,
            'total' => $total
             
        ]);
        
        
       
    }  
    public function calculate_and_print_invoice_data_vendor(Request $request, $vendor_id)
    {
        # code...
         
        $ids = $request->all();
       
       for ($x = 0; $x < count($ids); $x++) {

            $array_of_id[] = array("invoice_id"=> $ids[$x]);   
            
       }
      
       $idArray = array();
            

      foreach($array_of_id as $d => $value){
        if(isset($value['invoice_id'])){
            
            array_push($idArray,$value['invoice_id']);    
        }
               
      } 

        // DB::enableQueryLog();
    

         $invoice =  DB::select( DB::raw('SELECT  total_receivable AS total_purchase, invoice_no, invoice_date, status FROM invoices WHERE id IN ("' . implode('", "', $idArray) . '")'));

         $total = DB::select( DB::raw('SELECT SUM(total_receivable) AS total FROM invoices WHERE id IN ("' . implode('", "', $idArray) . '") '));
         //$query = DB::getQueryLog();

        //return print_r($query);

         $vendor_info =  Vendor::find($vendor_id);

        return response()->json([
           
            'invoices' => $invoice,
            'vendor' => $vendor_info,
            'total' => $total
             
        ]);
        
        
       
    }  
    public function mark_invoice_as_paid($id, Request $request)
    {
        # code...
        $res = DB::select( DB::raw("UPDATE invoices SET status = 'paid'  WHERE invoice_no = '$id' "));
        if($res){

            return response()->json([
           
                'status' => 200
                
            ]);
        }
        
    }
    public function mark_invoice_as_unpaid($id, Request $request)
    {
        # code...
        $res = DB::select( DB::raw("UPDATE invoices SET status = 'due'  WHERE invoice_no = '$id' "));
        if($res){

            return response()->json([
           
                'status' => 200
                
            ]);
        }
        
    }

    public function partial_payment_invoice(Request $request)
    {
        # code...
         $id = $request->post('inv_id');

         $PartialAmount = $request->post('partial');


         $CurrentPartialAmount = 0;

         $partial = DB::select( DB::raw("SELECT partial_payment FROM invoices WHERE invoice_no = '$id' "));

         foreach($partial as $p){

            $CurrentPartialAmount = $p->partial_payment;
         }

         $TotalReceiveable = 0;

         $total = DB::select( DB::raw("SELECT total_receivable FROM invoices WHERE invoice_no = '$id' "));

         foreach($total as $t){

            $TotalReceiveable = $t->total_receivable;
         }

         $Note = $request->post('payment_note');
        
         $dis = DB::select( DB::raw("SELECT discount FROM invoices WHERE invoice_no = '$id' "));

         $discount_amount = 0;

         foreach($dis as $d){

            $discount_amount = $d->discount;
         }
        
         $DiscountAndPartialAmountSum = $PartialAmount + $discount_amount + $CurrentPartialAmount;

         if($TotalReceiveable == $DiscountAndPartialAmountSum){

            DB::select( DB::raw("UPDATE invoices SET partial_payment = partial_payment + $PartialAmount, status = 'paid' WHERE invoice_no = '$id' "));


         }else{

            DB::select( DB::raw("UPDATE invoices SET partial_payment = partial_payment + $PartialAmount, status = 'partial' WHERE invoice_no = '$id' "));
        
                
         }


         $last_id =  DB::select( DB::raw('SELECT tr_id FROM transaction ORDER BY ID DESC LIMIT 1'));
         $getLastId = 0;
         $setLastId = 0;
 
         foreach($last_id as $l){
 
             $getLastId = $l->tr_id;
         }
         if($getLastId == ''){
 
             $setLastId = 1001;
 
         }else{
 
             $setLastId = (int) $getLastId + 1;
         }

           $trxID = $setLastId;

           
        //    $transactionData = [
        //     'tr_id' =>  $trxID,
        //     'tr_amount' => "000",
        //     'tr_type' => "deposti",
        //     'tr_ac_type' => "petty",
        //     'tr_ac' => "12312312312321",
        //     'tr_note' => "test",
        //     'tr_date' => date('Y-m-d'),
        //     'flag' => $id
        // ];

        // $transaction = Transaction::create($transactionData);
       // $tr_id = $transaction->id;
          
        if ($request->hasFile('scanned_file')) {

            $file = $request->file('scanned_file');
            $filename =  $trxID. '.' . $file->getClientOriginalExtension();

            $directoryName = public_path("uploads/transaction/");
            
            if (!file_exists($directoryName)) {
                mkdir($directoryName);
            }

            $file->move($directoryName, $filename);
        }else{

            $filename = "";
        }
            $ReceivedAmountForThisAccount = $PartialAmount;

            $CurrentAccountNumber = $request->post('transaction_account');

            DB::select(DB::raw("UPDATE bank_details SET opening_balance = opening_balance + $ReceivedAmountForThisAccount WHERE ac_no = '$CurrentAccountNumber' "));

            $transactionDataUpdate = [
                'tr_id' => $trxID,
                'tr_amount' => $PartialAmount,
                'tr_type' => "deposit",
                'tr_ac_type' => $request->post('transaction_account_type'),
                'tr_ac' => $request->post('transaction_account'),
                'tr_note' => "Invoice No:- ". $id.", ".$Note,
                'tr_date' => date('Y-m-d'),
                'file' => $filename,
               

            ];

            // return response()->json([
            //     'data' => $transactionDataUpdate
            // ]);

            $transaction = Transaction::create($transactionDataUpdate);
        //    $updateOrders = Transaction::find($tr_id)->update($transactionDataUpdate);

       
            return response()->json([
                'data' => $transaction
            ]);
        
    }
    public function paid_payment_invoice(Request $request)
    {
        # code...
         $id = $request->post('inv_id');

         $PaidAmount = $request->post('paid_amount');


        DB::select( DB::raw("UPDATE invoices SET total_receivable = $PaidAmount, status = 'paid' WHERE invoice_no = '$id' "));

        $ReceivedAmountForThisAccount = $PaidAmount;

        $CurrentAccountNumber = $request->post('transaction_account');

        DB::select(DB::raw("UPDATE bank_details SET opening_balance = opening_balance + $ReceivedAmountForThisAccount WHERE ac_no = '$CurrentAccountNumber' "));
        
        
        

         $last_id =  DB::select( DB::raw('SELECT tr_id FROM transaction ORDER BY ID DESC LIMIT 1'));
         $getLastId = 0;
         $setLastId = 0;
 
         foreach($last_id as $l){
 
             $getLastId = $l->tr_id;
         }
         if($getLastId == ''){
 
             $setLastId = 1001;
 
         }else{
 
             $setLastId = (int) $getLastId + 1;
         }

           $trxID = $setLastId;

       
           
        //    $transactionData = [
        //     'tr_id' =>  $trxID,
        //     'tr_amount' => "000",
        //     'tr_type' => "deposti",
        //     'tr_ac_type' => "petty",
        //     'tr_ac' => "12312312312321",
        //     'tr_note' => "test",
        //     'tr_date' => date('Y-m-d')
        // ];

        // $transaction = Transaction::create($transactionData);
       // $tr_id = $transaction->id;
          
        if ($request->hasFile('scanned_file')) {

            $file = $request->file('scanned_file');
            $filename =  $trxID. '.' . $file->getClientOriginalExtension();

            $directoryName = public_path("uploads/transaction/");
            
            if (!file_exists($directoryName)) {
                mkdir($directoryName);
            }

            $file->move($directoryName, $filename);
        }else{

            $filename="";
        }
            $Note = $request->post('payment_note');

            $transactionDataUpdate = [
                'tr_id' => $trxID,
                'tr_amount' => $PaidAmount,
                'tr_type' => "deposit",
                'tr_ac_type' => $request->post('transaction_account_type'),
                'tr_ac' => $request->post('transaction_account'),
                'tr_note' => "Invoice No:- ". $id.", ".$Note,
                'tr_date' => date('Y-m-d'),
                'file' => $filename

            ];

            $transaction = Transaction::create($transactionDataUpdate);
           
           //$updateOrders = Transaction::find($tr_id)->update($transactionDataUpdate);

       
            return response()->json([
                'data' => $transaction
            ]);
        
    }
    public function customers_invoice_list($customer_id)
    {
        # code...
       

        return new InvoiceCollection(Customer::select('invoices.*','customers.name','customers.phone')->join('invoices','invoices.customer_id','customers.id')->where('invoices.customer_id',$customer_id)->where('invoices.invoices_status','active')->orderBy('invoices.invoice_date','DESC')->paginate(1000)); 
    }

   
}
