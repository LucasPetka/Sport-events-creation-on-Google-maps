require('./bootstrap');

window.Vue = require('vue');

Vue.component('places', require('./components/Places.vue').default);
Vue.component('gmap', require('./components/Gmap.vue').default);
Vue.component('calendar', require('./components/Calendar.vue').default);
Vue.component('smallmap', require('./components/SmallMap.vue').default);
Vue.component('chats', require('./components/Chats.vue').default);

import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'
import Notifications from 'vue-notification'
import GmapCluster from 'vue2-google-maps/dist/components/cluster'
import Datetime from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'
import store from './store/index.js';

Vue.use(Notifications);
Vue.use(Datetime);
Vue.component('GmapCluster', GmapCluster);

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyBQxzhnAAV7IpsN2kjtER2X2Je00Lpnmm8',
    libraries: ['places','geometry'],
  },
   autobindAllEvents: false,
})


const app = new Vue({
  store,
    el: '#app',
});

