<template>
   <div>
         <!-- <div id="invoice" v-for="(invoice, index) in SingleInvoices" :key="invoice.id"> -->
         <div id="invoice">
         <div class="invoice" id="maininvoice">
            <!-- title row -->
            <div class="row">
               <div class="col-xs-12">
                  <h2 class="page-header">
                     <strong> {{ company.name}}
                     </strong>
                     <span class="pull-right">Status: 
                        <span id="markp2">
                           <span class="label label-warning" style="font-size:14px;text-transform:uppercase">{{ flag}}</span>
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

                 <span v-if="flag == 'Service'">
                     <a v-if="user.role == 'admin'" :href="'edit/invoiceService/' + this.InvoiceNo" class="btn btn-warning btn-sm" title="Edit Invoice"><span class="icon-pencil"  style=""></span>Edit Invoice</a>
                 </span> 

                 <span v-if="flag == 'GDS'">
                     <a v-if="user.role == 'admin'" :href="'edit/invoice/' + this.InvoiceNo" class="btn btn-warning btn-sm" title="Edit"><span class="icon-pencil"  style=""></span>Edit Invoice</a>
                 </span> 
                 <span v-if="flag == 'third party'">
                     <a v-if="user.role == 'admin'" :href="'edit/third-party/' + this.InvoiceNo" class="btn btn-warning btn-sm" title="Edit"><span class="icon-pencil"  style=""></span>Edit Invoice</a>
                 </span> 

                  <a href="javascirpt:;"  class="btn btn-primary btn-sm send-invoice" v-on:click="EmailInvoiceToCustomer(InvoiceNo,CustomerEmail)" title="Email" style=""><span class="icon-mail4"></span>Email Customer </a> 

                  <a href="javascirpt:;" class="btn btn-danger btn-sm send-invoice" v-on:click="EmailInvoiceToOffice(InvoiceNo,CustomerEmail)" title="Email" style=""><span class="icon-mail4"></span>Email Office </a> 

                  <a class="btn btn-info btn-sm send-sms" title="SMS" style="" href="javascript:" v-on:click="SendSMS(InvoiceNo,CustomerPhone,CustomerName,PaymentStatus,InvoiceDueDate,FinalSubTotal)" ><span class="icon-star-empty"></span>SMS </a>
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
                     <strong>{{ CustomerName }}</strong><br>{{ Address1 }} <br> {{ Address2 }}<br><strong>Phone:</strong> {{ CustomerPhone }}<br><strong>E-mail:</strong> {{ CustomerEmail }}
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
            <div class="row" v-show="this.invoiceTypeProduct == true">
               <div class="col-xs-12 table-responsive">
                  <h3>Product Description</h3>
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th>SL No.</th>
                           <th>E-Ticket No.</th>
                           <th>Passenger name</th>
                           <th>PNR</th>
                           <th>Carrier</th>
                           <th>Route</th>
                           <th>Qty</th>
                           <th>Rate</th>
                           <!--  <th>Tax </th> -->
                           <th>Subtotal</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr v-for="(invoice, index) in Description"  :key="index"  v-show="invoice.type == 'product'">
                           <td>{{ index +1}}</td>
                           <td>{{ invoice.e_ticket}}</td>
                           <td>{{ invoice.name}}</td>
                           <td>{{ invoice.pnr}}</td>
                           <td>{{ invoice.carrier}}</td>
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
            <div class="row" v-show="this.invoiceTypeService == true">
               <div class="col-xs-12 table-responsive">
                  <h3>Service Description </h3>
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
                         <tr v-for="(invoice, index) in Description"  :key="index"  v-show="invoice.type == 'service'" >
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
            <div class="row" >
               <!-- accepted payments column -->
               <div class="col-xs-6" v-if="BaseFare != 0">
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
                           <tr v-if="BaseFare == 0">
                              <th>Profit</th>
                              <td>BDT {{ Profit }}</td>
                           </tr>
                           <tr>
                              <th style="width:50%">Subtotal:</th>
                              <td>BDT {{ SubTotal }}</td>
                           </tr>
                           <tr v-if="BaseFare != 0">
                              <th>Discount</th>
                              <td>BDT {{ SubTotalDiscount }}</td>
                           </tr>
                           <tr v-if="PartialPayment > 0 && PartialPayment != FinalSubTotal">
                              <th>Partial Amount</th>
                              
                              <td>BDT {{ PartialPayment }}</td>
                              
                              </tr>
                             
                           <tr>
                              <th>Total Receivable:</th>
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
      
                        <form @submit.prevent="submit" enctype="multipart/form-data">
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
                                <label for="partial">Payment Amount (Payable Amount is: {{ this.FinalSubTotal}})</label>
                                <input type="number" min="0" class="form-control" name="partial" id="partial" v-model="fields.partial" v-on:change="PartialPaymnetCheck" required/>
                                <div v-if="errors && errors.partial" class="text-danger">{{ errors.partial[0] }}</div>
                            </div>
                            <div class="form-group" >
                                <label for="scanned_file">
                                   <input ref="scanned_file" id="scanned_file" name="scanned_file" class="form-control" type="file" accept="image/*" >
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="payment_note">Payment Note</label>
                                <input type="text" class="form-control" name="payment_note" id="payment_note" v-model="fields.payment_note" required/>
                                <div v-if="errors && errors.payment_note" class="text-danger">{{ errors.payment_note[0] }}</div>
                            </div>
                            <input type="hidden" name="inv_id" id="inv_id" v-model="fields.inv_id">
                            <button type="submit" class="btn btn-success" id="PartialPaymnetButton">Submit</button>
                            <div v-if="loadSuccessMessage" class="alert alert-success mt-3">
                            Partial Payment Inserted Successfully
                            </div>
                        </form>
                        
                    </div>
                    
                </div>
                </div>
            </div>
          </modal>
          <modal name="paid-modal" :width="900"
            :height="700" :left="500" :top="0" :clickToClose="false" :pivotX="0.8">
            <div class="container">
                <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-danger pull-right" style="margin-top: 35px;" @click="$modal.hide('paid-modal')">Close Window</button>
                    <h4>Paid Payment Details</h4>
                    <div class="panel-body form-group form-group-sm">
      
                        <form @submit.prevent="submitPaid" enctype="multipart/form-data">
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
                                <label for="paid_amount">Payment Amount (Payable Amount is: {{ this.FinalSubTotal}})</label>
                                <input type="number" min="0" class="form-control" name="paid_amount" id="paid_amount" v-model="fields.paid_amount" v-on:change="PaidPaymnetCheck" required/>
                                <div v-if="errors && errors.paid_amount" class="text-danger">{{ errors.paid_amount[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="scanned_file">
                                   <input ref="scanned_file" id="scanned_file" name="scanned_file" class="form-control" type="file" accept="image/*" >
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="payment_note">Payment Note</label>
                                <input type="text" class="form-control" name="payment_note" id="payment_note" v-model="fields.payment_note" required/>
                                <div v-if="errors && errors.payment_note" class="text-danger">{{ errors.payment_note[0] }}</div>
                            </div>
                            <input type="hidden" name="inv_id" id="inv_id" v-model="fields.inv_id">
                            <button type="submit" class="btn btn-success" id="PartialPaymnetButton">Submit</button>
                            <div v-if="loadSuccessMessage" class="alert alert-success mt-3">
                            Mark as Paid Inserted Successfully
                            </div>
                        </form>
                        
                    </div>
                    
                </div>
                </div>
            </div>
          </modal>
      </div>
</template>

<script>
export default {
    name: 'InvoiceDetails',
    props: ['id'],
    data() {
        return {
            welcome: 'This is your profile',
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
         errors: {},
        // success: false,
         loaded: true,
          account_numbers:[],
           user: JSON.parse(localStorage.getItem('user')),
           invoiceTypeService : false,
           invoiceTypeProduct : false,
           files_required: true,
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
         flag:'',
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
         success: null,
         InvoiceUniqID: '',
         PartialPayment: '',
         query:'',
         queryField: 'name',
         printUrl: '#',
         printUrlOffice: '#',
         downloadUrl: '#',
         flag:'',
         pagination: {
             current_page: 1,
         },
         token: '?token=' + localStorage.getItem('token'),
         InvoiceDate: '',
         InvoiceDueDate:'',
        }
    },
    mounted() {
        if(this.id != null){

            this.ViewInvoice(this.id);
             this.CompanyInfo();
             console.log("this is the id"+ this.id);

        }else{
           this.$router.back();
           //alert("problem")
        }
       
    },
     methods: {
      

       ViewInvoice(InvoiceUniqID){
          if(InvoiceUniqID == ''){

             alert("problem")
          }
         this.$Progress.start()
         axios.get('/api/v1/view-invoice/'+InvoiceUniqID) 
             .then(response => {
   
                
                  this.success = true;
                  this.Description = response.data.data.items;
                  for(let i=0; i < this.Description.length ;i++){
                     
                     if(response.data.data.items[i].type == 'product'){

                       this.invoiceTypeProduct = true;
                     
                     }
                     if(response.data.data.items[i].type == 'service'){

                        this.invoiceTypeService = true;
                     }

                  }
                  
                   console.log(response.data.data.items[0].type);
                  this.Summary = response.data.data;


                 // Invoice Details
                 this.PaymentStatus = response.data.data.status
                 this.flag = response.data.data.flag
                 this.InvoiceNo = response.data.data.invoice_no
                 this.InvoiceDate = response.data.data.invoice_date
                 this.InvoiceDueDate = response.data.data.invoice_due_date

                 this.BaseFare = response.data.data.base_fare
                 this.flag = response.data.data.flag
                 this.Tax = response.data.data.tax
                 this.Discount = response.data.data.discount
                 this.OtherAgent = response.data.data.other_agent
                 this.iataComission = response.data.data.iata_comission
                 this.GovtTax = response.data.data.govt_tax
                 this.Profit = response.data.data.profit
                 this.AirlinePayment = response.data.data.airline_payment
                
                 this.SubTotalDiscount = response.data.data.discount
                 this.PartialPayment = response.data.data.partial_payment
                 this.SubTotal = parseFloat(response.data.data.total_receivable)
               
                 //console.log(this.SubTotal);
                
                 if(this.PartialPayment >= 0 && this.SubTotalDiscount <= 0 ){ // if no discount

                     this.FinalSubTotal = this.SubTotal - this.PartialPayment;

                 }if(this.PartialPayment >= 0 && this.SubTotalDiscount > 0 ){ // if has discount

                       this.FinalSubTotal = (this.SubTotal - this.PartialPayment) - this.SubTotalDiscount;

                 }if(this.PartialPayment <= 0){

                     this.FinalSubTotal = this.SubTotal - this.SubTotalDiscount;

                 }if(this.PartialPayment == this.SubTotal){

                    this.FinalSubTotal = this.SubTotal
                 }
               

                


                 //  Customer Details  
                 this.CustomerName = response.data.data.customer.name
                 this.Address1 = response.data.data.customer.address1
                 this.Address2 = response.data.data.customer.address2
                 this.CustomerPhone = response.data.data.customer.phone
                 this.CustomerEmail = response.data.data.customer.email

                 this.fields.inv_id = InvoiceUniqID
                 //this.fields.inv_sub = 
                  // console.log(this.PartialPayment+' '+this.FinalSubTotal)
                 
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
                this.$modal.show('edit-modal');
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
       loadInvoicePrevious(){
           this.$Progress.start()
           this.success = false;
           

       },
       DeleteInvoice(id){
          var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.put('/api/v1/soft-delete-invoice/'+id) 
               .then(response => {
                  //alert("Deleted Successfully");
                   location.href="/invoice-list";
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
       
      submit() 
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
                        location.href="/invoice-list";
                        //console.log(response)
                       
                      
                    })
                    .catch(function (response) {
                        this.loaded = true;

                        if (error.response.status === 200) {
                            this.errors = error.response.data.errors || {};
                        }
                    });

               
            }
      },
      submitPaid() 
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
                    url: '/api/v1/mark-as-paid-invoice/',
                    data: bodyFormData,
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                })

                .then((response) => {

                        this.fields = {}; //Clear input fields.
                        this.loaded = true;
                        this.success = true;
                        location.href="/invoice-list";
                        //console.log(response)
                       
                      
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
        
          this.$modal.show('paid-modal');
       },
       MarkAsUnPaid(id){
          //alert(id);
          var x = confirm("Are you sure you want park this as Unpaid");
            if (x){
               this.$Progress.start()
               axios.get('/api/v1/mark-as-unpaid-invoice/'+id)
               .then(response => {
                  alert("Marked As Unpaid Successfully");
                   location.href="/invoice-list";
              this.$Progress.finish()
                     this.success = false;
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
         // alert(id);
        //  this.success = false;
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
      AccountType: function(e) 
      {
        
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
       PaidPaymnetCheck: function(e)
       {

        

               let paid_payment_amount = e.target.value;
               if(paid_payment_amount < this.FinalSubTotal || paid_payment_amount > this.FinalSubTotal){

                  alert("Paid Payment Can not be smaller or greater then Total Payable amount");
                  $("#PartialPaymnetButton").attr("disabled", true);

               }else{

                    $("#PartialPaymnetButton").attr("disabled", false);  
               }
               // PartialPaymnetButton
          

       },
       PartialPaymnetCheck: function(e)
       {

        

               let partial_payment_amount = e.target.value;
               if(partial_payment_amount > this.FinalSubTotal){

                  alert("Partial Payment Can not be greater then Total Payable amount");
                  $("#PartialPaymnetButton").attr("disabled", true);

               }else{

                    $("#PartialPaymnetButton").attr("disabled", false);  
               }
               // PartialPaymnetButton
          

       }
       
     },
     filters: {
       
     },
}
</script>