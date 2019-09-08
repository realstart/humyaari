<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/api/v1/login', 'AuthController@login');
Route::get('/api/v1/makepdf/{invoice_no}/{print?}', 'InvoiceController@makePDF')->name('pdf');
Route::get('/api/v1/companny-info-for-login','OfficeController@get_company_info');
Route::get('/api/v1/email-invoice/{invoice_no}/{customer_email}/{print?}','InvoiceController@email_invoice');
Route::get('/api/v1/email-invoice-office/{invoice_no}/{print?}','InvoiceController@email_invoice_to_office');


Route::get('/api/v1/gds-details-vendor/{vendor_id}','VendorController@vendor_gds');

Route::get('/api/v1/service-details-vendor/{vendor_id}','VendorController@vendor_service');

Route::get('/api/v1/third-party-details-vendor/{vendor_id}','VendorController@vendor_third_party');

Route::middleware(['jwt'])->group(function () {

    Route::get('/api/v1/get-last-invoice-no','InvoiceController@get_last_invoice_no');

     Route::get('/api/v1/get-invoice-count','InvoiceController@count_invoice');

    Route::get('/api/v1/invoice-list-customers/{customer_id}','InvoiceController@customers_invoice_list');

    Route::get('/api/v1/invoice-list','InvoiceController@show');

    Route::get('/api/v1/user-log', 'AuthController@userLog');

    Route::post('/api/v1/invoice-create', 'InvoiceController@make');

    Route::post('/api/v1/get-invoice', 'InvoiceController@getInvoice');

    Route::post('/api/v1/search-customer', 'CustomerController@search');

    Route::post('/api/v1/vendors', 'VendorController@all');

    Route::post('/api/v1/vendor-create', 'VendorController@make');

    Route::post('/api/v1/customer-create', 'CustomerController@make');

    Route::post('/api/v1/income-create', 'OfficeController@add_income');

    Route::post('/api/v1/expense-create', 'OfficeController@add_expense');

    Route::post('/api/v1/account-create', 'BankingController@make');

    Route::post('/api/v1/note-create', 'OfficeController@add_note');

    Route::post('/api/v1/template-create', 'OfficeController@add_sms_template');

    Route::post('/api/v1/delete-sms-template/{any}', 'OfficeController@delete_sms_template');

    Route::post('/api/v1/delete-user/{any}', 'UserController@delete_user');

    Route::post('/api/v1/delete-bank/{any}', 'BankingController@delete_bank');

    Route::get('/api/v1/edit-user/{any}', 'UserController@edit_user');

    Route::get('/api/v1/edit-bank/{any}', 'BankingController@edit_bank');

    Route::post('/api/v1/check-sms-exists/{any}', 'OfficeController@check_sms_template');

    Route::post('/api/v1/send-sms/{invoice_no}/{customer_phone}/{customer_name}/{payment_category}/{due_date}/{due_amount}', 'OfficeController@send_sms');

    Route::post('/api/v1/transaction-create', 'OfficeController@add_transaction');

    Route::get('/api/v1/get-transaction-details/{id}','OfficeController@get_transaction_details');

    Route::get('/api/v1/customer-list','CustomerController@show');

    Route::get('/api/v1/customer-have-invoice','CustomerController@customer_report');

    Route::get('/api/v1/vendor-have-invoice','VendorController@vendor_report');

    Route::get('/api/v1/vendor-name','VendorController@vendor_name');
    
    Route::get('/api/v1/user-have-invoice/{id}','CustomerController@user_invoice_report');

    Route::get('/api/v1/customer-details-with-invoice/{customer_id}','CustomerController@customer_detils_with_invoice');

    Route::get('/api/v1/vendor-details-with-invoice/{vendor_id}','VendorController@vendor_detils_with_invoice');

    // Route::get('/api/v1/gds-details-vendor/{vendor_id}','VendorController@vendor_gds');

    Route::get('/api/v1/customer-invoice-list/{customer_id}','CustomerController@customers_invoice');

    // Route::get('/api/v1/invoice-list-customers/{customer_id}','InvoiceController@customers_invoice_list');

    Route::get('/api/v1/search-customer/{field}/{query}','CustomerController@search_customer');

    Route::get('/api/v1/search-vendors/{field}/{query}','VendorController@search_vendor');

    // Route::get('/api/v1/search-vendor/{field}/{query}','VendorController@search_vendor');

    Route::get('/api/v1/search-notes/{field}/{query}','OfficeController@search_notes');

    Route::get('/api/v1/search-sms/{field}/{query}','OfficeController@search_sms');

    Route::get('/api/v1/invoice-report/{date_from}/{date_to}/{status}/','InvoiceController@invoice_report');

    Route::get('/api/v1/invoice-report-vendor/{date_from}/{date_to}/{vendor_id}','InvoiceController@invoice_report_vendor');

    Route::get('/api/v1/single-vendor-details/{vendor_id}','VendorController@get_single_vendor_details');

    Route::get('/api/v1/search-invoice/{field}/{query}','VendorController@search_invoice');

    Route::get('api/v1/search-invoice-for-user/{field}/{query}/{any}','UserController@search_invoice_for_user');

    Route::get('/api/v1/search-transactions/{field}/{query}','OfficeController@search_transaction');

    Route::get('/api/v1/search-accounts/{field}/{query}','OfficeController@search_accounts');

    Route::get('/api/v1/vendor-list','VendorController@show');

    Route::get('/api/v1/dashboard-invoice-list','InvoiceController@dashboard_list');

    Route::get('/api/v1/account-list','OfficeController@accounts');

    Route::get('/api/v1/transaction-list','OfficeController@transactions');

    Route::get('/api/v1/template-list','OfficeController@sms_template_list');

    Route::post('/api/v1/account-holder-list/{ac_type}','OfficeController@account_holder_list');

    Route::post('/api/v1/all-account-holder-list','OfficeController@all_account_holder_list');

    Route::get('/api/v1/income-list','OfficeController@income_list');

    Route::get('/api/v1/expense-list','OfficeController@expense_list');

    Route::get('/api/v1/note-list','OfficeController@note_list');

    Route::get('/api/v1/user-list','UserController@show');

    Route::get('/api/v1/bank-list','BankingController@show');

    Route::get('/api/v1/list-of-all-banks','BankingController@all_bank_list');

    Route::get('/api/v1/all-reports','OfficeController@AllReports');

    Route::get('/api/v1/seven-days-reports','OfficeController@SevenDaysReports');

    Route::get('/api/v1/sales-report','SalesController@LastSevenDaysSalesReport');

    Route::get('/api/v1/dashboard_transaction','SalesController@LastSevenDaysStatics');

    Route::get('/api/v1/income-report','OfficeController@LastSevenDaysIncomeReport');

    Route::get('/api/v1/expense-report','OfficeController@LastSevenDaysExpenseReport');

    Route::get('/api/v1/view-invoice/{any}','InvoiceController@view_invoice');

    Route::get('/api/v1/companny-info','OfficeController@get_company_info');

    Route::get('/api/v1/mark-as-paid-invoice/{id}','InvoiceController@mark_invoice_as_paid');

    Route::get('/api/v1/mark-as-unpaid-invoice/{id}','InvoiceController@mark_invoice_as_unpaid');

    Route::post('/api/v1/partial-payment-invoice','InvoiceController@partial_payment_invoice');

    Route::post('/api/v1/mark-as-paid-invoice','InvoiceController@paid_payment_invoice');

    Route::get('/api/v1/soft-delete-vendor/{id}','VendorController@soft_delete_vendor');

    Route::get('/api/v1/delete-note/{id}','OfficeController@delete_note');

    Route::delete('/api/v1/delete-income/{id}','OfficeController@delete_income');

    Route::delete('/api/v1/delete-expense/{id}','OfficeController@delete_expense');

    Route::delete('/api/v1/delete-transaction/{id}/{type}/{account}/{amount}/{flag}','OfficeController@delete_transaction');

    Route::get('/api/v1/soft-delete-customer/{id}','CustomerController@soft_delete_customer');

    Route::put('/api/v1/soft-delete-invoice/{id}','InvoiceController@soft_delete_invoice');

    Route::post('/api/v1/calculate-invoice-data','InvoiceController@calculate_invoice_data');

    Route::post('/api/v1/user-create','UserController@make');
    
    Route::post('/api/v1/bank-create','BankingController@make_new_bank');

    Route::post('/api/v1/check-user/{any}','UserController@check_existing_user');

    Route::post('/api/v1/calculate-and-print-invoice-data/{customer_id}','InvoiceController@calculate_and_print_invoice_data');

    Route::post('/api/v1/calculate-and-print-invoice-data-vendor/{vendor_id}','InvoiceController@calculate_and_print_invoice_data_vendor');

    Route::post('/api/v1/customer-email-check/{email}','CustomerController@check_customer_email');




    // EDIT API
    Route::get('/api/v1/edit-company-info/{id}','OfficeController@edit_company_info');

    Route::get('/api/v1/edit-template/{id}','OfficeController@edit_sms_template');

    Route::get('/api/v1/edit-client/{id}','CustomerController@edit_customer');

    Route::get('/api/v1/edit-vendor/{id}','VendorController@edit_vendor');

    Route::get('/api/v1/edit-note/{id}','OfficeController@edit_note');


    // Route::get('/api/v1/edit-company-info/{id}','OfficeController@edit_company_info');


    //Updates API
    Route::post('/api/v1/update-company-info','OfficeController@update_company_info');

    Route::post('/api/v1/update-company-logo','OfficeController@update_company_logo');

    Route::post('/api/v1/update-client-info','CustomerController@update_customer_info');

    Route::post('/api/v1/update-vendor-info','VendorController@update_vendor_info');

    Route::post('/api/v1/update-note-info','OfficeController@update_note_info');

    Route::post('/api/v1/update-template-info','OfficeController@update_template_info');

    Route::post('/api/v1/update-users-info','UserController@update_users_info');

    Route::post('/api/v1/update-bank-info','BankingController@update_bank_info');
    
    Route::post('/api/v1/update-user-info','UserController@update_user_info');

    Route::get('/api/v1/get-user-info/{id}','UserController@getUser');

    Route::post('/api/v1/check-user-password/{id}/{any}','UserController@check_user_password');
    
    Route::post('/api/v1/update-user-password','UserController@update_user_password');

    // Dashboard API

    Route::get('/api/v1/total-tspc','OfficeController@count_total_dashboard');
     Route::get('/api/v1/daily-sales-state','OfficeController@daily_sales_stat');
     Route::get('/api/v1/monthly-sales-state','OfficeController@monthly_sales_stat');

   
});

//Route::get('/api/v1/daily-sales-state','OfficeController@daily_sales_stat');





Route::get('/{any}', 'DashboardController@index')->where('any', '.*');






