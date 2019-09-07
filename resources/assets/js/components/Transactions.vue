<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:;" v-on:click="loadTransactionPrevious()">Transaction Manager</a></li>
            <li class="breadcrumb-item" v-if="!AddNew"><a href="javascript:;" v-on:click="List()">List of All Transaction</a></li>
            <li class="breadcrumb-item" v-if="!AddNew && ViewFull"><a href="#">Transaction Details</a></li>
            <li class="breadcrumb-item" v-if="AddNew"><a href="#" >Add New Transaction</a></li>
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
         <div class="row" v-if="!AddNew && !ViewFull">
            <div class="col-md-12">
               <a class="btn btn-danger" title="+ Add New Account" href="#" v-on:click="AddNewTrasnaction()" >+ Add New Transaction</a>
               <!-- <button class="btn btn-primary">Link Account to sale</button> -->
            </div>
         </div>
         <br>
         <div class="row" v-if="!AddNew  && !ViewFull">
            <div class="col-md-12 table-responsive">
               <div class="mb-3" style="margin-bottom: 15px">
                  <div class="row">
                     <div class="col-md-2">
                        <strong>Search By</strong>
                     </div>
                     <div class="col-md-2">
                        <select v-model="queryField" class="form-control" name="" id="fileds">
                           <option value="tr_id">Transaction ID</option>
                           <option value="tr_ac">Account No.</option>
                           <option value="tr_type">Transaction Type</option>
                           <option value="tr_ac_type">Account Type</option>
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
                     All Trasnactions List
                     <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <!-- <a href="javascript:;" class="fa fa-cog"></a> -->
                        <!-- <a href="javascript:;" class="fa fa-times"></a> -->
                     </span>
                  </header>
                  <div class="panel-body" style="display: block;">
                     <table class="table table-hover general-table">
                        <thead class="">
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tr. ID</th>
                        <th scope="col">Tr. Type</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Account Type </th>
                        <th scope="col">Tr. Account </th>
                        <th scope="col">Date</th>
                        <th scope="col">Note</th>
                        <th scope="col">Image</th>
                        <th scope="col">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-show="transactions.length" v-for="(trns, index) in transactions" :key="trns.id">
                        <th scope="row">{{index + 1}}</th>
                        <td>{{ trns.tr_id }}</td>
                        <td style="text-transform:uppercase">{{ trns.tr_type }}</td>
                        <td>{{ trns.tr_amount }}</td>
                        <td>{{ trns.tr_ac_type }}</td>
                        <td>{{ trns.tr_ac }}</td>
                        <td>{{ trns.tr_date }}</td>
                        <td>{{ trns.tr_note }}</td>
                        <td>
                           <img v-if="trns.file !='image file'" style="width: 100px;height:auto;    border: 2px solid black;" :src="'/uploads/transaction/'+ trns.file"  alt="Transaction proof"/>
                           <p v-else style="font-weight:bold">Image Not Required</p>
                        </td>
                        <td>
                           <a href="javascript:;" v-on:click="ViewTransaction(trns.id)" class="btn btn-info" title="View Transaction Details"><i class="fa fa-files-o"></i> View</a>
                           <!-- <a href="" class="btn btn-success" title="Edit"><i class="fa fa-edit"></i></a> -->
                           <a v-if="user.role == 'admin'" href="javascript:;" v-on:click="DeleteTranscation(trns.id,trns.tr_type,trns.tr_ac,trns.tr_amount,trns.flag)" class="btn btn-danger" title="Delete"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <tr v-show="!transactions.length" >
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
         <div class="row d-flex justify-content-center" v-if="AddNew">
            <div class="col-lg-12">
               <section class="panel">
                  <header class="panel-heading">
                     Transaction Create Form
                  </header>
                  <div class="panel-body">
                     <div class="position-center">
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                           <div class="form-group">
                              <label for="tr_amount">Transaction Amount</label>
                              <input type="number" min="0" class="form-control" name="transaction_amount" id="tr_amount" v-model="fields.transaction_amount" required/>
                              <div v-if="errors && errors.transaction_amount" class="text-danger">{{ errors.transaction_amount[0] }}</div>
                           </div>
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
                              <label for="tr_type">Transaction Type</label>
                              <select name="tr_type" class="form-control" id="transaction_type" v-on:change="TransactionType" v-model="fields.transaction_type">
                                 <option value=""> --- Select type ---</option>
                                 <option value="deposit">Deposit</option>
                                 <option value="whithdraw">Withdraw</option>
                                 <option value="transfer">Transfer</option>
                              </select>
                              <div v-if="errors && errors.transaction_type" class="text-danger">{{ errors.transaction_type[0] }}</div>
                           </div>
                           <div v-if="['deposit', 'whithdraw'].includes(fields.transaction_type)" class="form-group">
                              <label for="scanned_file">
                              <input ref="scanned_file" id="scanned_file" name="scanned_file" class="form-control" type="file" accept="image/*" required>
                              </label>
                           </div>
                           <div class="form-group" v-if="!transfer">
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
                           <div class="form-group" v-if="transfer">
                              <label for="transaction_account_from">From Account</label>
                              <select name="transaction_account_from" class="form-control" id="transaction_account_from" v-model="fields.transaction_account_from">
                                 <option value=" " disabled> --- Select type ---</option>
                                 <option
                                    v-for="vendor in account_numbers_from"
                                    :key="vendor.id"
                                    :value="vendor.ac_no"
                                    >{{ vendor.ac_no }}</option>
                              </select>
                              <div v-if="errors && errors.transaction_account_from" class="text-danger">{{ errors.transaction_account_from[0] }}</div>
                           </div>
                           <div class="form-group" v-if="transfer">
                              <label for="transaction_account_to">To Account</label>
                              <select name="transaction_account_to" class="form-control" id="transaction_account_to" v-model="fields.transaction_account_to">
                                 <option value=" " disabled> --- Select type ---</option>
                                 <option
                                    v-for="vendor in account_numbers_to"
                                    :key="vendor.id"
                                    :value="vendor.ac_no"
                                    >{{ vendor.ac_no }}</option>
                              </select>
                              <div v-if="errors && errors.transaction_account_to" class="text-danger">{{ errors.transaction_account_to[0] }}</div>
                           </div>
                           <div class="form-group">
                              From: <br>
                              <div class="input-group date mdate">
                                 <input type="text"
                                    class="form-control required"
                                    id="date_t"
                                    name="date_from"
                                    v-model="fields.transaction_date"
                                    data-date-format="yyyy/mm/dd"
                                    autocomplete="off"
                                    v-on:focusout="setDate('date_from', fields, 'transaction_date')"
                                    data-provide="datepicker">
                                 <span class="input-group-addon"><span class="icon-calendar"></span></span>
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="tr_note">Note</label>
                              <input type="text" class="form-control" name="transaction_note" id="tr_note" v-model="fields.transaction_note" />
                              <div v-if="errors && errors.transaction_note" class="text-danger">{{ errors.transaction_note[0] }}</div>
                           </div>
                           <button type="submit" class="btn btn-primary">Submit</button>
                           <div v-if="success" class="alert alert-success mt-3">
                              Data Inserted Successfully,  <a href="#" v-on:click="loadTransactionPrevious()">Go Back To Account</a>
                           </div>
                        </form>
                     </div>
                  </div>
               </section>
         </div>
         <div>
         </div>
      </div>
      <div class="container-fluid">
         <div class="row d-flex justify-content-center" v-if="ViewFull == true">
            <div class="col-md-12" style="background-color:#fff;padding-bottom:30px;padding-left:20px !important">
               <a v-for="(official_info, index) in official_infos" :href="'/uploads/transaction/' + official_info.file" style="margin-top: 10px;" class="btn btn-success pull-right" download><i class="fa fa-download"></i> Download</a>
               <h3 class=""><b>Transaction Details:</b></h3>
               <span v-for="(official_info, index) in official_infos" :key="official_info.id">
                  <p><span style="font-size:18px; font-weight:bold"> Transcation ID:</span> <span style="font-size:18px">{{ official_info.tr_id }}</span></p>
                  <p><span style="font-size:18px; font-weight:bold"> Transaction Type:</span> <span style="font-size:18px">{{ official_info.tr_type }}</span></p>
                  <p><span style="font-size:18px; font-weight:bold"> Account Type:</span> <span style="font-size:18px">{{ official_info.tr_ac_type }}</span></p>
                  <p><span style="font-size:18px; font-weight:bold"> Account Number:</span> <span style="font-size:18px">{{ official_info.tr_ac}}</span></p>
                  <p><span style="font-size:18px; font-weight:bold"> Note:</span> <span style="font-size:18px">{{ official_info.tr_note }}</span></p>
                  <p><span style="font-size:18px; font-weight:bold"> Amount:</span> <span style="font-size:18px">{{ official_info.tr_amount }}</span></p>
                  <p><span style="font-size:18px; font-weight:bold"> File:</span> 
                  <div class="zoom"><img style="width: 40%;" :src="'/uploads/transaction/'+ official_info.file" /></div>
                  </p>
               </span>
            </div>
         </div>
      </div>
   </div>
   <div class="text-center" v-if="!AddNew && !ViewFull">
      <pagination v-if="pagination.last_page > 1"
         :pagination="pagination"
         :offset="5"
         @paginate="query === '' ? loadUsers() : SearchData()"   
         ></pagination>
   </div>
   </div>
