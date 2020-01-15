<template>
    <div class="container-fluid">
                <gmap-map :center="getPosition()" v-bind:options="mapStyle" :zoom="14" :style="size">
                    <gmap-marker :position="getPosition()"></gmap-marker>
                </gmap-map>
    </div>
</template>

<script>

import mapstyle from '../assets/options.json'

export default {

    props: ['place', 'size'],

    data() {
        return {
        center: { lat: 55.205448395768826, lng: 23.930382446707117 }, //the center of the map "LITHUANIA"
        
        mapStyle: {
        styles: mapstyle,
        options:{
            fullscreenControl: true,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false,
            zoomControl: true
        }
        }

        };
    },

    mounted() {
    
    },

    methods: {

        getPosition: function() { 
        return {
            lat: parseFloat(this.$props.place.lat),
            lng: parseFloat(this.$props.place.lng)
        }


    },

    }
};
</script>