<template>
    <div class="container-fluid p-0">
                <gmap-map :center="center" v-bind:options="mapStyle" :zoom="14" :style="size">
                    <gmap-marker :draggable="drag" @drag="updateCoordinates" :position="coords"></gmap-marker>
                </gmap-map>

                <input type="hidden" id="lat" name="lat" :value="coords.lat">
                <input type="hidden" id="lng" name="lng" :value="coords.lng">
    </div>
</template>

<script>

import mapstyle from '../assets/options.json'

export default {

    props: ['place', 'size', 'drag'],

    data() {
        return {
        center: { lat: 55.205448395768826, lng: 23.930382446707117 }, //the center of the map "LITHUANIA"
        coords:{
            lat: 0,
            lng: 0
        },
        mapStyle: {
        styles: mapstyle,
        options:{
            gestureHandling: 'greedy',
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



        this.center = {
                lat: parseFloat(this.$props.place.lat),
                lng: parseFloat(this.$props.place.lng)
        };

        this.coords = {
                lat: parseFloat(this.$props.place.lat),
                lng: parseFloat(this.$props.place.lng)
        };
    
    },

    methods: {

    updateCoordinates(location) {
            this.coords = {
                lat: location.latLng.lat(),
                lng: location.latLng.lng(),
            };
        },

    }
};
</script>