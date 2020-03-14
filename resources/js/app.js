require('./bootstrap');

window.Vue = require('vue');

Vue.component('places', require('./components/Places.vue').default);
Vue.component('gmap', require('./components/Gmap.vue').default);
Vue.component('calendar', require('./components/Calendar.vue').default);
Vue.component('smallmap', require('./components/SmallMap.vue').default);
Vue.component('chats', require('./components/Chats.vue').default);
Vue.component('pagination', require('./components/Pagination.vue').default);
Vue.component('payment', require('./components/Payment.vue').default);
Vue.component('eventfinder', require('./components/EventFinder.vue').default);
Vue.component('editevent', require('./components/EditEvent.vue').default);
Vue.component('showprofileinfo', require('./components/ShowProfileInfo.vue').default);

import Vue from 'vue'

import * as VueGoogleMaps from 'vue2-google-maps'
import GmapCluster from 'vue2-google-maps/dist/components/cluster'
import VueChatScroll from 'vue-chat-scroll'
import Notifications from 'vue-notification'
import store from './store/index.js'
import JwPagination from 'jw-vue-pagination'
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'


Vue.component('VueSlider', VueSlider)
Vue.component('jw-pagination', JwPagination);
Vue.use(VueChatScroll)
Vue.use(Notifications);
Vue.component('GmapCluster', GmapCluster);


Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyBQxzhnAAV7IpsN2kjtER2X2Je00Lpnmm8',
    libraries: ['places','geometry'],
    region: 'GB',
    language: 'EN',
  },
   autobindAllEvents: false,
})


const app = new Vue({
  store,
    el: '#app',
});

