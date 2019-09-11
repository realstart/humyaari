<template>
   <div>
      <vue-progress-bar></vue-progress-bar>      
      <section class="wrapper" id="report-page-wrapper">
         <!--mini statistics start-->
         <div class="row">
            <div class="col-md-6">
               <section class="panel">
                  <div class="panel-body">
                     <div class="top-stats-panel">
                        <h4 class="widget-h">Monthly Sale</h4>
                        <div class="sm-pie">
                           <vc-donut
                              background="white" foreground="grey"
                              :size="100" unit="px" :thickness="30"
                              has-legend legend-placement="right"
                              :sections="sections" :total="100"
                              :start-angle="0"
                              ></vc-donut>
                        </div>
                     </div>
                  </div>
               </section>
            </div>
            <div class="col-md-6">
               <section class="panel">
                  <div class="panel-body">
                     <div class="top-stats-panel">
                        <h4 class="widget-h">Daily Sales</h4>
                        <div class="bar-stats">
                           <ul class="progress-stat-bar clearfix">
                              <li data-percent=""><span class="progress-stat-percent pink" :style="'height:' + PercentageGDS + '%'"></span></li>
                              <li data-percent=""><span class="progress-stat-percent" :style="'height:' + PercentageService + '%'"></span></li>
                              <li data-percent=""><span class="progress-stat-percent yellow-b" :style="'height:' + PercentageThirdParty + '%'"></span></li>
                           </ul>
                           <ul class="bar-legend">
                              <li><span class="bar-legend-pointer pink"></span> GDS</li>
                              <li><span class="bar-legend-pointer green"></span> Services</li>
                              <li><span class="bar-legend-pointer yellow-b"></span> Third Party</li>
                           </ul>
                           <div class="daily-sales-info">
                              <span class="sales-count" v-if="totalSalesAll <= 0">0</span> <span v-else> {{ totalSalesAll }}</span> <span class="sales-label">Daily sales</span>
                           </div>
                        </div>
                     </div>
                  </div>
               </section>
            </div>
         </div>            
         <div class="row">
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon"><img style="width:50px;" src="/img/icon/Tickets.png" alt=""></span>
                  <div class="mini-stat-info">
                     <span>{{ totalTicket + totalService }}</span>
                     <b>Total Sale</b> 
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon"><img style="width:50px;" src="/img/icon/Services.png" alt=""></span>
                  <div class="mini-stat-info">
                     <span>{{ totalSaleMonth }}</span>
                     <b>Sales This Month</b> 
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon"><img style="width:50px;" src="/img/icon/Profits.png" alt=""></span>
                  <div class="mini-stat-info">
                     <span>{{ totalDue }}</span>
                     <b>Total Due</b> 
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon green"><img style="width:50px;" src="/img/icon/Customers.png" alt=""></span>
                  <div class="mini-stat-info">
                     <span>{{ totalPaid}}</span>
                     <b>Payment Received</b>
                  </div>
               </div>
            </div>
         </div>
        <div class="row">
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon"><img style="width:50px;" src="/img/icon/Tickets.png" alt=""></span>
                  <div class="mini-stat-info">
                     <span>{{ totalTicket}}</span>
                     Total Ticket Sale
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon"><img style="width:50px;" src="/img/icon/Services.png" alt=""></span>
                  <div class="mini-stat-info">
                     <span>{{ totalService}}</span>
                     Total Services Sold
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon"><img style="width:50px;" src="/img/icon/Profits.png" alt=""></span>
                  <div class="mini-stat-info">
                     <span>{{ Number((totalProfit)).toFixed(2) }}</span>
                     Total Profit
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon green"><img style="width:50px;" src="/img/icon/Customers.png" alt=""></span>
                  <div class="mini-stat-info">
                     <span>{{ totalCustomer}}</span>
                     Total Customers
                  </div>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-6">
               <!--notification start-->
               <section class="panel">
                  <header class="panel-heading">
                     Sales Graph <span class="tools pull-right">
                     <a href="javascript:;" class="fa fa-chevron-down"></a>
                     <a href="javascript:;" class="fa fa-cog"></a>
                     <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                  </header>
                  <div class="panel-body">
                      <div class="sales-graph">
                        <canvas id="sales-graph"></canvas>
                     </div> 
                  </div>
               </section>
               <!--notification end-->
            </div>
             <div class="col-md-6">
               <!--todolist start-->
               <section class="panel">
                  <header class="panel-heading">
                     User Log <span class="tools pull-right">
                     <a href="javascript:;" class="fa fa-chevron-down"></a>
                     <a href="javascript:;" class="fa fa-cog"></a>
                     <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                  </header>
                  <div class="panel-body">
                         <ul class="list-group" v-show="userLog.length" v-for="(user, index) in userLog" :key="user.id">
                            <li style="list-style: none;margin-bottom: -12px;    list-style: none;margin-bottom: -12px;border: 1px dotted; padding-top: 5px;
                            padding-bottom: 5px;padding-left: 5px;padding-right: 5px;"> {{index + 1}}. {{ user.name }} ( {{ user.updated_at | moment("MMMM Do YYYY, h:mm: a") }} ) <i v-if="curuser.id == user.id" class="fa fa-user"  style="color:green;float: right;" aria-hidden="true"></i> <i v-if="curuser.id != user.id" class="fa fa-user"  style="color:red;float: right;" aria-hidden="true"></i></li>
                        </ul>
                      </div>
               </section>
               <!--todolist end-->
            </div>
         </div>
      </section>
      <div v-if="!success" class="row" style="margin:0 0px;margin-left: 15px;padding-right: 15px;">
         <div class="col-md-12 table-responsive" style="background-color:white;">
            <h3 class="text-left report_header box-header with-border">Latest Invoice</h3>
            <section class="panel">
               <header class="panel-heading">
                  Recent 10 Invoices
                  <span class="tools pull-right">
                  <a href="javascript:;" class="fa fa-chevron-down"></a>
                  </span>
               </header>
               <div class="panel-body" style="display: block;">
                  <table class="table table-hover general-table">
                     <thead>
                        <tr>
                           <th >#</th>
                           <th >Invoice No</th>
                           <th >Date</th>
                           <th >Amount</th>
                           <th >Client Name</th>
                           <th >Client Phone</th>
                           <th >Payment Status</th>
                           <th >Options</th>
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
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Options
                                    <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li> <router-link :to="{ name: 'InvoiceDetails', params: { id: invoice.invoice_no } }">View</router-link></li>
                                        <li><a target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/print'">Print Customer Copy</a></li>
                                        <li><a target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/office'">Print Office Copy</a></li>
                                        <li><a target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no ">Download</a></li>
                                        <li>
                                            <a v-if="user.role == 'admin'" href="#" title="Delete" v-on:click="DeleteInvoice(invoice.invoice_no)">Delete</a>
                                        </li>
                                    </ul>
                                </div>
                           </td>
                        </tr>
                        <tr v-show="!invoices.length">
                           <td colspan="8">
                              <div class="alert alert-danger text-center" role="alert">
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
</template>
<script>
   import { Line } from "vue-chartjs";
   export default {
     name: "report",
     extends: Line,
     data() {
        return {
            chart: null,
            labelSalesDate: [],
            TotalIncomes: [],
            TotalExpenses: [],
            TotalDue: [],
            TotalSale: [],
            TotalSevenDaysSale: [],
            TotalSevenDaysDue: [],
            TotalSevenDaysIncome: [],
            TotalSevenDaysExpense: [],
            TotalSevenDaysDeposit: [],
            TotalSevenDaysWithdraw: [],
            TotalSevenDaysTransfer: [],
            totalTicket: 0,
            totalCustomer: 0,
            totalService: 0,
            totalProfit: 0,
            totalSaleMonth: 0,
            totalDue: 0,
            totalPaid: 0,
            invoices: [],
            totalGDSSale:[],
            totalServiceSale:[],
            totalThirdPartySale:[],
            totalSalesAll:[],

            MonthlyGDSSale:[],
            MonthlyServiceSale:[],
            MonthlyThirdPartySale:[],
            MonthlySalesAll:[],


            PercentageGDS:null,
            PercentageService:null,
            PercentageThirdParty:null,

            PercentageGDSMonthly:null,
            PercentageServiceMonthly:null,
            PercentageThirdPartyMonthly:null,
            userLog: [],
            user: JSON.parse(localStorage.getItem('user')),
            success: null,
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
            loadSuccessMessage: '',
            errors: {},
            isLoading: true,
            PartialPayment: '',
            loaded: true,
            account_numbers:[],
            curuser: JSON.parse(localStorage.getItem('user')),
            invoiceTypeService : false,
            invoiceTypeProduct : false,
            sections: [
                { label: 'GDS', value: 0, color: 'red' }, 
                { label: 'Service', value: 0, color: 'green'}, 
                { label: 'Third Party', value: 0, color: 'blue'}
            ]
       };
     },
   
     mounted() {
       this.ReportData();
       this.ReportDataSevenDays();
       this.renderSevenDaysSalesReport();
      //  this.renderSevenDaysIncomeReport();
      //  this.renderSevenDaysExpenseReport();
      //  this.renderSevenDaysDepositReport();
       this.renderInvoice();
       this.renderUserLog();
       this.CompanyInfo();
       this.TotalTSPC();
       this.TotalSalesForOneDay();
       this.TotalSalesForOneMonth();
     },
     methods: {
        renderSevenDaysSalesReport() {
          this.success = false;
         axios
           .get("/api/v1/sales-report")
           .then(response => {
             const labels = [];
             const formatedData = {};
              if(response.data == ''){
                var canvas = document.getElementById("sales-graph");
                 var ctx = canvas.getContext("2d");
                 canvas.height = 300;
   
                 ctx.font = "20px Arial";
                 ctx.fillText("No Data Found", 10, 50);
                 return;
   
             }
             for (let i = 6; i >= 0; i--) {
               let date = moment(Date.now() - i * 24 * 3600 * 1000).format(
                 "YYYY-MM-DD"
               );
               labels.push(date);
               formatedData[date] = 0;
             }
            
             response.data.data.forEach(item => {
               formatedData[item.sales_date] =
                 formatedData[item.sales_date] + item.total_sale;
             });
   
             const data = [];
   
             Object.keys(formatedData).forEach(date => {
               data.push(formatedData[date]);
             });
             
   
             const config = {
               type: "line",
               responsive: false,
               data: {
                 labels,
                 datasets: [
                   {
                     label: "Sales: Last 7 active days",
                     backgroundColor: "rgba(111,228,113,.2)",
                     borderColor: "rgba(111,228,113,1)",
                     borderWidth: 1,
                     data
                   }
                 ]
               }
             };
             this.chart = new Chart(jQuery(this.$el).find("#sales-graph"), config);
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
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

        ReportData() {
          this.success = false;
         this.$Progress.start();
          this.isLoading = true;
         axios
           .get("/api/v1/all-reports")
           .then(response => {
             
             if(response != ''){this.isLoading = true;}
             this.TotalIncomes = response.data.income[0].TotalIncome;
             this.TotalExpenses = response.data.expense[0].TotalExpense;
             this.TotalDue = response.data.due[0].TotalDue;
             this.TotalSale = response.data.sale[0].TotalSale;
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
        },
        
        TotalTSPC() {
            this.success = false;
            this.$Progress.start();
            this.isLoading = true;

            axios
           .get("/api/v1/total-tspc")
           .then(response => {
                this.totalTicket = response.data.total_ticket[0].total_ticket;
                this.totalCustomer = response.data.total_customer[0].total_customer;
                this.totalService = response.data.total_service[0].total_service;
                this.totalProfit = response.data.total_profit[0].total_profit;
                this.totalSaleMonth = response.data.total_sale_month;
                this.totalDue = response.data.total_due;
                this.totalPaid = response.data.total_paid;
                console.log(this.totalTicket);
            })
           .catch(e => {
                console.log(e);
                this.$Progress.fail();
           });
        },

       DeleteInvoice(id){
          var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.put('/api/v1/soft-delete-invoice/'+id) 
               .then(response => {
                  //alert("Deleted Successfully");
                   location.href="/";
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

        TotalSalesForOneDay() {
          this.success = false;
         axios
           .get("/api/v1/daily-sales-state")
           .then(response => {
             
             this.totalGDSSale = response.data.gds_sales[0].gds_sales;
             this.totalServiceSale = response.data.service_sales[0].service_sales;
             this.totalThirdPartySale = response.data.thrid_party_sales[0].thrid_party_sales;
             this.totalSalesAll = response.data.total_sales[0].total_sales;
   
            this.PercentageGDS = ( 100 / this.totalSalesAll ) * this.totalGDSSale ;
            this.PercentageService = ( 100 / this.totalSalesAll ) * this.totalServiceSale ;
            this.PercentageThirdParty = ( 100 / this.totalSalesAll ) * this.totalThirdPartySale ;
             
            //alert(this.PercentageService + this.PercentageThirdParty + this.PercentageGDS)
            //console.log(this.totalSalesAll);
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
        },

        TotalSalesForOneMonth() {
          this.success = false;
         axios
           .get("/api/v1/monthly-sales-state")
           .then(response => {
             
             this.MonthlyGDSSale = response.data.gds_sales[0].gds_sales;
             this.MonthlyServiceSale = response.data.service_sales[0].service_sales;
             this.MonthlyThirdPartySale = response.data.thrid_party_sales[0].thrid_party_sales;
             this.MonthlySalesAll = response.data.total_sales[0].total_sales;
   
            this.PercentageGDSMonthly = ( 100 / this.MonthlySalesAll ) * this.MonthlyGDSSale ;
            this.PercentageServiceMonthly = ( 100 / this.MonthlySalesAll ) * this.MonthlyServiceSale ;
            this.PercentageThirdPartyMonthly = ( 100 / this.MonthlySalesAll ) * this.MonthlyThirdPartySale ;
   
            if(this.sections[0].label == 'GDS'){
                
                this.sections[0].value = Math.round(this.PercentageGDSMonthly)
            }
            if(this.sections[1].label == 'Service'){
               
                this.sections[1].value = Math.round(this.PercentageServiceMonthly)
            }
            if(this.sections[2].label == 'Third Party'){
               
                this.sections[2].value = Math.round(this.PercentageThirdPartyMonthly)
            }
           
             
            //alert(Number((this.PercentageGDSMonthly).toFixed(1)))
            //alert(Math.round(this.PercentageGDSMonthly))
            //console.log(response);
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
        },
        ReportDataSevenDays() {
           this.success = false;
         this.$Progress.start();
         axios
           .get("/api/v1/seven-days-reports")
           .then(response => {
             this.TotalSevenDaysIncome =
               response.data.seven_days_income[0].SevenDaysIncome;
             this.TotalSevenDaysExpense =
               response.data.seven_days_expense[0].SevenDaysExpense;
             this.TotalSevenDaysDue = response.data.seven_days_due[0].SevenDaysDue;
             this.TotalSevenDaysSale =
               response.data.seven_days_sale[0].SevenDaysSale;
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
       },
   
       renderSevenDaysDepositReport(){
            this.success = false;
            this.$Progress.start();
            axios
           .get("/api/v1/dashboard_transaction")
           .then(response => {
                this.TotalSevenDaysDeposit = response.data.deposit[0].deposit;
                this.TotalSevenDaysWithdraw = response.data.withdraw[0].withdraw;
                this.TotalSevenDaysTransfer = response.data.transfer[0].transfer_amount;
                
                console.log(this.TotalSevenDaysDeposit);
            })
            .catch(e => {
                console.log(e);
                this.$Progress.fail();
            });
       },
       renderInvoice(){
            this.success = false;
            this.$Progress.start();
            axios
            .get("/api/v1/invoice-list")
            .then(response => {
                this.invoices = response.data.data;
                console.log(this.invoices)
            })
            .catch(e => {
                console.log(e);
                this.$Progress.fail();
            });
       },
        renderUserLog(){
            this.success = false;
            this.$Progress.start();
            axios
            .get("/api/v1/user-log")
            .then(response => {
                this.userLog = response.data.data;                
            })
            .catch(e => {
                console.log(e);
                this.$Progress.fail();
            });
        },
     },
     filters: {}
    };
</script>
