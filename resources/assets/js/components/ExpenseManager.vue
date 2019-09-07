<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" v-on:click="loadexpensePrevious()">Expense Manager</a></li>
            <li class="breadcrumb-item" v-if="!AddNew"><a href="#">List of All Expense</a></li>
            <li class="breadcrumb-item" v-if="AddNew"><a href="#">Add New Expense</a></li>
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
         <div class="row" v-if="!AddNew">
            <div class="col-md-12">
               <a class="btn btn-danger" title="Click Here to add new Expense data" href="#" v-on:click="AddNewExpense()" >+ Add New Expense Data</a>
               <!-- <button class="btn btn-primary">Link Account to sale</button> -->
            </div>
         </div>
         <br>
         <div class="row" v-if="!AddNew">
            <div class="col-md-12">
               <div class="mb-3" style="margin-bottom: 15px">
                  <div class="row">
                     <div class="col-md-2">
                        <strong>Search By</strong>
                     </div>
                     <div class="col-md-2">
                        <select v-model="queryField" class="form-control" name="" id="fileds">
                           <option value="income_note">Note Key Word</option>
                           <option value="income_date">A/C No.</option>
                           <option value="income_amount">Amount</option>
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
                     Expense List
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
                        <th scope="col">Date</th>
                        <th scope="col">Account NO</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Note</th>
                        <th scope="col" v-if="user.role == 'admin'">Settings</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-show="expenses.length" v-for="(account, index) in expenses" :key="account.id">
                        <th scope="row">{{index + 1}}</th>
                        <td>{{ account.expense_date }}</td>
                        <td>{{ account.account_no }}</td>
                        <td>{{ account.expense_amount }}</td>
                        <td>{{ account.expense_note }}</td>
                        <td>
                           <!-- <a href="" class="btn btn-info" title="View Account"><i class="fa fa-files-o"></i> View</a>
                              <a href="" class="btn btn-success" title="Edit"><i class="fa fa-edit"></i></a> -->
                           <a v-if="user.role == 'admin'" href="javascript:;" v-on:click="Delete(account.id)" class="btn btn-danger" title="Delete"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <tr v-show="!expenses.length">
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
                  New Expense Details
               </header>
               <div class="panel-body">
                  <div class="position-center">
                     <form @submit.prevent="submit">
                        <div class="form-group" data-date-format="yyyy/mm/dd">
                           <label for="expense_date">Date</label>
                           <!-- <input type="date" class="form-control" name="income_date" id="income_date" v-model="fields.income_date" /> -->
                           <div class="input-group date mdate">
                              <input type="text"
                                 class="form-control"
                                 name="expense_date"
                                 id="expense_date"
                                 v-model="fields.expense_date"
                                 data-date-format="yyyy/mm/dd"
                                 data-provide="datepicker"
                                 autocomplete="off"
                                 v-on:focusout="setDate('expense_date', fields, 'expense_date')"
                                 required>
                              <span class="input-group-addon"><span class="icon-calendar"></span></span>
                           </div>
                           <div v-if="errors && errors.expense_date" class="text-danger">{{ errors.expense_date[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="tr_ac">Account</label>
                           <select name="transaction_account" class="form-control" id="transaction_account" v-model="fields.transaction_account" required>
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
                           <label for="expense_amount">Amount</label>
                           <input type="number" class="form-control" name="expense_amount" id="expense_amount" v-model="fields.expense_amount" required />
                           <div v-if="errors && errors.expense_amount" class="text-danger">{{ errors.expense_amount[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="expense_note">Note</label>
                           <input type="text" class="form-control" name="expense_note" id="expense_note" v-model="fields.expense_note" />
                           <div v-if="errors && errors.expense_note" class="text-danger">{{ errors.expense_note[0] }}</div>
                        </div>
                        <button type="submit" class="btn btn-round btn btn-info">Submit</button>
                        <div v-if="success" class="alert alert-success mt-6" id="success-box">
                           Data Insertd Successfully
                        </div>
                     </form>
                  </div>
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
   </div>
</template>
<script type="text/ecmascript-6">
   import helpers from '../mixins/helpers';
   export default {
     name: 'ExpenseManager',
     data() {
       return {
         
         expenses: [],
         account_numbers:[],
         query:'',
         AddNew: null,
         queryField: 'expense_amount',
         fields: {},
         errors: {},
         success: false,
         loaded: true,
         pagination: {
   
             current_page: 1,
         },
         user: JSON.parse(localStorage.getItem('user')),
       }
     },
     mixins: [helpers],
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
        this.Accouts();
     },
     methods: {
      
       loadUsers(){
   
         this.$Progress.start()
        axios.get('/api/v1/expense-list?page='+this.pagination.current_page) 
             .then(response => {
   
                 console.log(response)
                 this.expenses = response.data.data
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
             axios.get('/api/v1/search-expense/'+this.queryField+'/'+this.query+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.expenses = response.data.data
                 this.pagination = response.data.meta
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },
       AddNewExpense(){
         // this.$Progress.start()
          this.AddNew = true;
       },
       submit() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post('/api/v1/expense-create', this.fields).then(response => {
                    this.fields = {}; //Clear input fields.
                    this.loaded = true;
                    this.success = true;
                     location.href= "/expense-manager";
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
       },
       loadexpensePrevious(){
           this.$Progress.start()
           this.AddNew = false;
           
   
       },
       Accouts(){
   
          axios.post('/api/v1/all-account-holder-list/') 
               .then(response => {
      
                  console.log(response.data.data)
                  this.account_numbers = response.data.data;
                  
               })
               .catch(e => {
      
                  console.log(e)
                     this.$Progress.fail()
               })
   
       },
       Delete(id){
   
           var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.delete('/api/v1/delete-expense/'+id) 
               .then(response => {
                   alert("Deleted Successfully");
                  // console.log(response)
                     this.$Progress.finish(); 
                    location.href= "/expense-manager";
               })
               .catch(e => {
      
                  console.log(e)
                     this.$Progress.fail()
               })
               }     
            else{
   
             return false;
            }
       }
     },
     filters: {
       
     },
     
   }
   
</script>