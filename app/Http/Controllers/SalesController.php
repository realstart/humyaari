<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    //
    public function LastSevenDaysSalesReport()
    {
        # code...
        
        $SalesData = DB::select( DB::raw("SELECT invoice_date as sales_date, SUM(total_receivable) AS total_sale FROM invoices WHERE invoice_date > (DATE(NOW()) - INTERVAL 3 DAY) AND invoices_status='active' GROUP BY invoice_date"));

        // $SalesData = DB::select( DB::raw("SELECT expense_date as sales_date, SUM(expense_amount) AS total_sale FROM expense WHERE expense_date > (DATE(NOW()) - INTERVAL 3 DAY) GROUP BY expense_date"));

        if ($SalesData) {
            return response()->json([
                'data' => $SalesData
            ]);
        }
       
       
      

       
    }

    public function LastSevenDaysStatics(){
        $deposit = DB::select( DB::raw("SELECT SUM(tr_amount) AS deposit FROM transaction WHERE tr_date > (DATE(NOW()) - INTERVAL 7 DAY) AND tr_type='deposit' order by id Desc"));
        $withdraw = DB::select( DB::raw("SELECT SUM(tr_amount) AS withdraw FROM transaction WHERE tr_date > (DATE(NOW()) - INTERVAL 7 DAY) AND tr_type='whithdraw' order by id Desc"));
        $transfer = DB::select( DB::raw("SELECT SUM(tr_amount) AS transfer_amount FROM transaction WHERE tr_date > (DATE(NOW()) - INTERVAL 7 DAY) AND tr_type='transfer' order by id Desc"));
        if ($deposit) {
            return response()->json([
                'deposit' => $deposit,
                'withdraw' => $withdraw,
                'transfer' => $transfer
            ]);
        }
    }

}
