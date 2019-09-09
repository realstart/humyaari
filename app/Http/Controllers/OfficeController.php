<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AccountCollection;
use App\Office;
use App\Model\Banking;
use App\Model\InvoiceItem;
use App\Http\Resources\IncomeCollection;
use App\Http\Resources\ExpenseCollection;
use App\Model\Utility;
use Illuminate\Support\Facades\DB;
use App\Model\Transaction;
use App\Http\Resources\TransactionCollection;
use Illuminate\Support\Str;
use App\Model\Note;
use App\Http\Resources\NoteCollection;
use App\Http\Resources\SMSCollection;
use App\Model\SMS;
use App\Model\Company;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Mockery\Matcher\Not;
use phpDocumentor\Reflection\Types\Integer;

class OfficeController extends Controller
{
    //
    public function accounts()
    {

        return new AccountCollection(Banking::latest()->paginate(10));

    }

    public function transactions()
    {

        return new TransactionCollection(Transaction::latest()->paginate(10));

    }
    public function get_transaction_details($id)
    {
        # code...
        $tr_details = DB::select(DB::raw("SELECT * FROM transaction WHERE id = '$id' "));
        return response()->json([
            'data' => $tr_details
        ]);

    }
    public function search_accounts($field, $query)
    {
        # code...
        return new AccountCollection(Banking::where($field, 'LIKE', "%$query%")->orderBy('id', 'DESC')->paginate(10));
    }

    public function income_list()
    {
        # code...
        return new IncomeCollection(Office::latest()->paginate(10));

    }
    public function delete_income($id)
    {
        # code...
        $data = Office::findOrFail($id);

        $data->delete();
    
        return response()->json([

            'data' => $data
        ]);
    }
    public function delete_expense($id)
    {
        # code...
        $data = Utility::findOrFail($id);

        $data->delete();
    
        return response()->json([

            'data' => $data
        ]);
    }

    public function account_holder_list($ac_type)
    {
        # code...
        $ac_no = DB::select(DB::raw("SELECT * FROM bank_details WHERE ac_type = '$ac_type' "));
        return response()->json([
            'data' => $ac_no
        ]);


    }

    public function all_account_holder_list()
    {
        # code...
        $ac_no = DB::select(DB::raw("SELECT * FROM bank_details "));
        return response()->json([
            'data' => $ac_no
        ]);


    }

    public function add_income(Request $request)
    {
        # code...
        $inputs = $request->all();

        // $inputs['income_date'] = $inputs['income_date'];
        // $inputs['income_amount'] = $inputs['income_amount'];
        // $inputs['income_note'] = $inputs['income_note'];
         $inputs['account_no'] = $inputs['transaction_account'];
         $amount = $inputs['income_amount'];
         $account = $inputs['transaction_account'];

         DB::select(DB::raw("UPDATE bank_details SET opening_balance = opening_balance + $amount WHERE ac_no = '$account' "));

        $income = Office::create($inputs);

        return response()->json([
            'data' => $income
        ]);
    }

    public function expense_list()
    {
        # code...
        return new ExpenseCollection(Utility::latest()->paginate(10));
    }

    public function add_expense(Request $request)
    {
        # code...
        $inputs = $request->all();

        // $inputs['expense_date'] = $inputs['expense_date'];
        // $inputs['expense_amount'] = $inputs['expense_amount'];
        // $inputs['expense_note'] = $inputs['expense_note'];

        $inputs['account_no'] = $inputs['transaction_account'];
        $amount = $inputs['expense_amount'];
        $account = $inputs['transaction_account'];
        DB::select(DB::raw("UPDATE bank_details SET opening_balance = opening_balance - $amount WHERE ac_no = '$account' "));


        $income = Utility::create($inputs);

        return response()->json([
            'data' => $income
        ]);
    }

    public function AllReports()
    {
        # code...
        $TotalIncome = DB::select(DB::raw("SELECT SUM(income_amount) AS TotalIncome FROM income"));

        $TotalExpense = DB::select(DB::raw("SELECT SUM(expense_amount) AS TotalExpense FROM expense"));

        $TotalDue = DB::select(DB::raw("SELECT SUM(total_receivable) AS TotalDue FROM invoices WHERE status = 'due' AND invoices_status= 'active'"));

        $TotalSale = DB::select(DB::raw("SELECT SUM(total_receivable) AS TotalSale FROM invoices WHERE invoices_status= 'active'"));


        return response()->json([
            'income' => $TotalIncome,
            'expense' => $TotalExpense,
            'due' => $TotalDue,
            'sale' => $TotalSale,
        ]);

    }

