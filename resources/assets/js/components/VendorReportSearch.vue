<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Report</a></li>
            <li class="breadcrumb-item"><a href="#">Sales Vendor-Wise</a></li>
         </ol>
      </nav>
      <div class=''>
         <vue-progress-bar></vue-progress-bar>
         <div class="row" v-if="!success">
            <div class="col-md-12">
               <div class="" style="margin-bottom: 15px">
                  <div class="row">
                     <div class="col-md-1" style="padding-top: 25px;">
                        <strong>Filters</strong>
                     </div>
                     <br>
                     <div class="col-md-2 form-group">
                        Select Vendor:
                        <select class="js-example-basic-single form-control"  id="vendor_inf" name="vendor_inf">
                           <option value=" " aria-readonly="true">Select Vendor</option>
                           <option
                              v-for="vendor in VendorsSelect"
                              :key="vendor.id"
                              :value="vendor.id"
                              >{{ vendor.name }}</option>
                        </select>
                     </div>
                     <!-- <div class="col-md-3 form-group">
                        Status 
                        <select v-model="invoice_status" class="form-control" id="invoice_status" name="invoice_status">
                           <option value=" ">-- Select Option --</option>
                           <option value="paid">Paid</option>
                           <option value="due">Due</option>
                           <option value="partial">Partial</option>
                        </select>
                        </div>
                        -->
                     <div class="col-md-2 form-group">
                        From: <br>
                        <div class="input-group date mdate">
                           <input type="text"
                              class="form-control required"
                              id="date_f"
                              name="date_from"
                              v-model="date_from"
                              data-date-format="yyyy/mm/dd"
                              autocomplete="off"
                              v-on:focusout="setDate('date_from', null, 'date_from')"
                              data-provide="datepicker">
                           <span class="input-group-addon"><span class="icon-calendar"></span></span>
                        </div>
                        <!-- <p>Date: <input type="text" class="datepicker"></p> -->
                     </div>
                     <div class="col-md-2 form-group">
                        To: <br>
                        <div class="input-group date mdate">
                           <input type="text"
                              class="form-control required"
                              id="date_t"
                              name="date_to"
                              v-model="date_to"
                              data-date-format="yyyy/mm/dd"
                              autocomplete="off"
                              v-on:focusout="setDate('date_to', null, 'date_to')"
                              data-provide="datepicker">
                           <span class="input-group-addon"><span class="icon-calendar"></span></span>
                        </div>
                     </div>
                     <div class="col-md-1 form-group" style="">
                        &nbsp;&nbsp;&nbsp;&nbsp;<span><a class="btn btn-success form-control" style="width: 140px;" v-on:click="LoadVendorInvoices()">Load Data</a></span> 
                     </div>
                  </div>
               </div>
            </div>
              <span clss="card" v-show="VendorDetailsSuccess">
                  <div class="col-md-12">
                     <div class="col-md-8" style=""> <h3><strong>Vendor Details:</strong></h3> <br>

                     <div class="col-md-2">
                        <span v-if="vendor.logo == null">
                           <p style="font-size:16px;font-weight:bold;text-align:center">No Image Found</p> 
                        </span>
                        <span v-if="vendor.logo != null">
                            <img style="width: 70px;" :src="'/uploads/vendors/'+ vendor.logo" />
                        </span>
                     </div>
                     <div class="col-md-6">
                        <ul class="list-group" style="margin-top: -10px;">
                           <li class="list-group-item"> <strong>Name:</strong> {{ vendor.name }}</li>   
                           <li class="list-group-item"> <strong> Phone:</strong> {{ vendor.phone }} </li>   
                           <li class="list-group-item"> <strong> Email: </strong> {{ vendor.email }} </li>   
                        </ul>  
                     </div>
                       
                        

                     </div>
                               

                  </div>
               </span>
               <span>
               <div class="col-md-12" v-if="ThreeBoxSuccess" style="margin-bottom:15px;">
                   <div class="col-md-4" style="border:0px solid black"><h3><strong class="label label-info" style="font-size:20px;text-transform:uppercase">GDS:</strong></h3> <br>
                        <ul class="list-group" style="margin-top: -10px;">
                           <li class="list-group-item"> <strong>Number of invoices:</strong> {{ GDSArray.total_invoice_gds }}</li>   
                           <li class="list-group-item"> <strong> Total Amount:</strong> <span v-if="GDSArray.total_amount_gds == null">0</span> {{ GDSArray.total_amount_gds }} BDT</li>   
                           <li class="list-group-item"> <strong> Total Paid: </strong> <span v-if="GDSArray.total_paid_gds == null">0</span>{{ GDSArray.total_paid_gds }} BDT</li>   
                           <li class="list-group-item"> <strong> Total Due: </strong> <span v-if="GDSArray.total_due_gds == null">0</span>{{ GDSArray.total_due_gds }} BDT</li>   
                           <li class="list-group-item"> <strong> Total Partial: </strong> <span v-if="GDSArray.total_partial_gds == null">0</span>{{ GDSArray.total_partial_gds }} BDT</li>   
                        </ul> 
                   </div>
                  <div class="col-md-4" style="border:0px solid black"><h3><strong class="label label-success" style="font-size:20px;text-transform:uppercase">Service:</strong></h3> <br>
                         <ul class="list-group" style="margin-top: -10px;">
                            <li class="list-group-item"> <strong>Number of invoices:</strong> {{ ServiceArray.total_invoice_service }}</li>   
                           <li class="list-group-item"> <strong> Total Amount:</strong> <span v-if="ServiceArray.total_amount_service == null">0</span> {{ ServiceArray.total_amount_service }} BDT</li>   
                           <li class="list-group-item"> <strong> Total Paid: </strong> <span v-if="ServiceArray.total_paid_service == null">0</span> {{ ServiceArray.total_paid_service }} BDT</li>   
                           <li class="list-group-item"> <strong> Total Due: </strong> <span v-if="ServiceArray.total_due_service == null">0</span> {{ ServiceArray.total_due_service }} BDT</li>   
                           <li class="list-group-item"> <strong> Total Partial: </strong> <span v-if="ServiceArray.total_partial_service == null">0</span> {{ ServiceArray.total_partial_service }} BDT</li>   
                        </ul> 
                  </div>
                  <div class="col-md-4" style="border:0px solid black"><h3><strong class="label label-warning" style="font-size:20px;text-transform:uppercase">Third Party:</strong></h3> <br>
                         <ul class="list-group" style="margin-top: -10px;">
                            <li class="list-group-item"> <strong>Number of invoices:</strong> {{ ThirdPartyArray.total_invoice_tp }}</li>   
                           <li class="list-group-item"> <strong> Total Amount:</strong> <span v-if="ThirdPartyArray.total_amount_tp == null">0</span>{{ ThirdPartyArray.total_amount_tp }} BDT</li>   
                           <li class="list-group-item"> <strong> Total Paid: </strong> <span v-if="ThirdPartyArray.total_paid_tp == null">0</span>{{ ThirdPartyArray.total_paid_tp }} BDT</li>   
                           <li class="list-group-item"> <strong> Total Due: </strong> <span v-if="ThirdPartyArray.total_due_tp == null">0</span>{{ ThirdPartyArray.total_due_tp }} BDT</li>   
                           <li class="list-group-item"> <strong> Total Partial: </strong> <span v-if="ThirdPartyArray.total_partial_tp == null">0</span>{{ ThirdPartyArray.total_partial_tp }} BDT</li>   
                        </ul> 
                  
                  </div>

               </div>
               </span>
         
              <div class="col-md-12 table-responsive">
               <section class="panel">
                  <header class="panel-heading">
                     Sales Report Of Customers
                     <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <!-- <a href="javascript:;" class="fa fa-cog"></a> -->
                        <!-- <a href="javascript:;" class="fa fa-times"></a> -->
                     </span>
                  </header>
                  <div class="panel-body" style="display: block;">
                     <table class="table table-hover general-table">
                         <thead >
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Invoice No</th>
                        <th scope="col">Invoice Type</th>
                        <th scope="col">Date</th>
                        <th scope="col">Vendor Paymnetsdsdsds</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Options</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-show="invoices.length" v-for="(invoice, index) in invoices" :key="invoice.id">
                        <th scope="row">{{index + 1}}</th>
                        <td>FSTT{{ invoice.invoice_no }}</td>
                        <td> 
                           <span  v-if="invoice.flag == 'GDS'" class="label label-info" style="font-size:14px;text-transform:uppercase"> {{ invoice.flag }}</span> 
                           <span  v-if="invoice.flag == 'Service'" class="label label-success" style="font-size:14px;text-transform:uppercase"> {{ invoice.flag }}</span> 
                           <span  v-if="invoice.flag == 'third party'" class="label label-warning" style="font-size:14px;text-transform:uppercase"> {{ invoice.flag }}</span> 
                        </td>
                        <td>{{ invoice.invoice_date }}</td>
                        <td>
                           <span v-if="invoice.flag == 'GDS'">{{ invoice.airline_payment }}</span>
                           <span v-else> {{ invoice.total_receivable }}</span>
                        </td>
                        <td class="text-center"><span v-if="invoice.status == 'paid'" class="label label-success" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                           <span v-if="invoice.status == 'due'" class="label label-danger" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                           <span v-if="invoice.status == 'partial'" class="label label-warning" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                        </td>
                        <td>
                          
                           <router-link :to="{ name: 'InvoiceDetails', params: { id: invoice.invoice_no } }" class="btn btn-success">View</router-link>
         
                           <a v-if="user.role == 'admin'" href="#" class="btn btn-danger" title="Delete" v-on:click="DeleteInvoice(invoice.invoice_no)"><i class="fa fa-trash-o"></i></a>
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
               </section>
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
   import helpers from '../mixins/helpers';
   export default {
    name: 'CustomerReport',
    mixins: [helpers],
    data() {
      return {
        
   
        Vendor: [],
        VendorsSelect: [],
        vendor_id:'',
        invoices: [],
        GDSArray:{

            total_invoice_gds: '',
            total_amount_gds: '',
            total_paid_gds: '',
            total_due_gds: '',
            total_partial_gds: '',
        },
        ServiceArray:{

            total_invoice_service: '',
            total_amount_service: '',
            total_paid_service: '',
            total_due_service: '',
            total_partial_service: '',
        },
        ThirdPartyArray:{

            total_invoice_tp: '',
            total_amount_tp: '',
            total_paid_tp: '',
            total_due_service_tp: '',
            total_partial_service_tp: '',
        },
        success: null,
        VendorDetailsSuccess: false,
        ThreeBoxSuccess: false,
        date_from:'',
        date_to:'',
        invoice_status: '',
        pagination: {
   
            current_page: 1,
        },
        vendor: {

            name:'',
            address : '',
            company : '',
            email: '',
            phone: '',
             logo: '',
               
         },
           
         fields: {
   
           inv_id:''
        },
        options: [
           
        ],
       loadSuccessMessage: '',
        errors: {},
       loaded: true,
       user: JSON.parse(localStorage.getItem('user')),
       account_numbers: [],

      }
    },
    watch:{
       query:function(newQuery, oldQuery){
   
          if(newQuery === ''){
             this.loadUsers();
          }else{
   
             this.SearchData();
          }
       },
       
    },
    created() {
     
      this.GetVendorsName();
    },
   
    methods: {
       
      
      
      GetVendorsName(){
        //   window.history.go();
          axios.get('/api/v1/vendor-name') 
            .then(response => {
   
                  this.VendorsSelect = response.data.vendor_names
                
            })
            .catch(e => {
   
                console.log(e)
                 this.$Progress.fail()
            })
   
      },
       LoadVendorInvoices: function(e) {
             
         //alert("Start");
          this.$Progress.start(); 
      
           let date_f, date_t;
           //this.invoice_status = $("#invoice_status").val();
            this.vendor_id = $("#vendor_inf").val();       
            this.date_from = $( "#date_f" ).val();
            this.date_to= $( "#date_t" ).val();
   
            date_f = moment(this.date_from).format('YYYY-MM-DD');
            date_t = moment(this.date_to).format('YYYY-MM-DD');
          // console.log(this.invoice_status+' '+this.vendor_id+' '+date_f+ ' '+ date_t);
   
           axios.get('/api/v1/invoice-report-vendor/'+date_f+'/'+date_t+'/'+this.vendor_id+'/?page='+this.pagination.current_page)
            .then(response => {
   
               
                this.success = false;
                this.ThreeBoxSuccess = true;
                this.invoices = response.data.data
                this.pagination = response.data.meta

                axios.get('/api/v1/single-vendor-details/'+this.vendor_id)
                  .then(response => {
         
                     
                     this.VendorDetailsSuccess = true;
                     this.vendor.name = response.data.data.name
                     this.vendor.address = response.data.data.address
                     this.vendor.company = response.data.data.company
                     this.vendor.phone = response.data.data.phone
                     this.vendor.email = response.data.data.email
                     this.vendor.logo = response.data.data.logo
                
                     
                  })
                  .catch(e => {
         
                     console.log(e)
                     this.$Progress.fail()
                  }) 
                axios.get('/api/v1/gds-details-vendor/'+this.vendor_id)
                  .then(response => {
         
                     
                    console.log(response)
                    this.GDSArray.total_invoice_gds = response.data.total_invoice[0].total_invoice
                    this.GDSArray.total_amount_gds = response.data.total_amount[0].total_amount
                    this.GDSArray.total_paid_gds = response.data.total_paid[0].total_paid
                    this.GDSArray.total_due_gds = response.data.total_due[0].total_due
                    this.GDSArray.total_partial_gds = response.data.total_partial[0].total_partial

                    console.log(this.GDSArray.total_invoice_gds)
                
                     
                  })
                  .catch(e => {
         
                     console.log(e)
                     this.$Progress.fail()
                  }) 

                  axios.get('/api/v1/service-details-vendor/'+this.vendor_id)
                  .then(response => {
         
                     
                   
                    this.ServiceArray.total_invoice_service = response.data.total_invoice[0].total_invoice
                    this.ServiceArray.total_amount_service = response.data.total_amount[0].total_amount
                    this.ServiceArray.total_paid_service = response.data.total_paid[0].total_paid
                    this.ServiceArray.total_due_service = response.data.total_due[0].total_due
                    this.ServiceArray.total_partial_service = response.data.total_partial[0].total_partial
                
                     
                  })
                  .catch(e => {
         
                     console.log(e)
                     this.$Progress.fail()
                  }) 


                  axios.get('/api/v1/third-party-details-vendor/'+this.vendor_id)
                  .then(response => {
         
                    this.ThirdPartyArray.total_invoice_tp = response.data.total_invoice[0].total_invoice
                    this.ThirdPartyArray.total_amount_tp = response.data.total_amount[0].total_amount
                    this.ThirdPartyArray.total_paid_tp = response.data.total_paid[0].total_paid
                    this.ThirdPartyArray.total_due_tp = response.data.total_due[0].total_due
                    this.ThirdPartyArray.total_partial_tp = response.data.total_partial[0].total_partial
                     
                  })
                  .catch(e => {
         
                     console.log(e)
                     this.$Progress.fail()
                  }) 
               
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
   // $(document).ready(function() {
   //   $('.js-example-basic-single').select2();
   
   // });
   
   //   $( function datepick() {
   //     $(".datepicker").datepicker();
   //   } );
   
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
   ul li {

      background: none;
      border: none;
      margin-bottom: -10px;
   }

</style>