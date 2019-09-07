<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:;" v-on:click="loadPrevious()" >Reports and Data</a></li>
            <li v-if="!success" class="breadcrumb-item"><a href="">Invoice Report</a></li>
            <li v-if="success" class="breadcrumb-item"><a href="#">Invoice Details</a></li>
         </ol>
         <ol class="breadcrumb" v-if="success">
            Back to List
            <li v-if="success" class="breadcrumb-item"><a href="#" v-on:click="loadInvoicePrevious()">List of All Invoice</a></li>
         </ol>
      </nav>
      <!-- <div class="text-center">
         <pagination v-if="pagination.last_page > 1"
         :pagination="pagination"
         :offset="5"
         @paginate="loadUsers()"     
         ></pagination>
         </div> -->
      <div class=''>
         <vue-progress-bar></vue-progress-bar>
         <div v-if="!viewSuccess" class="row">
            <div class="col-md-12">
               <div class="" style="margin-bottom: 15px">
                  <div class="row">
                     <div class="col-md-1" style="padding-top: 25px;">
                        <strong>Filters</strong>
                     </div>
                     <div class="col-md-2 form-group">
                        From: <br>
                        <div class="input-group date mdate">
                           <input type="text"
                              class="form-control required"
                              id="date_f"
                              name="date_from"
                              v-model="date_from"
                              data-date-format="mm/dd/yyyy"
                              autocomplete="off"
                              v-on:focusout="setDate('date_from', null, 'date_from')"
                              data-provide="datepicker">
                           <span class="input-group-addon"><span class="icon-calendar"></span></span>
                        </div>
                     </div>
                     <div class="col-md-2 form-group">
                        To: <br>
                        <div class="input-group date mdate">
                           <input type="text"
                              class="form-control required"
                              id="date_t"
                              name="date_to"
                              v-model="date_to"
                              data-date-format="mm/dd/yyyy"
                              autocomplete="off"
                              v-on:focusout="setDate('date_to', null, 'date_to')"
                              data-provide="datepicker">
                           <span class="input-group-addon"><span class="icon-calendar"></span></span>
                        </div>
                     </div>
                     <div class="col-md-1 form-group" style="">
                        &nbsp;&nbsp;&nbsp;&nbsp;<span><a class="btn btn-success form-control" style="width: 140px;" v-on:click="LoadFilterdInvice()">Load Data</a></span> 
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-12 table-responsive">
               <section class="panel">
                  <header class="panel-heading">
                     Invoices
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
                                 <!-- <a class="btn btn-success" title="View Invoice" href="#" v-on:click="ViewInvoice(invoice.invoice_no)"><i class="fa fa-file"></i> View</a> -->
                                 <router-link :to="{ name: 'InvoiceDetails', params: { id: invoice.invoice_no } }" class="btn btn-success"><i class="fa fa-file"></i>View</router-link>
                                 <a class="btn btn-warning" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/print'">Print Customer Copy</a>
                                 <a class="btn btn-danger" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/office'">Print Office Copy</a>
                                 <a class="btn btn-primary" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no">Download</a>
                                 <!-- <a href="#" class="btn btn-danger" title="Delete"><i class="fa fa-trash-o"></i></a> -->
                              </td>
                           </tr>
                           <tr v-show="invoices && !invoices.length">
                              <td colspan="8">
                                 <div class="alert alert-danger text-center" role="alert">
                                    Opps! Sorry no data found
                                 </div>
                              </td>
                           </tr>
                           <tr v-show="invoice_status == ''">
                              <td colspan="8">
                                 <div class="alert alert-danger text-center" role="alert">
                                    Select Date(FROM - TO) , Payment Status And press load button
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
      <br><br>
      <div v-if="!viewSuccess" class="text-center" >
         <pagination v-if="pagination.last_page > 1"
            :pagination="pagination"
            :offset="5"
            @paginate="invoice_status === AccountType()"  
            ></pagination>
      </div>
   </div>