    public function SevenDaysReports()
    {
        # code...
        $SevenDaysTotalIncome = DB::select(DB::raw("SELECT SUM(income_amount) AS SevenDaysIncome FROM income WHERE income_date >= (DATE(NOW()) - INTERVAL 7 DAY)"));

        $SevenDaysTotalExpense = DB::select(DB::raw("SELECT SUM(expense_amount) AS SevenDaysExpense FROM expense WHERE expense_date >= (DATE(NOW()) - INTERVAL 7 DAY)"));

        $SevenDaysTotalDue = DB::select(DB::raw("SELECT SUM(total_receivable) AS SevenDaysDue FROM invoices WHERE  invoices_status= 'active' AND status = 'due' AND invoice_date >= (DATE(NOW()) - INTERVAL 7 DAY)"));

        $SevenDaysTotalSale = DB::select(DB::raw("SELECT SUM(total_receivable) AS SevenDaysSale FROM invoices WHERE invoices_status= 'active' AND invoice_date >= (DATE(NOW()) - INTERVAL 7 DAY)"));


        return response()->json([
            'seven_days_income' => $SevenDaysTotalIncome,
            'seven_days_expense' => $SevenDaysTotalExpense,
            'seven_days_due' => $SevenDaysTotalDue,
            'seven_days_sale' => $SevenDaysTotalSale,
        ]);

    }

    public function add_transaction(Request $request)
    {
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
       
        // $transactionData = [
        //     'tr_id' => $setLastId,
        //     'tr_amount' => "100",
        //     'tr_type' => "deposti",
        //     'tr_ac_type' => "petty",
        //     'tr_ac' => "12312312312321",
        //     'tr_note' => "test",
        //     'tr_date' => date('Y-m-d')
        // ];

        // $transaction = Transaction::create($transactionData);



        if ($request->post('transaction_type') !== 'transfer') {
            $file = $request->file('scanned_file');
            $filename =  $setLastId. '.' . $file->getClientOriginalExtension();
            $directoryName = public_path("uploads/transaction/");
            
            if (!file_exists($directoryName)) {
                mkdir($directoryName);
            }
            $file->move($directoryName, $filename);
           
        }

       // $id = $transaction->id;

        $currentAmount = 0;
        $ConcatAcNo = '';
        $Amount = 0;
        $Account = '';
        $Account = $request->post('transaction_account');
        $Amount = $request->post('transaction_amount');

        if ($request->post('transaction_type') == 'deposit') {

            DB::select(DB::raw("UPDATE bank_details SET opening_balance = opening_balance + $Amount WHERE ac_no = '$Account' "));

            $ConcatAcNo = $request->post('transaction_account');
        }
        if ($request->post('transaction_type') == 'whithdraw') {

            DB::select(DB::raw("UPDATE bank_details SET opening_balance = opening_balance - $Amount WHERE ac_no = '$Account' "));

            $ConcatAcNo = $request->post('transaction_account');
        }
        if ($request->post('transaction_type') == 'transfer') {

            $from_ac = $request->post('transaction_account_from');

            $to_ac = $request->post('transaction_account_to');

            DB::select(DB::raw("UPDATE bank_details SET opening_balance = opening_balance - $Amount WHERE ac_no = '$from_ac' "));

            DB::select(DB::raw("UPDATE bank_details SET opening_balance = opening_balance + $Amount WHERE ac_no = '$to_ac' "));

            $ConcatAcNo = $request->post('transaction_account_from') . '->' . $request->post('transaction_account_to');


        }
        if($request->post('transaction_type') == 'transfer'){

            if($request->post('transaction_note') == null )
            {
                $note = '';
            }else{

                $note = $request->post('transaction_note');
            }
            $transactionDataUpdate = [

                'tr_id' => $setLastId,
                'tr_amount' => $request->post('transaction_amount'),
                'tr_type' => $request->post('transaction_type'),
                'tr_ac_type' => $request->post('transaction_account_type'),
                'tr_ac' => $ConcatAcNo,
                'tr_note' => $note,
                'tr_date' => $request->post('transaction_date'),
                'file' =>  "image file"
    
            ];
            
            // $updateOrder = Transaction::find($id)->update($transactionDataUpdate);

           
            $transaction = Transaction::create($transactionDataUpdate);

            return response()->json([
                'data' =>  $transaction
            ]);

        }else{

            if($request->post('transaction_note') == null )
            {
                $note = '';
            }else{

                $note = $request->post('transaction_note');
            }
            $transactionDataUpdate = [

                'tr_id' => $setLastId,
                'tr_amount' => $request->post('transaction_amount'),
                'tr_type' => $request->post('transaction_type'),
                'tr_ac_type' => $request->post('transaction_account_type'),
                'tr_ac' => $ConcatAcNo,
                'tr_note' => $note,
                'tr_date' => $request->post('transaction_date'),
                'file' =>  $filename
    
            ];

            // $updateOrder = Transaction::find($id)->update($transactionDataUpdate);

            
            $transaction = Transaction::create($transactionDataUpdate);

            return response()->json([
                'data' =>  $transaction
            ]);
    


        }
       
        


    }

