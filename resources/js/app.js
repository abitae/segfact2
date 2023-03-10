/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue';
import vSelect from 'vue-select'
import {Toast, options} from './toast';
import './mixin';

Vue.use(Toast, options);
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('v-select', vSelect)
Vue.component('pagination', require('laravel-vue-pagination'));
import 'vue-select/dist/vue-select.css';


Vue.component('LoaderComponent', require('./backend/LoaderComponent.vue').default);
Vue.component('EmptyComponent', require('./backend/EmptyComponent.vue').default);
Vue.component('InProcessComponent', require('./backend/InProcessComponent.vue').default);

Vue.component('BankComponent', require('./backend/BankComponent/index.vue').default);
Vue.component('UserComponent', require('./backend/UserComponent/UserComponent.vue').default);
Vue.component('SupplierComponent', require('./backend/SupplierComponent/SupplierComponent.vue').default);
Vue.component('CustomerComponent', require('./backend/CustomerComponent/CustomerComponent.vue').default);
Vue.component('EnterpriseComponent', require('./backend/EnterpriseComponent/EnterpriseComponent.vue').default);
Vue.component('ProfileComponent', require('./backend/ProfileComponent/ProfileComponent.vue').default);
Vue.component('RoleComponent', require('./backend/RoleComponent/RoleComponent.vue').default);
Vue.component('PermissionComponent', require('./backend/PermissionComponent/PermissionComponent.vue').default);
Vue.component('ShoppingComponent', require('./backend/ShoppingComponent/ShoppingComponent.vue').default);
Vue.component('SaleComponent', require('./backend/SaleComponent/SaleComponent.vue').default);
Vue.component('SeguimientoComprobanteComponent', require('./backend/SeguimientoComprobanteComponent/SeguimientoComprobanteComponent.vue').default);
Vue.component('SeriesComponent', require('./backend/SeriesComponent/SeriesComponent.vue').default);
Vue.component('ContactComponent', require('./backend/ContactComponent/ContactComponent.vue').default);
Vue.component('LicenseComponent', require('./backend/LicenseComponent/LicenseComponent.vue').default);
Vue.component('SynchronizationComponent', require('./backend/SynchronizationComponent/SynchronizationComponent.vue').default);
Vue.component('AssignPermissionComponent', require('./backend/AssignPermissionComponent/AssignPermissionComponent.vue').default);

Vue.component('QuotateComponent', require('./backend/QuotateComponent/QuotateComponent.vue').default);
Vue.component('QuotateCreateComponent', require('./backend/QuotateComponent/CreateComponent.vue').default);


const app = new Vue({
  el: '#app',
});
