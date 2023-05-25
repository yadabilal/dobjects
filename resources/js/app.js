/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueMask from 'v-mask';

window.Vue = require('vue');
Vue.use(VueMask);
Vue.use(require('vue-resource'));
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('admin-component', require('./components/AdminComponent.vue').default);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/* Address Components*/
Vue.component('address-list-component', require('./components/MemberShipPanel/Address/ListComponent.vue').default);
Vue.component('address-search-component', require('./components/MemberShipPanel/Address/SearchComponent.vue').default);
Vue.component('address-form-component', require('./components/MemberShipPanel/Address/FormComponent.vue').default);
/* Post Components */
Vue.component('post-list-component', require('./components/MemberShipPanel/Post/ListComponent.vue').default);
Vue.component('post-form-component', require('./components/MemberShipPanel/Post/FormComponent.vue').default);
Vue.component('post-search-component', require('./components/MemberShipPanel/Post/SearchComponent.vue').default);
/*  Advertisement Components */
Vue.component('advertisement-list-component', require('./components/MemberShipPanel/Advertisement/ListComponent.vue').default);
Vue.component('advertisement-form-component', require('./components/MemberShipPanel/Advertisement/FormComponent.vue').default);
Vue.component('advertisement-search-component', require('./components/MemberShipPanel/Advertisement/SearchComponent.vue').default);
Vue.component('advertisement-tab-about-component', require('./components/MemberShipPanel/Advertisement/TabAboutComponent.vue').default);
Vue.component('advertisement-tab-book-owners-component', require('./components/MemberShipPanel/Advertisement/TabBookOwners.vue').default);
Vue.component('advertisement-tab-same-advertisement-users-component', require('./components/MemberShipPanel/Advertisement/TabSameAdvertisementUsers.vue').default);
/* BookCase Components  */
Vue.component('bookcase-list-component', require('./components/MemberShipPanel/BookCase/ListComponent.vue').default);
Vue.component('bookcase-form-component', require('./components/MemberShipPanel/BookCase/FormComponent.vue').default);
Vue.component('bookcase-search-component', require('./components/MemberShipPanel/BookCase/SearchComponent.vue').default);
Vue.component('bookcase-tab-request-component', require('./components/MemberShipPanel/BookCase/TabRequestComponent.vue').default);
Vue.component('bookcase-tab-who-read-component', require('./components/MemberShipPanel/BookCase/TabWhoReadComponent.vue').default);
Vue.component('bookcase-tab-gratitude-component', require('./components/MemberShipPanel/BookCase/TabGratitudeComponent.vue').default);
Vue.component('bookcase-tab-about-component', require('./components/MemberShipPanel/BookCase/TabAboutComponent.vue').default);
/* User settings*/
Vue.component('setting-list-component', require('./components/MemberShipPanel/Setting/ListComponent.vue').default);
/* Orders */
Vue.component('order-demand-list-component', require('./components/MemberShipPanel/Demand/ListComponent.vue').default);
Vue.component('order-demand-search-component', require('./components/MemberShipPanel/Demand/SearchComponent.vue').default);
Vue.component('order-demand-form-component', require('./components/MemberShipPanel/Demand/FormComponent.vue').default);
Vue.component('demand-tab-owner-component', require('./components/MemberShipPanel/Demand/TabBookOwners.vue').default);
/* Resuest*/
Vue.component('order-request-list-component', require('./components/MemberShipPanel/Request/ListComponent.vue').default);
Vue.component('order-request-search-component', require('./components/MemberShipPanel/Request/SearchComponent.vue').default);
Vue.component('order-request-form-component', require('./components/MemberShipPanel/Request/FormComponent.vue').default);

// Layouts
Vue.component('layouts-book-search-component', require('./components/MemberShipPanel/Layouts/BookSearchComponent.vue').default);
Vue.component('layouts-book-card-component', require('./components/MemberShipPanel/Layouts/BookCardComponent.vue').default);


Vue.component('pagination', require('laravel-vue-pagination'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
