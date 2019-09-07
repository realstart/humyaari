
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';


// Components
import Invoice from './components/Invoice';
import invoiceService from './components/invoiceService';
import ThirdParty from './components/ThirdParty';
import Report  from './components/Report';
import Login   from './components/Login';
import CustomerList from './components/CustomerList';
import VendorList from './components/VendorList';
import InvoiceList from './components/InvoiceList';
import InvoiceReport from './components/InvoiceReport';
import VendorCreate from './components/VendorCreate';
import CustomerCreate from './components/CustomerCreate';
import Accounts from './components/Accounts';
import Transactions from './components/Transactions';
import IncomeManager from './components/IncomeManager';
import ExpenseManager from './components/ExpenseManager';
import CustomerReport from './components/CustomerReport';
import VendorReport from './components/VendorReport';
import VendorReportSearch from './components/VendorReportSearch';
import NoteManager from './components/NoteManager';
import SMSTemplates from './components/SMSTemplates';
import CompanyInfo from './components/CompanyInfo';
import UserCreate from './components/UserCreate';
import Profile from './components/Profile';
import SalesTracker from './components/SalesTracker';
import BankManagement from './components/BankManagement';
import InvoiceDetails from './components/InvoiceDetails'
import Vue from 'vue';
import VueProgressBar from 'vue-progressbar';
import VModal from 'vue-js-modal';
import VueSpinners from 'vue-spinners';
import vSelect from 'vue-select';

import 'vue-select/dist/vue-select.css';
import Donut from 'vue-css-donut-chart';
import 'vue-css-donut-chart/dist/vcdonut.css';
import VueSvgGauge from 'vue-svg-gauge';


Vue.use(require('vue-moment'));
Vue.use(Donut);
Vue.use(VueSvgGauge)




const VueProgressBarOptions = {
    color: '#f39c12',
    failedColor: '#dd4b39',
    thickness: '5px',
    transition: {
      speed: '0.4s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
  }
Vue.use(VueProgressBar,VueProgressBarOptions);
Vue.use(VModal);
Vue.use(VModal, { dialog: true });
Vue.use(VueSpinners);
//  Vue.use(VeeValidate);

 


Vue.component('pagination', require('./components/partial/PaginationComponent.vue'));
Vue.component('v-select', vSelect)

// Vue.component('sales-graph',SalesGraph);

window.Vue = require('vue');

Vue.use(VueRouter);


const configValidator = {
    errorBagName: 'formErrors',
    fieldsBagName: 'fieldsValidate',
    delay: 0,
    locale: 'en',
    dictionary: null,
    strict: false,
    classes: false,
    classNames: {
        touched: 'touched',
        untouched: 'untouched',
        valid: 'valid',
        invalid: 'invalid',
        pristine: 'pristine',
        dirty: 'dirty'
    },
    events: 'input|blur',
    inject: true,
    validity: false,
    aria: true
};

Vue.use(VeeValidate, configValidator);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'report',
            component: Report,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/invoice',
            name: 'invoice',
            component: Invoice,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/invoiceService',
            name: 'invoiceService',
            component: invoiceService,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/third-party',
            name: 'ThirdParty',
            component: ThirdParty,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/edit/invoice/:id',
            name: 'InvoiceEdit',
            component: Invoice,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/edit/invoiceService/:id',
            name: 'InvoiceEditService',
            component: invoiceService,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/edit/third-party/:id',
            name: 'ThirdPartyEdit',
            component: ThirdParty,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                requiresAuth: false
            }
        },
        {
            path: '/customer-list',
            name: 'CustomerList',
            component: CustomerList,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/vendor-list',
            name: 'VendorList',
            component: VendorList,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/invoice-list',
            name: 'InvoiceList',
            component: InvoiceList,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/invoice-report',
            name: 'InvoiceReport',
            component: InvoiceReport,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/add-new-vendor',
            name: 'VendorCreate',
            component: VendorCreate,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/account-manager',
            name: 'Accounts',
            component: Accounts,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/transaction-manager',
            name: 'Transactions',
            component: Transactions,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/income-manager',
            name: 'IncomeManager',
            component: IncomeManager,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/expense-manager',
            name: 'ExpenseManager',
            component: ExpenseManager,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/accounts',
            name: 'CustomerCreate',
            component: CustomerCreate,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/customer-report',
            name: 'CustomerReport',
            component: CustomerReport,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/bank-management',
            name: 'BankManagement',
            component: BankManagement,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/vendor-report',
            name: 'VendorReportSearch',
            component: VendorReportSearch,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/note-manager',
            name: 'NoteManager',
            component: NoteManager,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/sms-manager',
            name: 'SMSTemplates',
            component: SMSTemplates,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/company-settings',
            name: 'CompanyInfo',
            component: CompanyInfo,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/user-management',
            name: 'UserCreate',
            component: UserCreate,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/profile-management',
            name: 'Profile',
            component: Profile,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/track-your-sales',
            name: 'SalesTracker',
            component: SalesTracker,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/invoice-details-view',
            name: 'InvoiceDetails',
            component: InvoiceDetails,
            props: true,
            meta: {
                requiresAuth: true
            }
          }
    ],
});

const checkToken = () => {
	let token = localStorage.getItem('token');
	return token ? token : null;
};

axios.defaults.headers.common['Authorization'] = 'Bearer ' + checkToken();

axios.interceptors.response.use(undefined, function (error) {
    if(error.response.status === 401) {
        if (app.$route.fullPath !== '/login') {
            localStorage.removeItem('user');
            localStorage.removeItem('token');
            location.reload();
        }
        return Promise.reject(error);
    }
});

router.beforeEach((to, from, next) => {

	let requiresAuth = (typeof to.meta.requiresAuth !== 'undefined') ? to.meta.requiresAuth : true;

	// If the user isn't logged and try to access a restricted page
	if (!checkToken() && requiresAuth) {
        next({
            path: '/login'
        });
    }
	else if (checkToken('TOKEN') && !requiresAuth) {
        next({
            path: '/'
        });
	} else {
        next();
	}

});


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('dashboard', require('./components/Dashboard.vue'));




Vue.config.debug = true;
Vue.config.devtools = true;

window.$ = jQuery;

const app = new Vue({
    el: '#app',
    router
});
