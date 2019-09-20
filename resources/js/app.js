require('./bootstrap');

window.Vue = require('vue');


Vue.component('places', 
require('./components/Places.vue').default);

Vue.component('navbar', 
require('./components/Navbar.vue').default);

Vue.component('gmap', 
require('./components/Gmap.vue').default);

Vue.component('calendar', 
require('./components/Calendar.vue').default);

Vue.component('profile', 
require('./components/Profile.vue').default);

import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'

import GmapCluster from 'vue2-google-maps/dist/components/cluster' // replace src with dist if you have Babel issues

import Datetime from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

Vue.use(Datetime)
 
Vue.component('GmapCluster', GmapCluster)


Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyBQxzhnAAV7IpsN2kjtER2X2Je00Lpnmm8',
    libraries: 'places, clusters', // This is required if you use the Autocomplete plugin

    // OR: libraries: 'places,drawing'
    // OR: libraries: 'places,drawing,visualization'
    // (as you require)
 
    //// If you want to set the version, you can do so:
    // v: '3.26',
  },
 
  //// If you intend to programmatically custom event listener code
  //// (e.g. `this.$refs.gmap.$on('zoom_changed', someFunc)`)
  //// instead of going through Vue templates (e.g. `<GmapMap @zoom_changed="someFunc">`)
  //// you might need to turn this on.
   autobindAllEvents: false,
 
  //// If you want to manually install components, e.g.
  //// import {GmapMarker} from 'vue2-google-maps/src/components/marker'
  //// Vue.component('GmapMarker', GmapMarker)
  //// then disable the following:
  // installComponents: true,
})


const app = new Vue({
    el: '#app',
});
