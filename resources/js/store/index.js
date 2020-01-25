import Vuex from 'vuex';
import Vue from 'vue';
import placesx from './modules/places';

//Load vuex
Vue.use(Vuex);

export default new Vuex.Store({
    modules:{
        placesx
    }
})