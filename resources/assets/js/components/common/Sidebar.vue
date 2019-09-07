<template>
    <aside>
        <div id="sidebar" class="nav-collapse">
            <!-- sidebar menu start-->
            <div class="leftside-navigation">
                <ul class="sidebar-menu" id="nav-accordion">
                    <li>
                        <router-link :to="{ name: 'report' }">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </router-link>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Sales</span>
                        </a>
                        <ul class="sub">
                            <li><router-link :to="{ name: 'invoice' }"><i class="fa fa-plus-square"></i> New Invoice GDS</router-link></li>
                            <li><router-link :to="{ name: 'invoiceService' }"><i class="fa fa-plus-square"></i> New Invoice Service</router-link></li>
                            <li><router-link :to="{ name: 'ThirdParty' }"><i class="fa fa-plus-square"></i> Third Party</router-link></li>
                            <li><router-link :to="{ name: 'InvoiceList' }"><i class="fa fa-plus-square"></i> Manage Invoices</router-link></li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                       
                            <router-link :to="{ name: 'CustomerList' }">
                            <i class="fa fa-user"></i>
                            <span>Clients</span>
                            </router-link>
                       
                        
                    </li>
                    <li class="sub-menu">

                         <router-link :to="{ name: 'VendorList' }">
                            <i class="fa fa-male"></i>
                            <span>Vendors</span>
                            </router-link>
                        <!-- <a href="javascript:;">
                            <i class="fa fa-male"></i>
                            <span>Vendors</span>
                        </a>
                        <ul class="sub">
                            <li><router-link :to="{ name: 'VendorList' }"><span class="icon-file-text"></span>  Manage Vendors</router-link></li>
                            <li><router-link :to="{ name: 'VendorCreate' }"><span class="icon-file-text"></span>  Add New Vendor</router-link></li>
                        </ul> -->
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-credit-card"></i>
                            <span>Banking</span>
                        </a>
                        <ul class="sub">
                            <li><router-link :to="{ name: 'Accounts' }"><span class="icon-list"></span> Accounts</router-link></li>
                            <li><router-link :to="{ name: 'Transactions' }"><span class="icon-drive"></span> Transactions</router-link></li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-money"></i>
                            <span>Income & Expenses</span>
                        </a>
                        <ul class="sub">
                            <li><router-link :to="{ name: 'IncomeManager' }"><span class="icon-plus"></span> Manage Income</router-link></li>
                            <li><router-link :to="{ name: 'ExpenseManager' }"><span class="icon-minus"></span> Manage Expence</router-link></li>
                        </ul>
                    </li>
                    <li><router-link :to="{ name: 'NoteManager' }"><i class="fa fa-tasks"></i> Notes</router-link></li>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-bar-chart-o"></i>
                            <span>Reports &amp; Data</span>
                        </a>
                        <ul class="sub">
                            <li><router-link :to="{ name: 'InvoiceReport' }"><span class="icon-file-text2"></span>Invoice Report</router-link></li>
                            <li><router-link :to="{ name: 'CustomerReport' }"><span class="icon-user"></span>  Sales Customer-wise</router-link></li>
                            <li><router-link :to="{ name: 'VendorReportSearch' }"><span class="icon-user"></span>  Sales Vendor-wise</router-link></li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-envelope"></i>
                            <span>SMS Manager</span>
                        </a>
                        <ul class="sub">
                            <li><router-link :to="{ name: 'SMSTemplates' }"><span class="fa fa-list"></span>Template List</router-link></li>
                        </ul>
                    </li>
                    <li class="sub-menu" v-if="user.role == 'admin'">
                        <a href="javascript:;">
                            <i class="fa fa-gears"></i>
                            <span>Settings</span>
                        </a>
                        <ul class="sub">
                            <li><router-link :to="{ name: 'CompanyInfo' }"><span class=""></span> Company Info</router-link></li>
                            <li><router-link :to="{ name: 'UserCreate' }"><span class=""></span> User Management</router-link></li>
                            <li><router-link :to="{ name: 'BankManagement' }"><span class=""></span> Bank Management</router-link></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- sidebar menu end-->
        </div>
    </aside>
</template>

<script>
export default {
  name: 'dashboardSidebar',
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user')),
    }
  },
}
</script>
