import axios from 'axios';

const state = {

placesx:[],
typesx:[],
eventsx:[]

};

const getters = {

    allPlaces: (state) => state.placesx,
    allTypes: (state) => state.typesx


};

const actions = {
    async fetchPlacesx({commit}, bounds){
        var ne = bounds.getNorthEast();
        var sw = bounds.getSouthWest();
        const response = await axios.get('api/places/'+ne.lat()+'/'+sw.lat()+'/'+ne.lng()+'/'+sw.lng());
        commit('setPlaces', response.data);
    },

    async fetchTypesx({commit}){
        const response = await axios.get('api/types');
        commit('setTypes', response.data);
    },


};

const mutations = {
    setPlaces: (state, placesxs) =>( state.placesx = placesxs),
    setTypes: (state, typesxs) =>( state.typesx = typesxs)



};


export default {
    state,
    getters,
    actions,
    mutations
}