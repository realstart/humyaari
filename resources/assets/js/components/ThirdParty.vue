<template>
   <div class="form-container">
      <div v-if="success" class="callout callout-success" v-show="!viewSuccess">
         <h4><i class="fa fa-info-circle"></i> Invoice {{ isEdit ? 'updated' : 'create' }}</h4>
         <!-- <a class="btn btn-primary" target="_blank" href="javascript:;" v-on:click="ViewInvoice(InvoiceNoForView)">View Invoice</a> -->
         <router-link :to="{ name: 'InvoiceDetails', params: { id: InvoiceIdForView } }" class="btn btn-success">View</router-link>
         <a class="btn btn-warning" target="_blank" :href="printUrl">Print Customer Copy</a>
         <a class="btn btn-danger" target="_blank" :href="printUrlOffice">Print Office Copy</a>
         <a class="btn btn-primary" target="_blank" :href="downloadUrl">Download</a> &nbsp;&nbsp;&nbsp; or &nbsp;&nbsp;&nbsp; 
         <a class="btn btn-info" href="#" v-on:click.prevent="resetForm()">Create new</a>
      </div>
      <form v-if="!success" method="post" id="create_invoice" class="fmargin">
         <div class="row">
            <div class="panel panel-primary">
               <div class="panel-body chead">
                  <div class="col-md-4">
                     <h3>{{ isEdit ? 'Edit' : 'create' }} Invoice</h3>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <small>Vendor Name</small>
                        <div class="input-group" :class="{'has-error': formErrors.has('vendorId') }">
                           <select
                              v-model="vendorId"
                              class="form-control"
                              name="vendorId"
                              v-validate="'min_value:1'"
                              >
                              <option value="0" disabled>--Select Vendor--</option>
                              <option
                                 v-for="vendor in vendors"
                                 :key="vendor.id"
                                 :value="vendor.id"
                                 >{{ vendor.name }}</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <small>Invoice Date</small>
                        <div class="input-group date mdate" id="tsn_date">
                           <input
                              type="text"
                              name="invoice_date"
                              class="form-control required"
                              data-date-format="mm/dd/yyyy"
                              data-provide="datepicker"
                              v-model="invoiceDate"
                              v-on:focusout="updateDate('invoice_date')"
                              >
                           <span class="input-group-addon">
                           <span class="icon-calendar"></span>
                           </span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <small>Invoice Due Date</small>
                        <div class="input-group date" id="tsn_due">
                           <input
                              type="text"
                              class="form-control required"
                              name="invoice_due_date"
                              data-date-format="mm/dd/yyyy"
                              data-provide="datepicker"
                              v-model="invoiceDueDate"
                              v-on:focusout="updateDate('invoice_due_date')"
                              >
                           <span class="input-group-addon">
                           <span class="icon-calendar"></span>
                           </span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-2 float-right">
                     <small>Invoice Number</small>
                     <div class="input-group">
                        <span class="input-group-addon">#FSTT</span>
                        <input
                           type="text"
                           name
                           id="invoice_id"
                           class="form-control required"
                           placeholder="Invoice Number"
                           value
                           :disabled="isEdit"
                           v-model="invoiceNo"
                           >
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <a v-on:click.prevent="addService()" href="#" class="btn theme-submit-btn btn-xs add-service-row">
         <span class="icon-plus"></span>
         <strong>Add Service</strong>
         </a>
         <div class="row bg-light-blue color-palette margin-t padding-f">
            <div class="ol-xs-12 col-md-6 col-lg-5 mol-5">
               <div class="col-md-12">Service Name</div>
            </div>
            <div class="col-xs-12 col-md-2 col-lg-1 mol-1">QTY</div>
            <div class="col-xs-12 col-md-2 col-lg-2 mol-2">UNIT PRICE</div>
            <!-- <div class="col-xs-12 col-md-6 col-lg-2 mol-2">TAX</div> -->
            <div class="col-xs-12 col-md-2 col-lg-2 mol-2" style="border-right:0">SUBTOTAL</div>
         </div>
         <div class="row panel panel-default" id="service_list">
            <br>
            <article v-for="(service, index) in services" :key="index" class="mr6">
               <div class="col-xs-12 col-md-6 col-lg-6 mol-5">
                  <div class="input-group" :class="{'has-error': formErrors.has('service_name_' + index) }">
                     <span class="input-group-addon" style="display: none">
                     <a href="#" class="btn btn-success btn-xs product-select">
                     <span class="icon-database" title="Select Product From List"></span>List
                     </a>
                     </span>
                     <input
                        style="width:440px;margin-right:0px"
                        type="text"
                        class="form-control item-input bill_saman hbh required"
                        id="bill_saman[]"
                        placeholder="Enter Service Name"
                        v-model="service.name"
                        :name="'service_name_' + index"
                        v-validate="'required'"
                        >
                     <input
                        type="hidden"
                        class="bill_service_pid"
                        id="bill_service_pid[]"
                        name="bill_service_pid[]"
                        value="0"
                        >
                    
                  </div>
               </div>
               <div class="col-xs-12 col-md-6 col-lg-1 mol-1">
                  <div
                     class="input-group"
                     :class="{'has-error': formErrors.has('service_quantity_' + index) }"
                     >
                     <input
                        type="number"
                        class="form-control saman_qty jod-service required"
                        v-model="service.quantity"
                        :name="'service_quantity_' + index"
                        v-validate="'required'"
                        >
                  </div>
               </div>
               <div class="col-xs-12 col-md-6 col-lg-2 mol-2">
                  <div
                     class="input-group"
                     :class="{'has-error': formErrors.has('service_price_' + index) }"
                     >
                     <span class="input-group-addon currenty">BDT</span>
                     <input
                        type="number"
                        class="form-control jod-service billsaman_price required"
                        placeholder="0.00"
                        v-model="service.price"
                        :name="'service_price_' + index"
                        v-validate="'required'"
                        >
                  </div>
               </div>
               <div class="col-xs-12 col-md-6 col-lg-2 mol-2">
                  <div class="input-group">
                     <span class="input-group-addon">BDT</span>
                     <span class="form-control jod-service-sub">{{ service.quantity * service.price }}</span> 
                     <!-- point 1 -->
                     <input type="hidden" class="ttInput" name="total[]" id="total-0" value="0">
                  </div>
                  <div class="clear"></div>
               </div>
                <span
                        v-on:click="removeService(index)"
                        class="input-group-addon"
                        style="width:20px;border:1px solid #d2d6de"
                        >
                     <a href="#" class="btn btn-danger btn-xs delete-row" title="Delete Product row">
                             <i class="fa fa-trash-o"></i>
                     </a>
                     </span>
               <br>
               <br>
            </article>
         </div>
         <!-- Price Break Down -->
         <div class="row margin-t">
             <div class="col-md-6">
               <section class="panel">
                  <header class="panel-heading">
                     Customer Information
                  </header>
                  <div class="panel-body">
                     <div class="position-center">
                        <div class="form-group" :class="{'has-error': formErrors.has('name') }">
                           <!-- <label style="width:12%">Name:</label> -->
                           <label for="name">Name</label>
                           <input
                              style=""
                              type="text"
                              class="form-control"
                              name="name"
                              placeholder="Customer name"
                              tabindex="1"
                              v-model="customer.name"
                              v-validate="'required'"
                              >
                        </div>
                        <div class="form-group" :class="{'has-error': formErrors.has('address1') }">
                           <label style="width:12%">Address:</label>
                           <input
                              class="form-control"
                              type="text"
                              name="address1"
                              placeholder="Address"
                              tabindex="3"
                              v-model="customer.address1"
                              v-validate="'required'"
                              >
                        </div>
                        <div class="form-group" :class="{'has-error': formErrors.has('address2') }">
                           <label style="width:12%">City:</label>
                           <input
                              class="form-control"
                              type="text"
                              name="address2"
                              placeholder="City, Country, Postal Code"
                              tabindex="5"
                              v-model="customer.address2"
                              v-validate="'required'"
                              >
                        </div>
                        <div class="form-group" :class="{'has-error': formErrors.has('phone') }">
                           <label style="width:12%">Phone:</label>
                           <input
                              class="form-control"
                              type="text"
                              name="phone"
                              placeholder="Phone number"
                              tabindex="8"
                              v-model="customer.phone"
                              v-validate="'required'"
                              >
                        </div>
                        <div class="form-group" :class="{'has-error': formErrors.has('email') }">
                           <label style="width:12%">Email:</label>
                           <input
                              class="form-control"
                              type="text"
                              name="email"
                              placeholder="Email"
                              tabindex="8"
                              v-model="customer.email"
                              v-validate="'required|email'"
                              >
                        </div>
                        <div class="form-group no-margin-bottom">
                           <a
                              href="#"
                              class="grahk-select btn btn-info"
                              data-toggle="modal"
                              data-target="#insert_customer"
                              >Select existing customer</a>&nbsp;
                           <input
                              type="button"
                              id="clear-form"
                              class="btn btn-danger dropdown-toggle"
                              value="Clear"
                              v-on:click="clearCustomer()"
                              >
                        </div>
                     </div>
                  </div>
               </section>
            </div>
            <div class="col-md-6">
               <section class="panel">
                  <header class="panel-heading">
                     Price Breakdown
                  </header>
                  <div class="panel-body">
                     <div class="position-center">
                      <div class="form-group" :class="{'has-error': formErrors.has('base_fare') }">
                        <!-- <label style="width:20%">Total:</label> -->
                        <input
                          
                           type="hidden"
                           name="base_fare"
                           placeholder="Total"
                           tabindex="1"
                           v-model="allTotal"
                           v-validate="'required'"
                           readonly
                           >
                           <!-- {{ this.allTotal }} -->
                           <!-- point 2 -->
                     </div>
                     <div class="form-group" :class="{'has-error': formErrors.has('service_agent') }">
                        <label style="width:20%">Costing:</label>
                        <input
                         class="form-control"
                           type="text"
                           name="service_agent"
                           placeholder="Add Costing"
                           tabindex="8"
                           v-model="serviceAgent"
                           value="0"
                           >
                     </div>
                     </div>
                  </div>
               </section>
            </div>
         </div>
         <!-- summary -->
         <div class="row">
            <div class="col-md-12">
              <section class="panel">
                  <header class="panel-heading">
                    Summery
                  </header>
                  <div class="panel-body">
                     <div class="">
                        <div class="col-md-6" style="margin-top:0px !important">
                            <label>
                              <h3>Add Note:</h3>
                            </label>
                            <textarea
                              class="form-control"
                              placeholder="Please enter any order notes here."
                              rows="6"
                              v-model="note"
                              >
                            </textarea>
                           <input type="hidden" name value>
                         </div>
                         <div class="col-md-6">
                        <div class="table-responsive">
                           <table class="table" id="summery-table">
                                       <tr>
                                 <th>Profit</th>
                                 <td>
                                    <span class>{{ allTotal -  serviceAgent}}</span>
                                 </td>
                              </tr>
                              <tr>
                                 <th>Total</th>
                                 <td>
                                    <span class>{{ total  }}</span>
                                 </td>
                              </tr>
                              <tr>
                                 <th>Status</th>
                                 <td>
                                    <select v-model="paymentStatus" class="form-control">
                                       <option value="paid">Paid</option>
                                       <option value="due" selected>Due</option>
                                       <option value>Pending/None</option>
                                    </select>
                                 </td>
                              </tr>
                              <tr>
                                 <th>Payment Method</th>
                                 <td>
                                    <select v-model="paymentMethod" name="payment" class="form-control">
                                       <option value="0">Cash Payment</option>
                                       <option value="1">Card Payment</option>
                                       <option value="4">Bank/Cheque Payment</option>
                                    </select>
                                 </td>
                              </tr>
                             
                           </table>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xs-8 text-center">
                           <br>
                        </div>
                        <div class="col-xs-4 text-left margin-t"></div>
                     </div>
                     </div>
                  </div>
               </section>
            </div>
         </div>
         <!-- end summary -->
         <div class="row panel panel-default padding-f">
            <div class="col-md-6"></div>
            <div class="col-md-6 padding-f">
               <div class="col-xs-8"></div>
               <div class="col-xs-4">
                  <input
                     type="button"
                     class="btn btn-success float-right"
                     :value="isEdit ? 'Save Invoice' : 'Create Invoice'"
                     data-loading-text="Creating..."
                     v-on:click="createInvoice()"
                     id="SubmitBtn"
                     >
               </div>
            </div>
         </div>
         <div id="insert_customer" class="modal fade">
            <div class="modal-dialog lg">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                     <h4 class="modal-title">Select an existing customer</h4>
                  </div>
                  <div class="modal-body">
                     <div class="frmSearch">
                        <input
                           type="text"
                           id="search-box"
                           placeholder="Enter Here Customer Name........"
                           class="form-control"
                           v-model="searchName"
                           >
                     </div>
                     <br>
                     <div class="tbl table-responsive">
                        <table id="ctable" class="table cell-border" cellspacing="0">
                           <thead>
                              <tr>
                                 <th>Name</th>
                                 <th class="hidden-xs">Address</th>
                                 <th>Phone</th>
                                 <th class="hidden-xs">Email</th>
                                 <th></th>
                              </tr>
                           </thead>
                           <tbody>
                              <template v-if="customers">
                                 <tr v-if="customer.customer_status == 'active'" v-for="(customer, index) in customers" :key="index">
                                    <td>{{ customer.name }}</td>
                                    <td class="hidden-xs">{{ customer.address1 }}</td>
                                    <td>{{ customer.phone }}</td>
                                    <td class="hidden-xs">{{ customer.email }}</td>
                                    <td>
                                       <button
                                          type="button"
                                          class="btn btn-primary grahk-chayn"
                                          v-on:click="selectCustomer(customer)"
                                          >Select</button>
                                    </td>
                                 </tr>
                              </template>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </div>