    public function search_transaction($field, $query)
    {
        # code...
        return new TransactionCollection(Transaction::where($field, 'LIKE', "%$query%")->orderBy('id', 'DESC')->paginate(10));
    }

    public function LastSevenDaysIncomeReport()
    {
        # code...
        $SalesData = DB::select(DB::raw("SELECT income_date as income_date, SUM(income_amount) AS total_income FROM income WHERE income_date > (DATE(NOW()) - INTERVAL 7 DAY) GROUP BY income_date"));

        if ($SalesData) {
            return response()->json([
                'data' => $SalesData
            ]);
        }
    }

    public function LastSevenDaysExpenseReport()
    {
        # code...
        $SalesData = DB::select(DB::raw("SELECT expense_date as expense_date, SUM(expense_amount) AS total_expense FROM expense WHERE expense_date > (DATE(NOW()) - INTERVAL 7 DAY) GROUP BY expense_date"));

        if ($SalesData) {
            return response()->json([
                'data' => $SalesData
            ]);
        }
    }

    public function add_note(Request $request)
    {
        # code...
        $noteData = [


            'note_date' => $request->post('note_date'),
            'note_title' => $request->post('note_title'),
            'note_details' => $request->post('note_details'),

        ];
        $notes = Note::create($noteData);
  
        return response()->json([
            'data' => $notes
        ]);
    }

    public function note_list()
    {
        # code...
        return new NoteCollection(Note::latest()->paginate(10));
    }

    public function search_notes($field, $query)
    {
        # code...
        return new NoteCollection(Note::where($field, 'LIKE', "%$query%")->orderBy('id', 'DESC')->paginate(10));
    }

    public function sms_template_list()
    {
        # code...
        return new SMSCollection(SMS::latest()->paginate(10));
    }

    public function search_sms($field, $query)
    {
        # code...
        return new SMSCollection(SMS::where($field, 'LIKE', "%$query%")->orderBy('id', 'DESC')->paginate(10));
    }