</template>
<script type="text/ecmascript-6">
   import helpers from '../mixins/helpers';
   export default {
     name: 'Transactions',
     mixins: [helpers],
     data() {
       return {
         
         transactions: [],
         account_numbers:[],
         account_numbers_from:[],
         account_numbers_to:[],
         query:'',
         AddNew: null,
         queryField: 'tr_id',
         transactionID:'',
         fields: {},
         errors: {},
         success: false,
         loaded: true,
         transfer:false,
         pagination: {
   
             current_page: 1,
         },
         ViewFull: false,
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
      
     },
     methods: {
        TransactionType: function(e){
           let tr_type = e.target.value;
            if(tr_type == 'transfer'){
               this.transfer = true;
               axios.post('/api/v1/all-account-holder-list/') 
                  .then(response => {
         
                    // console.log(response.data.data)
                     this.account_numbers_from = response.data.data;
                     this.account_numbers_to = response.data.data;
                     
                  })
                  .catch(e => {
         
                     console.log(e)
                        this.$Progress.fail()
                  })
            }else{
                  this.transfer = false;
            }
   
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
      
       loadUsers(){
   
         this.$Progress.start()
        axios.get('/api/v1/transaction-list?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response.data)
                this.transactions = response.data.data
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
             axios.get('/api/v1/search-transactions/'+this.queryField+'/'+this.query+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.transactions = response.data.data
                 this.pagination = response.data.meta
                  this.$Progress.finish()
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
   
                var bodyFormData = new FormData();
   
                Object.keys(this.fields).forEach((key) => {
                    bodyFormData.append(key, this.fields[key]);
                });
               // console.log(bodyFormData);
   
                if (['deposit', 'whithdraw'].includes(this.fields.transaction_type)) {
                    let imageFile = this.$refs.scanned_file.files[0];
                    bodyFormData.append('scanned_file', imageFile);
                }
   
                axios({
                    method: 'post',
                    url: '/api/v1/transaction-create',
                    data: bodyFormData,
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                })
                    .then((response) => {
                        this.fields = {}; //Clear input fields.
                        this.loaded = true;
                        this.success = true;
                        // location.href='/transaction-manager';
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
       DeleteTranscation(id,type,account,amount,flag){
   
          var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.delete('/api/v1/delete-transaction/'+id+'/'+type+'/'+account+'/'+amount+'/'+flag)
               .then(response => {
   
                     location.href= "transaction-manager";
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
       loadTransactionPrevious(){
           this.AddNew = false;
           this.loadUsers();
       },
        AddNewTrasnaction(){
         // this.$Progress.start()
          this.AddNew = true;
          
         
       },
       ViewTransaction(id){
   
   
          this.$Progress.start()
          axios.get('/api/v1/get-transaction-details/'+id) 
             .then(response => {
                  this.ViewFull = true;
                 console.log(response.data)
                 this.official_infos = response.data.data
               //   this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
       },
       List(){
   
          this.ViewFull = false;
       },
     },
     filters: {
       
     },
     
     
   }
   
</script>