<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <h3>Invoices By {{ user.firstname}} {{ user.lastname}} ({{ username }})</h3>
         </ol>
         <ol class="breadcrumb" v-if="success">
            Back to
            <li v-if="success" class="breadcrumb-item"><a href="#" v-on:click="loadInvoicePrevious()">List of All Invoice</a></li>
         </ol>
      </nav>
      <div class=''>
         <vue-progress-bar></vue-progress-bar>
         <div v-if="!success" class="row">
            <div class="col-md-12">
               <div class="mb-3" style="margin-bottom: 15px">
                  <div class="row">
                     <div class="col-md-2">
                        <strong>Search By</strong>
                     </div>
                     <div class="col-md-2">
                        <select v-model="queryField" class="form-control" name="" id="fileds">
                           <option value="">-- Select Option --</option>
                           <option value="invoice_no">Invoice No</option>
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
                     Sales Tracker
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
                        <td class="text-center"><span v-if="invoice.status == 'paid'" class="label label-success" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                           <span v-if="invoice.status == 'due'" class="label label-danger" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                        </td>
                        <td>
                           <!-- <a class="btn btn-success" title="View Invoice" href="#" v-on:click="ViewInvoice(invoice.invoice_no)"><i class="fa fa-file"></i> View</a> -->
                           <router-link :to="{ name: 'InvoiceDetails', params: { id: invoice.invoice_no } }" class="btn btn-success">View</router-link>
                           <a class="btn btn-warning" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/print'">Print Customer Copy</a>
                           <a class="btn btn-danger" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/office'">Print Office Copy</a>
                           <a class="btn btn-primary" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no ">Download</a>
                           <a href="#" class="btn btn-danger" title="Delete" v-on:click="DeleteInvoice(invoice.invoice_no)"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <tr v-show="!invoices.length">
                        <td colspan="12">
                           <div class="alert alert-danger" role="alert">
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
      <div v-if="!success" class="text-center" >
         <pagination v-if="pagination.last_page > 1"
            :pagination="pagination"
            :offset="5"
            @paginate="query === '' ? loadInvoice() : SearchData()"  
            ></pagination>
      </div>
   </div>
</template>
<script type="text/ecmascript-6">
   export default {
     name: 'SalesTracker',
     data() {
       return {
         
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
         success: null,
         InvoiceUniqID: '',
         query:'',
         queryField: 'name',
         printUrl: '#',
         printUrlOffice: '#',
         downloadUrl: '#',
         pagination: {
   
             current_page: 1,
         },
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
   
            inv_id:''
         },
         username: '',
         user: JSON.parse(localStorage.getItem('user')),
          invoiceTypeService : false,
           invoiceTypeProduct : false,
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
     computed: {
       
       
    },
     methods: {
       
      
       loadUsers(){
         this.currentUserID = this.user.id
         this.username = this.user.name
         this.$Progress.start()
        axios.get('/api/v1/user-have-invoice/'+this.currentUserID+'?page='+this.pagination.current_page) 
             .then(response => {
   
                
                 this.InvoiceList = false;
                 this.invoices = response.data.data
                 this.pagination = response.data.meta
                 this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },
       SearchData(){
   
           this.$Progress.start()
             axios.get('api/v1/search-invoice-for-user/'+this.queryField+'/'+this.query+'/'+this.currentUserID+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.invoices = response.data.data
                 this.pagination = response.data.meta
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
         ViewInvoice(InvoiceUniqID){
   
         this.$Progress.start()
         axios.get('/api/v1/view-invoice/'+InvoiceUniqID) 
             .then(response => {
   
                
                  this.success = true;
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
   
      
     
       
      
         },
     filters: {
       
     },
     
   }
    
</script>
<style scoped>
</style>