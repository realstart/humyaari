<template>
   <div>

      <!-- <div class="modal fade" :class="{ in: modalShown }" id="myModal" role="dialog">
         <div class="modal-dialog">
         
           
            <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
               <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
            
         </div>
      </div> -->
     
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Report</a></li>
            <li class="breadcrumb-item"><a href="#">Sales Vendor-Wise</a></li>
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
         <div class="row" v-if="!InvoiceList && is_invoice_all_calculated == false">
            
            <div class="col-md-12 table-responsive">
               <table class="table-striped table table-bordered table-hover" id="">
                  <thead class="" style="background:#222d32;color:white">
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Company</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-show="vendors.length" v-for="(vendor, index) in vendors" :key="vendor.id">
                        <th scope="row">{{index + 1}}</th>
                        <td>{{ vendor.name }}</td>
                        <td>{{ vendor.email }}</td>
                        <td>{{ vendor.phone }}</td>
                        <td>{{ vendor.company }}</td>
                        <td>
                           <a href="#" class="btn btn-primary" title="List Invoice" v-on:click="ViewInvoiceList(vendor.id)">Invoice List</a>
                           <!-- <a href="" class="btn btn-info" title="View Invoice"><i class="fa fa-files-o"></i></a>
                           <a href="" class="btn btn-success" title="Edit"><i class="fa fa-edit"></i></a>
                           <a href="" class="btn btn-danger" title="Delete"><i class="fa fa-trash-o-o"></i></a> -->
                        </td>
                     </tr>
                     <tr v-show="!vendors.length">
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
         <div class="row" v-if="InvoiceList && is_invoice_all_calculated == false">
            <div class="col-md-12">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4>Invoices List</h4>
                  </div>
                  <div class="panel-body">
                     <h3>Basic Info:</h3>
                     <div class="row">

                         <div class="col-md-6"> 
                             <div v-show="vendor_info.length" v-for="(vendor, index) in vendor_info" :key="vendor.id">
                                <p>Customer: {{ vendor.name }}</p>
                                <p>Contact: {{ vendor.phone }}</p>
                                <p>Email: {{ vendor.email }}</p>
                                <p>Registered on: {{ vendor.created_at }}</p>
                            </div>
                         </div>
                         <div class="col-md-6" style="margin-top: -5%;">
                             <div>
                                <h3>Purchasing Info:</h3>
                                <p >Total Purchases: <strong v-if="total_purchase > 0">BDT {{ total_purchase}}</strong><strong v-else>BDT 0</strong></p>
                                 <p>Paid Amount: <strong v-if="total_paid > 0">BDT {{ total_paid }}</strong><strong v-else>BDT 0</strong></p>
                                <p>Due Amount: <strong v-if="total_due > 0">BDT {{ total_due}}</strong><strong v-else>BDT 0</strong></p> </div>
                         </div>
                     </div>
                     
                     <hr>
                     
   
                           <div id="feedback"> <a type="submit" v-on:click="CalculateInvoiceData();" class="btn btn-primary"><i class="fa fa-calculator" aria-hidden="true"></i></a></div>
                           <div id="feedback2" v-for="(vendor, index) in vendor_info" :key="vendor.id"> <a type="submit" v-on:click="CalculateAndPrintInvoiceData(vendor.id);" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i></a></div>
                              
                       <modal name="result-modal">
                           <div class="container">
                              <div class="row">
                                 <div class="col-md-6">
                                     <circle v-if="isLoading"></circle>
                                   <ul class="list-group" style="margin-top:50px; font-size: 18px;">
                                    <li class="list-group-item">Total Purchase amount: <span class="badge badge-danger" style="font-size: 18px;"><span v-if="total_purchase_ids <= 0 "> 0 BDT</span>  <span v-else>  {{ this.total_purchase_ids}} BDT </span></span></li>
                                    <li class="list-group-item">Total Due amount <span class="badge badge-primary" style="font-size: 18px;"><span v-if="total_due_ids <= 0 "> 0 BDT</span>  <span v-else>  {{ this.total_due_ids}} BDT </span></span></li> 
                                    <li class="list-group-item">Total Paid amount <span class="badge badge-primary" style="font-size: 18px;"><span v-if="total_paid_ids <= 0 "> 0 BDT</span> <span v-else>{{ this.total_paid_ids}} BDT</span></span></li> 
                                    </ul>
                                    <button class="btn btn-danger pull-right" style="margin-top: 35px;" @click="$modal.hide('result-modal')">Close Result</button>
                                 </div>
                              </div>

                           </div>
                      </modal>   
                       
                       <br>   
                      <div class="col-md-12 table-responsive">
                        <table class="table-striped table table-bordered table-hover" id="">
                           <thead class="" style="background:#222d32;color:white">
                              <tr>
                                 <th scope="col"><input type="checkbox" v-model="selectAll"></th>
                                 <th scope="col">#</th>
                                 <th scope="col">Invoice No</th>
                                 <th scope="col">Issue Date</th>
                                 <th scope="col">Status</th>
                                 <th scope="col">Option</th>
                              </tr>
                           </thead>
                           <tbody>

                              <tr v-show="invoices.length" v-for="(invoice, index) in invoices" :key="invoice.id">
                                 <circle v-if="isLoading"></circle>
                                 <th><input type="checkbox" name="invoice_ids" v-model="selected" :value="invoice.id" number ></th>
                                 <th scope="row">{{index + 1}}</th>
                                 <td>FSTT{{ invoice.invoice_no }}</td>
                                 <td>{{ invoice.invoice_date }}</td>
                                 <td class="text-center"><span v-if="invoice.status == 'paid'" class="label label-success" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                              <span v-if="invoice.status == 'due'" class="label label-danger" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                              </td>
                                 <td>
                                    <!-- <a href="#" class="btn btn-primary" title="List Invoice">View</a> -->
                                    <a class="btn btn-info" title="Print Customer Copy" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/print'">Print C Copy</a>
                                    <a class="btn btn-danger" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/office'">Print O Copy</a>
                                    <a class="btn btn-primary" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no">Download</a>
                                    <!-- <a href="" class="btn btn-danger" title="Delete"><i class="fa fa-trash-o-o"></i></a> -->
                                 </td>
                              </tr>
                              
                              
                              <tr v-show="!vendors.length">
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
               </div>
            </div>
         </div>
        <div class="container">
             <div class="row" v-if="is_invoice_all_calculated == true">
            <a href="javascript:;" v-on:click="ReturnTo();" class="btn btn-success">Back</a>
            <br>
             <div class="row">
               <div class="col-sm-4 invoice-col">
                  From
                  <address>
                     <strong> {{ company.name}} </strong><br>{{ company.address }}<br><strong>Phone:</strong> {{ company.phone1}} | {{ company.phone2}}<br><strong>E-mail:</strong> {{ company.email}}
                  </address>
               </div>
               <!-- /.col -->
               <div class="col-sm-4 invoice-col">
                  To
                  <address>
                     <strong>{{ VendorName }}</strong><br>{{ Address }}<br><strong>Phone:</strong> {{ VendorPhone }}<br><strong>E-mail:</strong> {{ VendorEmail }}
                  </address>
               </div>
               <!-- /.col -->
            </div>
            <!-- /.row -->
            <br>
            <!-- Table row -->
            <div class="row">
               <div class="col-xs-12 table table-responsive">
                  <h3>Invoice Description</h3>
                  <table class="table table-striped">
                     <thead>
                        <tr>  
                           <th>#</th>
                           <th>Invoice No.</th>
                           <th>Issue Date</th>
                           <th>Status</th>
                           <th>Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr v-for="(invoice, index) in Description"  :key="index">
                           <td>{{ index +1}}</td>
                           <td>{{ invoice.invoice_no}}</td>
                           <td>{{ invoice.invoice_date}}</td>
                           <td>{{ invoice.status }}</td>
                           <td>{{ invoice.total_purchase}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total:{{ TotalOfAllInvoice }} </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <br>&nbsp;
               <!-- /.col -->
            </div>
         </div>  
         </div> 
      </div>
      <div class="text-center">
         <pagination v-if="pagination.last_page > 1"
            :pagination="pagination"
            :offset="5"
            @paginate="query === '' ? LoadAllInvoice() : SearchData()"   
            ></pagination>
      </div>
      
   </div>
   
</template>
<script type="text/ecmascript-6">
   export default {
     name: 'CustomerReport',
     data() {
       return {
         
         vendors: [],
         Vendor: [],
         vendor_info: [],
         vendor_id:'',
         invoices:[],
         all_invoice:[],
         single_vendor:[],
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
         VendorName:'',
         Address:'',
         VendorPhone:'',
         VendorEmail:'',
         TotalOfAllInvoice:'',
 
         pagination: {
   
             current_page: 1,
         },
             company: {

                name:'',
                address : '',
                phone1: '',
                phone2: '',
                email : '',
                id : ''
         },
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
        selectAll: {
           
            get: function () {
               
                return this.invoices ? this.selected.length == this.invoices.length : false;
                  //this.InvoiceCalculate = false;
            },
            set: function (value) {
                
                var selected = [];
                if(selected == []){
                   this.InvoiceCalculate = false;
                }else{
                   this.InvoiceCalculate = true;
                    if (value) {
                    this.invoices.forEach(function (invoices) {
                        selected.push(invoices.id);
                        
                        //console.log(selected);
                    });
                }
                //console.log(selected);
                this.selected = selected;
                }

               
            }
            
        },
       
    },
     methods: {
       
      
       loadUsers(){
   
         this.$Progress.start()
        axios.get('/api/v1/vendor-have-invoice?page='+this.pagination.current_page) 
             .then(response => {
   
                
                 this.InvoiceList = false;
                 this.vendors = response.data.data
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
             axios.get('/api/v1/search-vendors/'+this.queryField+'/'+this.query+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.vendors = response.data.data
                 this.pagination = response.data.meta
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },
       ViewInvoiceList(id){
           //alert(id)
           this.$Progress.start()
        axios.get('/api/v1/vendor-details-with-invoice/'+id) 
             .then(response => {
   
                 this.InvoiceList = true;
                 this.vendor_info = response.data.info
                 this.total_purchase = response.data.total_purchase[0].total_purchase;
                 this.total_paid = response.data.total_paid[0].total_paid;
                 this.total_due = response.data.total_due[0].total_due;
                 this.$Progress.finish()
                 axios.get('/api/v1/customer-invoice-list/'+id) 
                  .then(response => {
            
                     this.InvoiceList = true;
                     this.invoices = response.data.data
                     this.pagination = response.data.meta
                     this.$Progress.finish()
                     
                              
                     //this.$Progress.finish()
            
                  })
                  .catch(e => {
            
                     console.log(e)
                     this.$Progress.fail()
                  })

                   this.LoadAllInvoice(id)
   
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
          
       },
       LoadAllInvoice(id){
                    this.isLoading = true;
           axios.get('/api/v1/customer-invoice-list/'+id) 
            .then(response => {
              
               this.InvoiceList = true;
               this.invoices = response.data.data
               this.pagination = response.data.meta
               this.$Progress.finish()
                 this.isLoading = false;
               
                
                        
               //this.$Progress.finish()
        
            })
            .catch(e => {
        
               console.log(e)
               this.$Progress.fail()
            })
       },
       CalculateInvoiceData(){
          if(this.selected == ''){

             alert("Please choose some rows");
          }else{
             //this.$Progress.start()
             this.$modal.show('result-modal');
             
             let payload = {
            invoice_ids: this.selected
           };
          this.isLoading = true;
         let theString = "'" + payload.invoice_ids.join("','") + "'";
          axios.post('/api/v1/calculate-invoice-data', payload.invoice_ids).then(response => {
                 
                 // console.log(response)
                 this.isLoading = false;
                 this.total_purchase_ids = response.data.total_purchase[0].total_purchase;
                 this.total_paid_ids = response.data.total_paid[0].total_paid;
                 this.total_due_ids = response.data.total_due[0].total_due;
                }).catch(error => {
                   console.log(error)
                     this.$Progress.fail()
                });
          }
          
         },
       CalculateAndPrintInvoiceData(vendor_id){
          if(this.selected == ''){
                  
                  alert("Please choose some rows");
          }else{
             //this.$Progress.start()
             //this.$modal.show('result-modal');
             
             let payload = {

               invoice_ids: this.selected,
            
           };
          this.isLoading = true;
         let theString = "'" + payload.invoice_ids.join("','") + "'";
          axios.post('/api/v1/calculate-and-print-invoice-data-vendor/'+vendor_id, payload.invoice_ids,payload.vendor_id).then(response => {
                  console.log(response.data.total[0])
                 this.is_invoice_all_calculated = true;
                 this.Description = response.data.invoices;
               //   this.Customer = response.data.customer;
                 this.VendorName = response.data.vendor.name
                 this.Address = response.data.vendor.address
                 this.VendorPhone = response.data.vendor.phone
                 this.VendorEmail = response.data.vendor.email
                 this.TotalOfAllInvoice = response.data.total[0].total

                }).catch(error => {
                   console.log(error)
                     this.$Progress.fail()
                });
          }
          
         },
         CompanyInfo(){

             axios.get('/api/v1/companny-info') 
             .then(response => {
   
                // console.log(response.data)
                this.$modal.show('edit-modal');
                this.company.name = response.data.data[0].company_name
                this.company.address = response.data.data[0].company_address
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
       ReturnTo(){
            this.is_invoice_all_calculated = false; 
       }
           
      
         },
     filters: {
       
     },
     
   }
    
</script>

<style scoped>
   #feedback { 
				height: 30px; 
				width: 63px; 
				position: fixed; 
				right: 0;
				top: 48%; 
				z-index: 1000;
				transform: rotate(-90deg);
				-webkit-transform: rotate(-90deg); 
				-moz-transform: rotate(-90deg); 
				-o-transform: rotate(-90deg); 
				filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
			}

			#feedback a { 
				display: block; 
				background: #06c; 
				height:31px; 
				width: 50px; 
				padding: 6px 16px;
				color: #fff; 
				font-family: Arial, sans-serif; 
				font-size: 17px; 
				font-weight: bold; 
				text-decoration: none; 
				border-bottom: solid 1px #333; border-left: solid 1px #333; border-right: solid 1px #fff;
			}

			#feedback a:hover { 
         background: #d61111;
         font-size: 20px;
         width: 55px;
        
			}
   #feedback2 { 
				height: 30px; 
				width: 63px; 
				position: fixed; 
				right: 0;
				top: 56%; 
				z-index: 1000;
				transform: rotate(-90deg);
				-webkit-transform: rotate(-90deg); 
				-moz-transform: rotate(-90deg); 
				-o-transform: rotate(-90deg); 
				filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
			}

			#feedback2 a { 
				display: block; 
				background: #000000; 
				height:31px; 
				width: 50px; 
				padding: 6px 16px;
				color: #fff; 
				font-family: Arial, sans-serif; 
				font-size: 17px; 
				font-weight: bold; 
				text-decoration: none; 
				border-bottom: solid 1px #333; border-left: solid 1px #333; border-right: solid 1px #fff;
			}

			#feedback2 a:hover { 
        background: #d61111;
        font-size: 20px;
        width: 55px;
        
			}
</style>