    public function check_sms_template($sms_type)
    {
        # code...
        $check = DB::select(DB::raw("SELECT * FROM sms_template WHERE template_category = '$sms_type'"));
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
    public function add_sms_template(Request $request)
    {
        # code...

        $inputs = $request->all();
        $inputs['template_category'] = $inputs['template_category'];
        if ($inputs['template_category'] == 'due') {

            $inputs['template_serial'] = 'due_' . Str::random(8);

        }
        if ($inputs['template_category'] == 'paid') {

            $inputs['template_serial'] = 'paid_' . Str::random(8);
        }

        $inputs['template_status'] = $inputs['template_status'];
        $inputs['template_title'] = $inputs['template_title'];
        $inputs['template_body'] = $inputs['template_body'];

        $income = SMS::create($inputs);

        return response()->json([
            'data' => $income
        ]);
    }
    public function update_template_info(Request $request)
    {
        # code...
        $id = $request->post('id');
        $TemplateData = [


            'template_title' => $request->post('template_title'),
            'template_body' => $request->post('template_body'),

        ];
        $sms = SMS::find($id)->update($TemplateData);
        return response()->json([

            'data' => $sms
        ]);
    }
    public function delete_sms_template(Request $request,$id)
    {
        # code...
        $sms = SMS::findOrFail($id);

        $sms->delete();
        //DB::delete('delete from sms_template where id = ?',[$id]);

        return response()->json([

            'data' => $id
        ]);
    }
    public function send_sms($invoice_no,$customer_phone,$customer_name,$payment_category,$due_date,$due_amount)
    {
        # code...
        if($payment_category == 'due'){

            $results = DB::select("select template_body from sms_template where template_category = '$payment_category' ");
            foreach($results as $res){
                $string = $res->template_body;
            }
            $ReplaceValue = [$due_amount,$customer_name,$invoice_no];
    
            $SearchedValue = ["{amount}","{customer_name}","{invoice_no}"];
    
            $newSrting = str_replace($SearchedValue, $ReplaceValue, $string);

            // return response()->json([
            //     'data' =>  $newSrting
            // ]);
            
            
                $sms_array = array();
     
                //create a json array of your sms
                $row_array['trxID'] =     $this->udate('YmdHisu');
                $row_array['trxTime'] = date('Y-m-d H:i:s');
                
                $mySMSArray [0]['smsID'] = $this->udate('YmdHisu');
                $mySMSArray [0]['smsSendTime'] = date('Y-m-d H:i:s');
                $mySMSArray [0]['mask'] = 'maskName';
                // $mySMSArray [0]['mobileNo'] = '88'+$customer_phone;
                $mySMSArray [0]['mobileNo'] = "01731001333";
               
                
                $mySMSArray [0]['smsBody'] =  $newSrting ;
                
                $row_array['smsDatumArray'] = $mySMSArray;
                
                $myJSonDatum = json_encode($row_array);
                
                //specifi the url
                $url="http://api.infobuzzer.net/v3.1/SendSMS/sendSmsInfoStore";
                
                if($ch = curl_init($url))
                {
                    //Your valid username & Password ----------Please update those field
                    $username = 'virtualpeople450@gmail.com';
                    $password = '123456';
                
                    curl_setopt( $ch , CURLOPT_HTTPAUTH , CURLAUTH_BASIC ) ;
                    curl_setopt( $ch, CURLOPT_USERPWD , $username . ':' . $password ) ;
                    curl_setopt( $ch , CURLOPT_CUSTOMREQUEST , 'POST' ) ;
                
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                        'Content-Length: ' . strlen($myJSonDatum)));
                
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$myJSonDatum);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                    curl_setopt( $ch, CURLOPT_TIMEOUT , 300 ) ;
                    $response=curl_exec($ch);
                    curl_close($ch);
                   // echo('Response is: '.$response);
                   return response()->json([
                        'data' => $response
                    ]);
                }
                else
                {
                   // echo();
                   return response()->json([
                    'data' => "Sorry,the connection cannot be established"
                ]);
                }
        }
        if($payment_category == 'paid'){

            $results = DB::select("select template_body from sms_template where template_category = '$payment_category' ");
            foreach($results as $res){
                $string = $res->template_body;
            }
            $ReplaceValue = [$due_amount,$customer_name,$invoice_no];
    
            $SearchedValue = ["{amount}","{customer_name}","{invoice_no}"];
    
            $newSrting = str_replace($SearchedValue, $ReplaceValue, $string);

            // return response()->json([
            //     'data' =>  $newSrting
            // ]);
            
                $sms_array = array();
     
                //create a json array of your sms
                $row_array['trxID'] =     $this->udate('YmdHisu');
                $row_array['trxTime'] = date('Y-m-d H:i:s');
                
                $mySMSArray [0]['smsID'] = $this->udate('YmdHisu');
                $mySMSArray [0]['smsSendTime'] = date('Y-m-d H:i:s');
                $mySMSArray [0]['mask'] = 'maskName';
                // $mySMSArray [0]['mobileNo'] = '88'+$customer_phone;
                $mySMSArray [0]['mobileNo'] = "01731001333";
               
                
                $mySMSArray [0]['smsBody'] =  $newSrting ;
                
                $row_array['smsDatumArray'] = $mySMSArray;
                
                $myJSonDatum = json_encode($row_array);
                
                //specifi the url
                $url="http://api.infobuzzer.net/v3.1/SendSMS/sendSmsInfoStore";
                
                if($ch = curl_init($url))
                {
                    //Your valid username & Password ----------Please update those field
                    $username = 'virtualpeople450@gmail.com';
                    $password = '123456';
                
                    curl_setopt( $ch , CURLOPT_HTTPAUTH , CURLAUTH_BASIC ) ;
                    curl_setopt( $ch, CURLOPT_USERPWD , $username . ':' . $password ) ;
                    curl_setopt( $ch , CURLOPT_CUSTOMREQUEST , 'POST' ) ;
                
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                        'Content-Length: ' . strlen($myJSonDatum)));
                
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$myJSonDatum);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                    curl_setopt( $ch, CURLOPT_TIMEOUT , 300 ) ;
                    $response=curl_exec($ch);
                    curl_close($ch);
                   // echo('Response is: '.$response);
                   return response()->json([
                        'data' => $response
                    ]);
                }
                else
                {
                   // echo();
                   return response()->json([
                    'data' => "Sorry,the connection cannot be established"
                ]);
                }
        }
    }
    function udate($format, $utimestamp = null)
    {
        $m = explode(' ',microtime());
        list($totalSeconds, $extraMilliseconds) = array($m[1], (int)round($m[0]*1000,3));
        return date("YmdHis", $totalSeconds) . sprintf('%03d',$extraMilliseconds) ;
    }
    public function edit_sms_template(Request $request, $id)
    {
        # code...
        $request = SMS::find($id);

        return response()->json([
            'data' => $request
        ]);
    }

    public function get_company_info()
    {
        # code...
        $results = DB::select('select * from official_info');
        if ($results) {
            return response()->json([
                'data' => $results
            ]);
        }
    }

    public function edit_company_info($id)
    {
        # code...
        $results = DB::select("select * from official_info where id = '$id' ");
        if ($results) {
            return response()->json([
                'data' => $results
            ]);
        }
    }

    public function update_company_info(Request $request)
    {
        # code...
   
        $id = $request->post('id');
        $CompanyData = [

            'company_name' => $request->post('name'),
            'company_address' => $request->post('address'),
            'company_address2' => $request->post('address2'),
            'company_email' => $request->post('email'),
            'company_phone_one' => $request->post('phone1'),
            'company_phone_two' => $request->post('phone2'),

        ];
        $updateOrders = Company::find($id)->update($CompanyData);

        return response()->json([
            'data' => $updateOrders
        ]);
    }

    public function update_company_logo(Request $request) {
        $file = $request->file('company_logo');
        $id = $request->post('id');

        $filename =  "logo_new". '.' . $file->getClientOriginalExtension();

        $directoryName = base_path("uploads/logo/");
        
        if (!file_exists($directoryName)) {
            mkdir($directoryName);
        }

        $file->move($directoryName, $filename);

        $LogoData = [
            
            'company_logo' => $filename
        ];
       
        $updateOrders = Company::find($id)->update($LogoData);
   
        return response()->json([
            'data' => $updateOrders
        ]);
    }

    public function edit_note(Request $request, $id)
    {
        # code...
        $request = Note::find($id);

        return response()->json([
            'data' => $request
        ]);
       
    }
    public function update_note_info(Request $request)
    {
        # code...
        $id = $request->post('id');
        $noteData = [


            'note_date' => $request->post('note_date'),
            'note_title' => $request->post('note_title'),
            'note_details' => $request->post('note_details'),

        ];
        $notes = Note::find($id)->update($noteData);
        return response()->json([

            'data' => $notes
        ]);
    }
    public function delete_note(Request $request, $id)
    {
        # code...
        $note = Note::findOrFail($id);

        $note->delete();

        return response()->json([

            'data' => $note
        ]);

    }
    public function delete_transaction(Request $request, $id,$type,$account,$amount,$flag)
    {
        # code...

        if ($type == 'deposit') {

            DB::select(DB::raw("UPDATE bank_details SET opening_balance = opening_balance - $amount WHERE ac_no = '$account' "));
            if($flag !=''){
                $data = DB::select( DB::raw("SELECT total_receivable,partial_payment FROM invoices WHERE invoice_no = '$flag'"));
                $CurrentAmountOfInvoice= 0;
                $CurrentPartialAmountOfInvoice= 0;
                foreach($data as $d){

                    $CurrentAmountOfInvoice = $d->total_receivable;
                    $CurrentPartialAmountOfInvoice = $d->partial_payment;
                }
                if($CurrentAmountOfInvoice == $CurrentPartialAmountOfInvoice){ // if tota_receivable is equal to partial amount then set status 'partial'

                    DB::select( DB::raw("UPDATE invoices SET partial_payment = partial_payment - $amount, status = 'partial' WHERE invoice_no = '$flag' "));

                }
                else{

                    DB::select( DB::raw("UPDATE invoices SET partial_payment = partial_payment - $amount, status = 'partial' WHERE invoice_no = '$flag' "));
                }



               
            }
           
        }
        if ($type == 'whithdraw') {

            DB::select(DB::raw("UPDATE bank_details SET opening_balance = opening_balance + $amount WHERE ac_no = '$account' "));
            
        }

        $transaction = Transaction::findOrFail($id);

        $transaction->delete();

        return response()->json([

            // 'data' => $id.", ".$type.", ".$account.", ".$amount
            'data' => $transaction
        ]);
    }

    public function count_total_dashboard(Request $request)
    {
        # code...

        $total_ticket =  DB::select( DB::raw("SELECT  COUNT(id) AS total_ticket FROM invoice_items WHERE type='product'"));
        $total_customer =  DB::select( DB::raw("SELECT  COUNT(id) AS total_customer FROM customers"));
        $total_service =  DB::select( DB::raw("SELECT  COUNT(id) AS total_service FROM invoice_items WHERE type='service'"));
        $total_profit =  DB::select( DB::raw("SELECT SUM(profit) AS total_profit FROM invoices"));
        $total_sale_month = DB::table('invoice_items')->whereMonth('created_at', date('m'))->count();
        $total_due = DB::table('invoices')->where('status','due')->count();
        return response()->json([           
            'total_ticket' => $total_ticket,
            'total_customer' => $total_customer,
            'total_service' => $total_service,
            'total_profit' => $total_profit,
            'total_sale_month' => $total_sale_month,
            'total_due' => $total_due,
        ]);
    }

    public function daily_sales_stat(Request $request)
    {
        # code...
        $date =  date('Y-m-d');

        $gds =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS gds_sales FROM invoices WHERE flag='GDS' AND invoice_date = '$date'"));;
      
        $service =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS service_sales FROM invoices WHERE flag='Service' AND invoice_date = '$date'"));

        $third_party =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS thrid_party_sales FROM invoices WHERE flag='third party' AND invoice_date = '$date'"));

        $total_sale =  DB::select( DB::raw("SELECT SUM(total_receivable) AS total_sales FROM invoices WHERE  invoice_date = '$date'"));


        return response()->json([
           
            'gds_sales' => $gds,
            'service_sales' => $service,
            'thrid_party_sales' => $third_party,
            'total_sales' => $total_sale

            
        ]);
    }
    public function monthly_sales_stat(Request $request)
    {
        # code...
       // DB::enableQueryLog();
       
       
        $date =  date('Y-m-d');
        $OneMonthDuration = date('Y-m-d',strtotime("-30 days",strtotime($date)));

        //return $OneModnthDuration;


        $gds =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS gds_sales FROM invoices WHERE flag='GDS' AND invoice_date BETWEEN '$OneMonthDuration' AND '$date'"));

        // $query = DB::getQueryLog();
        // print_r($query);
      
        $service =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS service_sales FROM invoices WHERE flag='Service' AND invoice_date BETWEEN '$OneMonthDuration' AND '$date'"));

        $third_party =  DB::select( DB::raw("SELECT  SUM(total_receivable) AS thrid_party_sales FROM invoices WHERE flag='third party' AND invoice_date BETWEEN '$OneMonthDuration' AND '$date'"));

        $total_sale =  DB::select( DB::raw("SELECT SUM(total_receivable) AS total_sales FROM invoices WHERE  invoice_date BETWEEN '$OneMonthDuration' AND '$date'"));


        return response()->json([
           
            'gds_sales' => $gds,
            'service_sales' => $service,
            'thrid_party_sales' => $third_party,
            'total_sales' => $total_sale

            
        ]);
    }
    

}