</template>
<script>
   export default {
     name: "ThirdParty",
     data() {
       return {
         GetLastInvoiceNo: "",
         SetLastInvoiceNo: "",
         InvoiceNoForView : "",
         invoiceDate: "",
         invoiceDueDate: "",
         invoiceNo: '',
         vendorId: 0,
         vendors: [],
         products: [],
         services: [],
         customer: {
           name: '',
           address1: '',
           address2: '',
           phone: '',
           email: '',
           id:'',
         },
         allTotal: 0,
         customers: [],
         baseFare: null,
         tax: null,
         discount: null,
         otherAgent: null,
         serviceAgent: null,
         iataComission: 0,
         govtTax: 0,
         profit: 0,
         airlinePayment: 0,
         paymentStatus: "due",
         paymentMethod: 0,
         searchName: null,
         note: "",
         success: null,
         printUrl: '#',
         InvoiceUrl: '#',
         printUrlOffice: '#',
         downloadUrl: '#',
         isEdit: false,
         flag: '',
         token: '?token=' + localStorage.getItem('token'),
         currentUserID:'',
         user: JSON.parse(localStorage.getItem('user')),
   
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
            errors: {},
           // success: false,
            loaded: true,
            viewSuccess: null,
            account_numbers:[],
             invoiceTypeService : false,
              invoiceTypeProduct : false,
           
       };
     },
     watch: {
       baseFare() {
         this.calculateBreakdown();
       },
       govtTax() {
         this.calculateBreakdown();
       },
       discount() {
         this.calculateBreakdown();
       },
       otherAgent() {
         this.calculateBreakdown();
       },
       serviceAgent() {
         this.calculateBreakdown();
       },
       searchName(updateValue) {
         this.searchCustomer();
       },
       '$route' (to, from) {
         this.resetForm();
       }
     },
     mounted() {
       this.initComponent();

       this.GetLastInvoiceID();
      this.addService();
       
     },
     computed: {
       subtotal() {
         let total = 0;
         this.products.forEach(product => {
           product.total = product.quantity * product.price;
           total += product.quantity * product.price;
         });
         return total;
       },
       total() {
         let total = this.subtotal;
         this.services.forEach(service => {
           service.total = service.quantity * service.price;
           total += service.quantity * service.price;
           this.allTotal = total;
         });
         return total;
       },
       
     },
     methods: {
       
       initComponent() {
         if (this.$route.name === 'ThirdPartyEdit') {
           this.isEdit = true;
           this.getInvoice();
         } else {
           this.addProduct();
           this.invoiceDate = moment()
                   .format("MM/DD/YYYY")
                   .toString();
           this.invoiceDueDate = moment()
                   .format("MM/DD/YYYY")
                   .toString();
         }
   
         $("#insert_customer").on("show.bs.modal", () => {
           this.searchName = "";
         });
   
         axios
             .post("/api/v1/vendors")
             .then(response => (this.vendors = response.data.vendors))
             .catch(error => console.log(error));
       },
       
         GetLastInvoiceID(){
           let SetLastInvoiceNumber;
           let GetLastInvoiceNumber;
           let TotalRows;
            axios.get('/api/v1/get-last-invoice-no') 
           .then(response => {
                   
              if(response.data.invoice_id == ''){ //if empty for fresh insert
   
                   this.invoiceNo = Number(1001);
              
   
               }if(this.isEdit == true){ // for update
   
                   
                      axios.get('/api/v1/get-invoice-count') 
                         .then(response => {
                                 
                          
                           TotalRows = response.data.data[0].Total_IDS;
   
                           this.invoiceNo =  Number(response.data.invoice_id[0].invoice_no ) + 1 - Number(TotalRows)
   
                          
                   
                         })
                         .catch(e => {
                   
                             console.log(e)
                           
                         })
   
               }else{ // if not empty for fresh insert
   
                   this.invoiceNo =  Number(response.data.invoice_id[0].invoice_no) + 1;
                
               } 
      
           })
           .catch(e => {
      
               console.log(e)
              
           })
        
   
        },
       getInvoice() {
         return axios
                 .post("/api/v1/get-invoice", {
                   invoice_no: this.$route.params.id
                 })
                 .then((response) => {
                   const invoice = { ...response.data.data.invoice };
                   this.products = response.data.data.products;
                   this.services = response.data.data.services;
                   this.invoiceNo = invoice.invoice_no;
                   this.customer = {...invoice.customer};
                   this.vendorId = invoice.vendor.id
                   this.invoiceDate = moment(invoice.invoice_date).format("MM/DD/YYYY").toString();
                   this.invoiceDueDate = moment(invoice.invoice_due_date).format("MM/DD/YYYY").toString();
                   this.baseFare = invoice.base_fare;
                   this.tax = invoice.tax;
                   this.discount = invoice.discount;
                   this.otherAgent = invoice.other_agent;
                   this.serviceAgent = invoice.service_agent;
                   this.iataComission = invoice.iata_comission;
                   this.govtTax = invoice.govt_tax;
                   this.airlinePayment = invoice.airline_payment;
                   this.profit = invoice.profit;
                   this.paymentStatus = invoice.status;
                   this.paymentMethod = invoice.payment_method;
                   this.note = invoice.note;
                   delete this.$route.params.id;
                 })
                 .catch(err => console.log(err));
       },
       selectCustomer(customer) {
         this.customer = { ...customer };
         jQuery('[data-dismiss=modal]').trigger('click');
       },
       clearCustomer() {
         this.customer = {
           name: "",
           address1: "",
           address2: "",
           phone: "",
           email: ""
         };
       },
       searchCustomer() {
         return axios
           .post("/api/v1/search-customer", {
             name: this.searchName
           })
           .then(response => (this.customers = response.data.customers))
           .catch(err => console.log(err));
       },
       calculateBreakdown() {
         this.iataComission = (this.baseFare * 7) / 100;
         this.govtTax = (parseInt(this.baseFare) + parseInt(this.tax)) * 0.003;
         this.profit = this.iataComission - this.discount - this.govtTax - this.otherAgent;
         this.airlinePayment = (parseInt(this.baseFare) - this.iataComission) +  parseInt(this.tax) + this.govtTax 
        
       },
      randomString(length = 5) {
         return Math.random()
           .toString(36)
           .substring(length)
           .toUpperCase();
       },
     
       addProduct() {
         let product = {
           type: "product",
           name: '',
           pnr: '',
           carrier: '',
           route: '',
           quantity: null,
           price: null
         };
         this.products.push(product);
       },
       CheckEqualityForSubTotalInTax:function(e){
              let tax_check = e.target.value;
              let b_fare = this.baseFare;
              let s_total = this.total;
              //let s_total = total();
              console.log( s_total + parseFloat(tax_check) + parseFloat(b_fare));
             
   
              
   
               if( parseFloat(tax_check) + parseFloat(b_fare) > s_total){
   
                 alert("Base Fare + Tax should not be greater than SubTotal");
                 $("#SubmitBtn").attr("disabled", true);
                 //console.log(s_total);
               }
               else{
   
                   $("#SubmitBtn").attr("disabled", false);
               }
   
       },
       CheckEqualityForSubTotalInBaseFare:function(e){
              let b_fare = e.target.value;
              let tax_check = this.tax;
              let s_total = this.total;
              //let s_total = total();
              console.log( s_total + parseFloat(tax_check) + parseFloat(b_fare));
             
   
              
   
               if( parseFloat(tax_check) + parseFloat(b_fare) > s_total){
   
                 alert("Base Fare + Tax should not be greater than SubTotal");
                 $("#SubmitBtn").attr("disabled", true);
                 //console.log(s_total);
               }
               else{
   
                   $("#SubmitBtn").attr("disabled", false);
               }
   
       },
       removeProduct(index) {
         this.products.splice(index, 1);
       },
       addService() {
         let service = {
           type: "service",
           name: '',
           quantity: null,
           price: null
         };
         this.services.push(service);
       },
       removeService(index) {
         this.services.splice(index, 1);
       },
       createInvoice() {
         this.$validator
           .validateAll()
           .then(result => {
             if (!result) {
               return;
             }
             this.currentUserID = this.user.id
             axios
               .post("/api/v1/invoice-create", {
                 invoice_no: this.invoiceNo,
                 products: [],
                 services: this.services,
                 customer: this.customer,
                 vendor_id: this.vendorId,
                 invoice_date: this.invoiceDate,
                 invoice_due_date: this.invoiceDueDate,
                 base_fare: 0,
                 tax: 0,
                 flag: 'third party',
                 discount: 0,
                 other_agent: 0,
                 service_agent: this.serviceAgent,
                 iata_comission: 0,
                 govt_tax: 0,
                 airline_payment: 0,
                 total_receivable: this.allTotal,
                 profit: this.allTotal -  this.serviceAgent,
                 status: this.paymentStatus,
                 payment_method: this.paymentMethod,
                 note: this.note,
                 edit: this.isEdit,
                 currentUserID: this.user.id
                 
               })
               .then(response => {
                 //console.log(response);
                 this.InvoiceNoForView = response.data.invoice.invoice_no;
                 this.printUrl = location.origin + '/api/v1/makepdf/' + response.data.invoice.invoice_no + '/print' + this.token;
                 this.printUrlOffice = location.origin + '/api/v1/makepdf/' + response.data.invoice.invoice_no + '/office' + this.token;
                 this.downloadUrl = location.origin + '/api/v1/makepdf/' + response.data.invoice.invoice_no + this.token;
                 this.InvoiceIdForView = response.data.invoice.invoice_no;
                 this.success = true;
               })
               .catch(e => {
                 console.log(e);
               });
           })
           .catch(err => console.log(err));
       },
       updateDate(fieldName) {
         const newValue = jQuery("[name=" + fieldName + "]").val();
         if (fieldName === "invoice_date") {
           this.invoiceDate = newValue;
         } else {
           this.invoiceDueDate = newValue;
         }
       },
       resetForm() {
         this.invoiceDate = moment().format("MM/DD/YYYY").toString();
         this.invoiceDueDate = moment().format("MM/DD/YYYY").toString();
         this.invoiceNo = this.randomString();
         this.vendorId = 0;
         this.vendors = [];
         this.products = [];
         this.addProduct();
         this.services = [];
         this.addService();
         this.customer = {
           name: '',
           address1: '',
           address2: '',
           phone: '',
           email: ''
         };
         this.customers = [];
         this.baseFare = null;
         this.tax = null;
         this.discount = null;
         this.otherAgent = null;
         this.serviceAgent = null;
         this.iataComission = 0;
         this.govtTax = 0;
         this.profit = 0;
         this.airlinePayment = 0;
         this.paymentStatus = "due";
         this.paymentMethod = 0;
         this.searchName = null;
         this.note = "";
         this.success = null;
         this.isEdit = false;
       },
       ViewInvoice(InvoiceUniqID)
       {
      
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
             
     }
   };
</script>