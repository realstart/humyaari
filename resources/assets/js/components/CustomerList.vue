
<template>

   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:;" v-on:click="previous()">Clients</a></li>
            <li class="breadcrumb-item"><a href="#">List of All Clients</a></li>
         </ol>
      </nav>
     
      <div class='' v-show="!InvoiceShowSuccess">
         <vue-progress-bar></vue-progress-bar>
         <div class="row" v-if="!AddNew">
            <div class="col-md-12">
              <router-link :to="{ name: 'CustomerCreate' }" class="btn btn-danger">+ Add New Customer</router-link >
            </div>
         </div>
         <br>
         <div class="row">
            <div class="col-md-12">
               <div class="mb-3" style="margin-bottom: 15px">
                  <div class="row">
                     <div class="col-md-2">
                        <strong>Search By</strong>
                     </div>
                     <div class="col-md-2">
                        <select v-model="queryField" class="form-control" name="" id="fileds">
                           <option value="">-- Select Option --</option>
                           <option value="name">Name</option>
                           <option value="email">Email</option>
                           <option value="phone">Phone</option>
                           <option value="address1">Address</option>
                        </select>
                     </div>
                     <div class="col-md-5">
                        <input v-model="query" class="form-control" type="text" placeholder="Enter Some Text">
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-12 table-responsive">
               <section class="panel">
                  <header class="panel-heading">
                     Customer List
                     <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <!-- <a href="javascript:;" class="fa fa-cog"></a> -->
                        <!-- <a href="javascript:;" class="fa fa-times"></a> -->

                     </span>
                  </header>
                  <div class="panel-body" style="display: block;">
                     <table class="table table-hover general-table">
                        <thead>
                           <tr>
                              <th >#</th>
                              <th >Name</th>
                              <th >Email</th>
                              <th >Phone</th>
                              <th >Address</th>
                              <th >Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-show="customers.length" v-for="(customer, index) in customers" :key="customer.id">
                              <th >{{index + 1}}</th>
                              <td>{{ customer.name }}</td>
                              <td>{{ customer.email }}</td>
                              <td>{{ customer.phone }}</td>
                              <td>{{ customer.address1 }} {{ customer.address2 }}</td>
                              <td>
                                 <a href="javascript:;" class="btn btn-info" title="View Invoice" v-on:click="loadInvoice(customer.id)"><i class="fa fa-files-o"></i></a>
                                 <!-- <a v-if="user.role == 'admin'" v-on:click="EditCustomer(customer.id)" href="#" class="btn btn-success" title="Edit" style="cursor:pointer" ><i class="fa fa-edit"></i></a> -->

                                  <button v-if="user.role == 'admin'" type="button" v-on:click="EditCustomer(customer.id)" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></button>

                                 <a v-if="user.role == 'admin'" href="javascript:;" class="btn btn-danger" title="Delete" v-on:click="DeleteCustomer(customer.id)"><i class="fa fa-trash-o"></i></a>
                              </td>
                           </tr>
                           <tr v-show="!customers.length">
                              <td colspan="8">
                                 <div class="alert alert-danger" role="alert" style="text-align: center; background: #eceff5;">
                                    Opps! Sorry no data found
                                 </div>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </section>
            </div>
         </div>
      </div>
      <div class="text-center">
         <pagination v-if="pagination.last_page > 1"
            :pagination="pagination"
            :offset="5"
            @paginate="query === '' ? loadUsers() : SearchData()"   
            ></pagination>
      </div>
      <div v-show="InvoiceShowSuccess && !viewSuccess" class="row">
         <div class="col-md-12 table-responsive">
            <!-- <a href="javascript:;" v-on:click="GoBack();" class="btn btn-success" >Go Back<a/> -->
            <table class="table-striped table table-bordered table-hover" id="">
               <thead class="" style="background:#222d32;color:white">
                  <tr>
                     <th scope="col">#</th>
                     <th scope="col">Invoice No</th>
                     <th scope="col">Date</th>
                     <th scope="col">Amount</th>
                     <th scope="col">Client Name</th>
                     <th scope="col">Client Phone</th>
                     <th scope="col">Payment Status</th>
                     <th scope="col">Options</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-show="invoices.length" v-for="(invoice, index) in invoices" :key="invoice.id">
                     <th scope="row">{{index + 1}}</th>
                     <td>FSTT{{ invoice.invoice_no }}</td>
                     <td>{{ invoice.invoice_date }}</td>
                     <td>{{ invoice.total_receivable }}</td>
                     <td>{{ invoice.name }}</td>
                     <td>{{ invoice.phone }}</td>
                     <td class="text-center"><span v-if="invoice.status == 'paid'" class="label label-success" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                        <span v-if="invoice.status == 'due'" class="label label-danger" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                        <span v-if="invoice.status == 'partial'" class="label label-warning" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                     </td>
                     <td>
                        <a class="btn btn-success" title="View Invoice" href="#" v-on:click="ViewInvoice(invoice.invoice_no)"><i class="fa fa-file"></i> View</a>
                        <a class="btn btn-warning" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/print'">Print Customer Copy</a>
                        <a class="btn btn-danger" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/office'">Print Office Copy</a>
                        <!-- <a class="btn btn-primary" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no +">Download</a> -->
                        <a href="#" class="btn btn-danger" title="Delete" v-on:click="DeleteInvoice(invoice.invoice_no)"><i class="fa fa-trash"></i></a>
                     </td>
                  </tr>
                  <tr v-show="!invoices.length">
                     <td colspan="6">
                        <div class="alert alert-danger" role="alert">
                           Opps! Sorry no data found
                        </div>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
      <div v-if="viewSuccess" >
         <!-- <div id="invoice" v-for="(invoice, index) in SingleInvoices" :key="invoice.id"> -->
         <div id="invoice">
            <div class="invoice" id="maininvoice">
               <!-- title row -->
               <div class="row">
                  <div class="col-xs-12">
                     <h2 class="page-header">
                        <strong>  {{ company.name}}
                        </strong>
                        <span class="pull-right">
                           Status: 
                           <span id="markp2">
                              <!-- <span class="label label-danger" >{{ PaymentStatus}}</span> -->
                              <span v-if="PaymentStatus == 'paid'" class="label label-success" style="font-size:14px;text-transform:uppercase"> {{ PaymentStatus }}</span>
                              <span v-if="PaymentStatus == 'due'" class="label label-danger" style="font-size:14px;text-transform:uppercase"> {{ PaymentStatus}}</span>
                              <span v-if="PaymentStatus == 'partial'" class="label label-warning" style="font-size:14px;text-transform:uppercase"> {{ PaymentStatus}}</span>
                           </span>
                        </span>
                     </h2>
                  </div>
                  <!-- /.col -->
               </div>
               <div class="row">
                  <div class="col-xs-10">
                     <span id="markp"><a v-if="PaymentStatus == 'due' || PaymentStatus == 'partial'" href="javascript:;" v-on:click="MarkAsPaid(InvoiceNo)" class="btn btn-success btn-sm mark-payment" title="Mark as Paid"><span class="icon-clipboard"></span>Mark as Paid </a>
                     <a v-if="PaymentStatus == 'paid'" href="javascript:;" v-on:click="MarkAsUnPaid(InvoiceNo)" class="btn btn-success btn-sm mark-payment" title="Mark as Paid"><span class="icon-clipboard"></span>mark as unpaid </a>&nbsp; &nbsp;
                     <a v-if="PaymentStatus == 'due' || PaymentStatus == 'partial'" href="javascript:;" v-on:click="PartialPay(InvoiceNo)" class="btn btn-primary btn-sm part-payment" title="Partial Payment"><span class="icon-drawer"></span>Partial Pay </a>&nbsp; &nbsp;
                     </span>
                     <a :href="'/api/v1/makepdf/' + this.InvoiceNo + '/print'" class="btn btn-success btn-sm" title="Print/Save" target="_blank"><span class="icon-download2"></span> PDF/Print Customer Copy
                     </a>
                     <a :href="'/api/v1/makepdf/' + this.InvoiceNo + '/office'" class="btn btn-success btn-sm" title="Print/Save" target="_blank" style="    margin-bottom: 2px;"><span class="icon-download2"></span> PDF/Print Office Copy
                     </a>
                     <a :href="'edit/invoice/' + this.InvoiceNo" class="btn btn-warning btn-sm" title="Edit"><span class="icon-pencil"  style=""></span>Edit Invoice</a>
                     <a href="javascirpt:;"  class="btn btn-primary btn-sm send-invoice" v-on:click="EmailInvoiceToCustomer(InvoiceNo,CustomerEmail)" title="Email" style=""><span class="icon-mail4"></span>Email Customer </a> 
                     <a href="javascirpt:;" class="btn btn-danger btn-sm send-invoice" v-on:click="EmailInvoiceToOffice(InvoiceNo,CustomerEmail)" title="Email" style=""><span class="icon-mail4"></span>Email Office </a> 
                     <a class="btn btn-info btn-sm send-sms" title="SMS" style="" href="javascript:" v-on:click="SendSMS(InvoiceNo,CustomerPhone,PaymentStatus,InvoiceDueDate,FinalSubTotal)" ><span class="icon-star-empty"></span>SMS </a>
                  </div>
                  <!-- /.col -->
               </div>
               <hr>
               <!-- info row -->
               <div class="row invoice-info">
                  <div class="col-sm-4 invoice-col">
                     From
                     <address>
                        <strong> {{ company.name}} </strong><br>{{ company.address }} <br> {{ company.address2 }}<br><strong>Phone:</strong> {{ company.phone1}} | {{ company.phone2}}<br><strong>E-mail:</strong> {{ company.email}}
                     </address>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 invoice-col">
                     To
                     <address>
                        <strong>{{ CustomerName }}</strong><br>{{ Address1 }} {{ Address2 }}<br><strong>Phone:</strong> {{ CustomerPhone }}<br><strong>E-mail:</strong> {{ CustomerEmail }}
                     </address>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 invoice-col">
                     <b>Invoice #FSTT{{ InvoiceNo }}</b>
                     <br>
                     <b>Invoice Date:</b> {{ InvoiceDate | moment("YYYY-MM-DD") }}<br>
                     <b>Payment Due:</b> {{ InvoiceDueDate}}
                  </div>
                  <!-- /.col -->
               </div>
               <!-- /.row -->
               <br>
               <!-- Table row -->
               <div class="row" v-for="(invoice, index) in Description"  :key="index"  v-if="invoice.type == 'product'">
                  <div class="col-xs-12 table-responsive">
                     <h3>Product Description</h3>
                     <table class="table table-striped">
                        <thead>
                           <tr>
                              <th>SL No.</th>
                              <th>E-Ticket No.</th>
                              <th>PNR</th>
                              <th>Route</th>
                              <th>Qty</th>
                              <th>Rate</th>
                              <!--  <th>Tax </th> -->
                              <th>Subtotal</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-for="(invoice, index) in Description"  :key="index"  v-if="invoice.type == 'product'" >
                              <td>{{ index +1}}</td>
                              <td>{{ invoice.name}}</td>
                              <td>{{ invoice.pnr}}</td>
                              <td>{{ invoice.route }}</td>
                              <td>{{ invoice.quantity}}</td>
                              <td>{{ invoice.price}}</td>
                              <!-- <td>
                                 0.00 (0.00%)</td> -->
                              <td>{{ invoice.total}}</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <br>&nbsp;
                  <!-- /.col -->
               </div>
               <div class="row" v-for="(invoice, index) in Description"  :key="index"  v-if="invoice.type == 'service'">
                  <div class="col-xs-12 table-responsive">
                     <h3>Service Description</h3>
                     <table class="table table-striped">
                        <thead>
                           <tr>
                              <th>SL No.</th>
                              <th>Service Name</th>
                              <th>Qty</th>
                              <th>Rate</th>
                              <!--  <th>Tax </th> -->
                              <th>Subtotal</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-for="(invoice, index) in Description"  :key="index"  v-if="invoice.type == 'service'" >
                              <td>{{ index +1}}</td>
                              <td>{{ invoice.name}}</td>
                              <td>{{ invoice.quantity}}</td>
                              <td>{{ invoice.price}}</td>
                              <!-- <td>0.00</td> -->
                              <!-- <td>
                                 0.00 (0.00%)</td> -->
                              <td>{{ invoice.total}}</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <br>&nbsp;
                  <!-- /.col -->
               </div>
               <!-- /.row -->
               <div class="row">
                  <!-- accepted payments column -->
                  <div class="col-xs-6">
                     <p class="lead"> Break Down Summary</p>
                     <div class="table-responsive">
                        <table class="table">
                           <tbody>
                              <tr>
                                 <th style="width:50%">Base Fare:</th>
                                 <td>
                                    {{ BaseFare }}                    
                                 </td>
                              </tr>
                              <tr>
                                 <th>Tax</th>
                                 <td>{{ Tax }}</td>
                              </tr>
                              <tr>
                                 <th>Discount</th>
                                 <td>{{ Discount }}</td>
                              </tr>
                              <tr>
                                 <th>Other Agent:</th>
                                 <td>{{ OtherAgent }}</td>
                              </tr>
                              <tr>
                                 <th>IATA Comission:</th>
                                 <td>{{ iataComission }}</td>
                              </tr>
                              <tr>
                                 <th>Govt. Tax:</th>
                                 <td>{{ GovtTax }}</td>
                              </tr>
                              <tr>
                                 <th>Profit:</th>
                                 <td>{{ Profit }}</td>
                              </tr>
                              <tr>
                                 <th>Airline Payment:</th>
                                 <td>{{ AirlinePayment }}</td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <!-- /.col -->
                  <div class="col-xs-6">
                     <p class="lead">Summary</p>
                     <div class="table-responsive ">
                        <table class="table">
                           <tbody>
                              <tr>
                                 <th style="width:50%">Subtotal:</th>
                                 <td>BDT {{ SubTotal }}</td>
                              </tr>
                              <tr>
                                 <th>Discount</th>
                                 <td>BDT {{ SubTotalDiscount }}</td>
                              </tr>
                              <tr v-if="PartialPayment > 0 && PartialPayment != FinalSubTotal">
                                 <th>Partial Amount</th>
                                 <td>BDT {{ PartialPayment }}</td>
                              </tr>
                              <tr>
                                 <th>Total Payable:</th>
                                 <td>BDT {{ FinalSubTotal }}</td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <!-- /.col -->
               </div>
               <div class="row">
                  <div class="col-xs-12">
                     <p class="lead">Payment Methods:</p>
                     <img :src="'/images/cashpay.png'" >
                     <br><br>            
                  </div>
                  <small><strong style="padding-left: 1.5%; float:right;padding-right: 1%; ">Invoice Created By <em>{{ user.firstname}} {{ user.lastname}}</em></strong></small>
               </div>
               <!-- /.row -->
            </div>
            <!-- /.content -->
         </div>
         <modal name="partial-modal" :width="900"
            :height="700" :left="500" :top="0" :clickToClose="false" :pivotX="0.8">
            <div class="container">
               <div class="row">
                  <div class="col-md-6">
                     <button class="btn btn-danger pull-right" style="margin-top: 35px;" @click="$modal.hide('partial-modal')">Close Window</button>
                     <h4>Partial Payment Details</h4>
                     <div class="panel-body form-group form-group-sm">
                        <form @submit.prevent="submitPartial" enctype="multipart/form-data">
                           <div class="form-group">
                              <label for="tr_ac_type">Account Type</label>
                              <select name="transaction_account_type" class="form-control" id="transaction_account_type" v-on:change="AccountType" v-model="fields.transaction_account_type">
                                 <option value="" disabled> --- Select type ---</option>
                                 <option value="bank">Bank</option>
                                 <option value="petty">Petty Cash</option>
                              </select>
                              <div v-if="errors && errors.transaction_account_type" class="text-danger">{{ errors.transaction_account_type[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="tr_ac">Account</label>
                              <select name="transaction_account" class="form-control" id="transaction_account" v-model="fields.transaction_account">
                                 <option value=" " disabled> --- Select type ---</option>
                                 <option
                                    v-for="vendor in account_numbers"
                                    :key="vendor.id"
                                    :value="vendor.ac_no"
                                    >{{ vendor.ac_no }}</option>
                              </select>
                              <div v-if="errors && errors.transaction_account" class="text-danger">{{ errors.transaction_account[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="partial">Payment Amount</label>
                              <input type="number" min="0" class="form-control" name="partial" id="partial" v-model="fields.partial" required/>
                              <div v-if="errors && errors.partial" class="text-danger">{{ errors.partial[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="scanned_file">
                              <input ref="scanned_file" id="scanned_file" name="scanned_file" class="form-control" type="file" accept="image/*" required>
                              </label>
                           </div>
                           <div class="form-group">
                              <label for="payment_note">Payment Note</label>
                              <input type="text" class="form-control" name="payment_note" id="payment_note" v-model="fields.payment_note" required/>
                              <div v-if="errors && errors.payment_note" class="text-danger">{{ errors.payment_note[0] }}</div>
                           </div>
                           <input type="hidden" name="inv_id" id="inv_id" v-model="fields.inv_id">
                           <button type="submit" class="btn btn-success">Submit</button>
                           <div v-if="loadSuccessMessage" class="alert alert-success mt-3">
                              Partial Payment Inserted Successfully
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </modal>
         <div id="part_payment" class="modal fade">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                     <h4 class="modal-title">Partial Payment Transaction Details</h4>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-6"><input type="text" id="amount" name="amount" class="form-control required" placeholder="Amount"></div>
                        <div class="col-md-6"><input type="text" name="tnote" class="form-control" placeholder="Payment Note"></div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" data-dismiss="modal" class="btn btn-primary" id="partpay">Add</button>
                  </div>
               </div>
            </div>
         </div>
         <div id="prm_invoice" class="modal fade">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                     <h4 class="modal-title">Payment reminder</h4>
                  </div>
                  <div class="modal-body">
                     <p>Do you like to send a payment reminder e-mail for this Invoice?</p>
                  </div>
                  <div class="modal-footer">
                     <button type="button" data-dismiss="modal" class="btn btn-primary" id="rmsend">Yes</button>
                     <button type="button" data-dismiss="modal" class="btn">No</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- <modal v-if="showModal" name="edit-modal" :width="900"
         :height="600" :left="500" :top="0" :clickToClose="false" :pivotX="0.8">
         <div class="container">
            <div class="row">
               <div class="col-md-8">
                  <button class="btn btn-danger pull-right" style="margin-top: 35px;" @click="$modal.hide('edit-modal')">Close Window</button>
                  <h1>Edit Customer Info</h1>
                  <section class="panel">
                     <header class="panel-heading">
                        Customer Create Form
                     </header>
                     <div class="panel-body">
                        <div class="position-center">
                           <form @submit.prevent="submit">
                              <div class="form-group">
                                 <label for="name">Name</label>
                                 <input type="text" class="form-control" name="name" id="name" v-model="customer.name" required/>
                                 <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                              </div>
                              <div class="form-group">
                                 <label for="address1">Address 1</label>
                                 <input type="text" class="form-control" name="address1" id="address1" v-model="customer.address1" required/>
                                 <div v-if="errors && errors.address1" class="text-danger">{{ errors.address1[0] }}</div>
                              </div>
                              <div class="form-group">
                                 <label for="address2">Address 2</label>
                                 <input type="text" class="form-control" name="address2" id="address2" v-model="customer.address2" required/>
                                 <div v-if="errors && errors.address2" class="text-danger">{{ errors.address2[0] }}</div>
                              </div>
                              <div class="form-group">
                                 <label for="phone">Phone 2</label>
                                 <input type="text" class="form-control" name="phone" id="phone" v-model="customer.phone" required/>
                                 <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                              </div>
                              <div class="form-group">
                                 <label for="email">Email</label>
                                 <input type="text" class="form-control" name="email" id="email" v-model="customer.email" required/>
                                 <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                              </div>
                              <input type="hidden" name="id" v-model="customer.id">
                              <button type="submit" class="btn btn-success">Update</button>
                              <div v-if="success" class="alert alert-success mt-3">
                                 Customer's Informations Updated Successfully
                              </div>
                           </form>
                        </div>
                     </div>
                  </section>
               </div>
            </div>
         </div>
      </modal> -->
      <!-- Modal -->
      <div  class="modal fade" id="myModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title"> Edit Customer Details</h4>
            </div>
            <div class="modal-body">
                  <section class="panel">
                     <!-- <header class="panel-heading">
                        Edit Customer Details
                     </header> -->
                     <div class="panel-body">
                        <div class="position-center">
                           <form @submit.prevent="submit">
                              <div class="form-group">
                                 <label for="name">Name</label>
                                 <input type="text" class="form-control" name="name" id="name" v-model="customer.name" required/>
                                 <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                              </div>
                              <div class="form-group">
                                 <label for="address1">Address 1</label>
                                 <input type="text" class="form-control" name="address1" id="address1" v-model="customer.address1" required/>
                                 <div v-if="errors && errors.address1" class="text-danger">{{ errors.address1[0] }}</div>
                              </div>
                              <div class="form-group">
                                 <label for="address2">Address 2</label>
                                 <input type="text" class="form-control" name="address2" id="address2" v-model="customer.address2" required/>
                                 <div v-if="errors && errors.address2" class="text-danger">{{ errors.address2[0] }}</div>
                              </div>
                              <div class="form-group">
                                 <label for="phone">Phone 2</label>
                                 <input type="text" class="form-control" name="phone" id="phone" v-model="customer.phone" required/>
                                 <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                              </div>
                              <div class="form-group">
                                 <label for="email">Email</label>
                                 <input type="text" class="form-control" name="email" id="email" v-model="customer.email" required/>
                                 <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                              </div>
                              <input type="hidden" name="id" v-model="customer.id">
                              <button type="submit" class="btn btn-success">Update</button>
                              <div v-if="success" class="alert alert-success mt-3">
                                 Customer's Informations Updated Successfully
                              </div>
                           </form>
                        </div>
                     </div>
                  </section>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
            
         </div>
      </div>
  

   </div>
</template>
<script type="text/ecmascript-6">
   export default {
     name: 'list',
     data() {
       return {
         customers: [],
         query:'',
         queryField: 'name',
         pagination: {
   
             current_page: 1,
         },
         Reload: false,
         fields: {},
         errors: {},
         success: null,
         loaded: true,  
         customer: {
   
                name:'',
                address1 : '',
                address2 : '',
                email: '',
                phone: '',
                id : ''
         },
         customers: [],
         Customer: [],
         customer_info: [],
         customer_id:'',
         invoices:[],
         all_invoice:[],
         single_customer:[],
         query:'',
         queryField: 'name',
         total_purchase:[],
         total_paid:[],
         total_due:[],
         total_purchase_ids:[],
         total_paid_ids:[],
         total_due_ids:[],
         InvoiceList: null,
         total_purchase: '',
         total_paid: '',
         total_due: '',
         selected: [],
         SelectedIds: '',
         InvoiceCalculate: false,
         modalShow: null,
         isLoading: false,
         is_invoice_all_calculated:false,
         Description:[],
         CustomerName:'',
         Address1:'',
         Address2:'',
         CustomerPhone:'',
         CustomerEmail:'',
         TotalOfAllInvoice:'',
         showModal: false,
   
   
         InvoiceShowSuccess: false,
         viewSuccess: null,
         invoices: [],
         Description: [],
         Summary: [],
         PaymentStatus:'',
         InvoiceNo:'',
         CustomerName:'',
         Address1:'',
         Address2:'',
         CustomerPhone:'',
         CustomerEmail:'',
         BaseFare:'',
         Tax:'',
         Discount:'',
         OtherAgent:'',
         iataComission:'',
         GovtTax:'',
         Profit:'',
         AirlinePayment:'',
         SubTotal:'',
         SubTotalDiscount:'',
         FinalSubTotal:'',
         InvoiceUniqID: '',
         PartialPayment: '',
         query:'',
         queryField: 'name',
         printUrl: '#',
         printUrlOffice: '#',
         downloadUrl: '#',
         pagination: {
             current_page: 1,
         },
         token: '?token=' + localStorage.getItem('token'),
         company: {
   
                name:'',
                address : '',
                address2 : '',
                phone1: '',
                phone2: '',
                email : '',
                id : ''
         },
         fields: {
   
            inv_id:'',
           // inv_sub: '',
         },
         loadSuccessMessage: '',
         account_numbers:[],
         user: JSON.parse(localStorage.getItem('user')),
       }
     },
     watch:{
        query:function(newQuery, oldQuery){
   
           if(newQuery === ''){
              this.loadUsers();
           }else{
   
              this.SearchData();
           }
        }
     },
     created() {
       this.loadUsers();
       this.CompanyInfo();
     },
     methods: {
      
       loadUsers(){
   
         this.$Progress.start()
        axios.get('/api/v1/customer-list?page='+this.pagination.current_page) 
             .then(response => {
   
                 this.success = false;
                 this.customers = response.data.data
                 this.pagination = response.data.meta
                    this.showModal = true;
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },
       SearchData(){
   
           this.$Progress.start()
             axios.get('/api/v1/search-customer/'+this.queryField+'/'+this.query+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.customers = response.data.data
                 this.pagination = response.data.meta
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },
       DeleteCustomer(id){
          var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.get('/api/v1/soft-delete-customer/'+id) 
               .then(response => {
                  alert("Deleted Successfully");
                  // console.log(response)
                     this.$Progress.finish();
                      location.href= "/customer-list";
               })
               .catch(e => {
      
                  console.log(e)
                     this.$Progress.fail()
               })
               }     
            else{
   
             return false;
            }
               
       },
       EditCustomer(id){
   
               //alert('edit customer');
            
                //this.$modal.show('edit-modal');
               //showModal = true;
               
               axios.get('/api/v1/edit-client/'+id) 
               .then(response => {
                console.log(response.data.data);     
                this.customer.name = response.data.data.name
                this.customer.address1 = response.data.data.address1
                this.customer.address2 = response.data.data.address2
                this.customer.phone = response.data.data.phone
                this.customer.email = response.data.data.email
                this.customer.id = response.data.data.id
                  
                
   
               })
               .catch(e => {
      
                  console.log(e)
                     this.$Progress.fail()
               })
          
       },
       submit() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post('/api/v1/update-client-info', this.customer).then(response => {
                   console.log(response);
                    this.company = {}; //Clear input fields.
                    this.loaded = true;
                     this.success = true;
                    this.Reload = true;
                    location.href= "/customer-list";
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
       },
         loadInvoice(customer_id){
   
        
         this.$Progress.start()
        axios.get('/api/v1/invoice-list-customers/'+customer_id+'/?page='+this.pagination.current_page) 
             .then(response => {
               
                 //console.log(response);
                 this.InvoiceShowSuccess = true;
                 this.invoices = response.data.data
                 this.pagination = response.data.meta
                 this.printUrl = location.origin + '/api/v1/makepdf/' + response.data.data.invoice_no + '/print';
                 this.printUrlOffice = location.origin + '/api/v1/makepdf/' + response.data.data.invoice_no + '/office';
                 this.downloadUrl = location.origin + '/api/v1/makepdf/' + response.data.data.invoice_no;
   
                  this.$Progress.finish()
                  
   
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },
        GoBack(){
   
         location.href="/customer-list";
      },
        ViewInvoice(InvoiceUniqID)
      {
        
         this.$Progress.start()
         axios.get('/api/v1/view-invoice/'+InvoiceUniqID) 
             .then(response => {
   
                  console.log(response);
                  this.viewSuccess = true;
                  this.Description = response.data.data.items;
                  this.Summary = response.data.data;
                  for(let i=0; i < this.Description.length ;i++){
                     
                     if(response.data.data.items[i].type == 'product'){
   
                       this.invoiceTypeProduct = true;
                     
                     }
                     if(response.data.data.items[i].type == 'service'){
   
                        this.invoiceTypeService = true;
                     }
   
                  }
   
   
                 // Invoice Details
                 this.PaymentStatus = response.data.data.status
                 this.InvoiceNo = response.data.data.invoice_no
                 this.InvoiceDate = response.data.data.invoice_date
                 this.InvoiceDueDate = response.data.data.invoice_due_date
   
                 this.BaseFare = response.data.data.base_fare
                 this.Tax = response.data.data.tax
                 this.Discount = response.data.data.discount
                 this.OtherAgent = response.data.data.other_agent
                 this.iataComission = response.data.data.iata_comission
                 this.GovtTax = response.data.data.govt_tax
                 this.Profit = response.data.data.profit
                 this.AirlinePayment = response.data.data.airline_payment
                 
                 this.SubTotalDiscount = response.data.data.discount
                 this.PartialPayment = response.data.data.partial_payment
                 this.SubTotal = parseFloat(response.data.data.total_receivable) + parseFloat(this.SubTotalDiscount)
                 
                 if(this.PartialPayment > 0 && this.SubTotalDiscount < 0 ){ // if no discount
   
                     this.FinalSubTotal = this.SubTotal - this.PartialPayment
   
                 }if(this.PartialPayment > 0 && this.SubTotalDiscount > 0 ){ // if has discount
   
                       this.FinalSubTotal = (this.SubTotal - this.PartialPayment) - this.SubTotalDiscount
   
                 }else{
   
                    this.FinalSubTotal = this.SubTotal - this.SubTotalDiscount
                 }
                
   
   
                 //  Customer Details  
                 this.CustomerName = response.data.data.customer.name
                 this.Address1 = response.data.data.customer.address1
                 this.Address2 = response.data.data.customer.address2
                 this.CustomerPhone = response.data.data.customer.phone
                 this.CustomerEmail = response.data.data.customer.email
   
                 this.fields.inv_id = InvoiceUniqID
                 //this.fields.inv_sub = 
                   //console.log(CustomerName)
                 
                  this.$Progress.finish()
                  
   
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
        },
         CompanyInfo(){
   
             axios.get('/api/v1/companny-info')
             .then(response => {
                
                this.company.name = response.data.data[0].company_name
                this.company.address = response.data.data[0].company_address
                this.company.address2 = response.data.data[0].company_address2
                this.company.phone1 = response.data.data[0].company_phone_one
                this.company.phone2 = response.data.data[0].company_phone_two
                this.company.email = response.data.data[0].company_email
                
              
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
       },
          DeleteInvoice(id){
          var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.put('/api/v1/soft-delete-invoice/'+id) 
               .then(response => {
                  //alert("Deleted Successfully");
                    location.href="/customer-list";
                     this.$Progress.finish()
               })
               .catch(e => {
      
                  console.log(e)
                     this.$Progress.fail()
               })
               }     
            else{
   
             return false;
            }
               
       },
       EmailInvoiceToCustomer(InvoiceID,EmailID){
         this.$Progress.start()
               axios.get('/api/v1/email-invoice/'+ InvoiceID + '/'+  EmailID +'/print') 
               .then(response => {
                  alert("Email Sent Successfully");
                  // console.log(response)
                     this.$Progress.finish()
               })
               .catch(e => {
      
                  console.log(e)
                  this.$Progress.fail()
               })
          
   
       },
       EmailInvoiceToOffice(InvoiceID){
         this.$Progress.start()
               axios.get('/api/v1/email-invoice-office/' + InvoiceID + '/office') 
               .then(response => {
                  alert("Email Sent Successfully");
                  // console.log(response)
                     this.$Progress.finish()
               })
               .catch(e => {
      
                  console.log(e)
                  this.$Progress.fail()
               })
          
   
       },
       
        submitPartial() 
        {
           
             if (this.loaded) {
                this.loaded = false;
                this.success = false;
                 this.loadSuccessMessage = false;
                this.errors = {};
   
                var bodyFormData = new FormData();
   
                Object.keys(this.fields).forEach((key) => {
                    bodyFormData.append(key, this.fields[key]);
                });
                let imageFile = this.$refs.scanned_file.files[0];
                bodyFormData.append('scanned_file', imageFile);
   
                axios({
                    method: 'post',
                    url: '/api/v1/partial-payment-invoice',
                    data: bodyFormData,
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                })
   
                .then((response) => {
   
                        this.fields = {}; //Clear input fields.
                        this.loaded = true;
                        this.success = true;   
                        location.href= "/customer-list";
                     
                      
                    })
                    .catch(function (response) {
                        this.loaded = true;
   
                        if (error.response.status === 200) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
   
               
            }
   
       },
       MarkAsPaid(id){
          //alert(id);
           var x = confirm("Are you sure you want park this as paid");
            if (x){
               this.$Progress.start()
               axios.get('/api/v1/mark-as-paid-invoice/'+id)
               .then(response => {
   
                  alert("Marked As Paid Successfully");
                  location.href="/customer-list";
                 
                 
               })
               .catch(e => {
   
                  console.log(e)
                     this.$Progress.fail()
               })
               }
            else{
   
             return false;
            }
       },
       MarkAsUnPaid(id){
          //alert(id);
          var x = confirm("Are you sure you want park this as Unpaid");
            if (x){
               this.$Progress.start()
               axios.get('/api/v1/mark-as-unpaid-invoice/'+id)
               .then(response => {
                    alert("Marked As Unpaid Successfully");
                  location.href="/customer-list";
               })
               .catch(e => {
      
                  console.log(e)
                     this.$Progress.fail()
               })
               }     
            else{
   
             return false;
            }
       },
       PartialPay(id){
   
           this.$modal.show('partial-modal');
          
       },
       SendSMS(InvoiceNo,CustomerPhone,CustomerName,PaymentStatus,InvoiceDueDate,SubTotal){
   
         let TotalAmount = '';
   
         if(PaymentStatus == 'due'){
   
            TotalAmount = this.FinalSubTotal 
         }
         if(PaymentStatus == 'paid'){
   
            TotalAmount = this.SubTotal
   
         }
         axios.post('/api/v1/send-sms/'+InvoiceNo+'/'+CustomerPhone+'/'+CustomerName+'/'+PaymentStatus+'/'+InvoiceDueDate+'/'+TotalAmount) 
                  .then(response => {
         
                    console.log(response.data.data);
                    alert("SMS send successfully");
                     
                  })
                  .catch(e => {
         
                     console.log(e)
                     this.$Progress.fail()
                  })
   
         
      },
       AccountType: function(e) {
        
               this.transfer = false;
   
               let ac_type = e.target.value;
               axios.post('/api/v1/account-holder-list/'+ac_type) 
               .then(response => {
      
                  //console.log(response.data.data)
                  this.account_numbers = response.data.data;
                  
               })
               .catch(e => {
      
                  console.log(e)
                     this.$Progress.fail()
               })
          
        
       },
       previous(){
   
          location.href= '/customer-list';
       }
     
     },
     filters: {
       
     },
     
   }
   
</script>