</template>
<script type="text/ecmascript-6">
   import helpers from '../mixins/helpers';
   export default {
     name: 'InvoiceReport',
     mixins: [helpers],
     data() {
       return {
         account_numbers:[],
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
         success: null,
         InvoiceUniqID: '',
         date_from:'',
         date_to:'',
         invoice_status:'select date',
         printUrl: '#',
         printUrlOffice: '#',
         downloadUrl: '#',
         pagination: {
   
             current_page: 1,
         },
         //fields: {},
           company: {
   
                name:'',
                address : '',
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
         errors: {},
         viewSuccess: null,
         InvoiceDueDate:'',
         PartialPayment:'',
         loaded : '',
          customers: [],
         query:'',
         queryField: 'name',
         pagination: {
   
             current_page: 1,
         },
         Reload: false,
         fields: {},
         errors: {},
         success: false,
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
         modalShow: false,
         isLoading: false,
         is_invoice_all_calculated:false,
         Description:[],
         CustomerName:'',
         Address1:'',
         Address2:'',
         CustomerPhone:'',
         CustomerEmail:'',
         TotalOfAllInvoice:'',
   
   
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
          invoiceTypeService : false,
           invoiceTypeProduct : false,
       }
     },
     created() {
       this.CompanyInfo();
     },
     methods: {
      
   
       LoadFilterdInvice: function(e) {
              
            
           this.$Progress.start(); 
       
             let date_f, date_t;
             this.invoice_status = $("#invoice_status").val();
             this.date_from = $( "#date_f" ).val();
             this.date_to= $( "#date_t" ).val();
   
             date_f = moment(this.date_from).format('YYYY-MM-DD');
             date_t = moment(this.date_to).format('YYYY-MM-DD');
        
            axios.get('/api/v1/invoice-report/'+date_f+'/'+date_t+'/'+this.invoice_status+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                // console.log(response.data.data)
                
                 this.success = false;
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
      
       ViewInvoice(InvoiceUniqID){
   
         this.$Progress.start()
         axios.get('/api/v1/view-invoice/'+InvoiceUniqID) 
             .then(response => {
   
                
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
   
                  this.SubTotal = parseFloat(response.data.data.total_receivable)
   
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
                  
                   //console.log(CustomerName)
                 
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
                //this.success = false;
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
   
                        // this.fields = {}; //Clear input fields.
                        // this.loaded = true;
                        // this.success = true;   
                        // location.href= "/invoice-report";
                        console.log(response)
                     
                      
                    })
                    .catch(function (response) {
                        this.loaded = true;
   
                        if (error.response.status === 200) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
   
               
            }
   
       },
       loadInvoicePrevious(){
           this.$Progress.start()
           this.success = false;
           
   
       },
         AccountTypes: function(e) {
        
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
       PartialPaymnetCheck: function(e){
   
        
   
               let partial_payment_amount = e.target.value;
               if(partial_payment_amount > this.FinalSubTotal){
   
                  alert("Partial Payment Can not be greater then Total Payable amount");
                  $("#PartialPaymnetButton").attr("disabled", true);
   
               }else{
   
                    $("#PartialPaymnetButton").attr("disabled", false);  
               }
               // PartialPaymnetButton
          
   
       },
             MarkAsPaid(id){
        
          this.$modal.show('paid-modal');
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
         loadPrevious(){
           this.$Progress.start()
           this.viewSuccess = false;
           
   
       },
        PaidPaymnetCheck: function(e){
   
        
   
               let paid_payment_amount = e.target.value;
               if(paid_payment_amount < this.FinalSubTotal || paid_payment_amount > this.FinalSubTotal){
   
                  alert("Paid Payment Can not be smaller or greater then Total Payable amount");
                  $("#PartialPaymnetButton").attr("disabled", true);
   
               }else{
   
                    $("#PartialPaymnetButton").attr("disabled", false);  
               }
               // PartialPaymnetButton
          
   
       },
     },
     filters: {
       
     },
     
   }
   
   // $('.date').datepicker({
   //    todayHighlight: true,
   // });
   
</script>