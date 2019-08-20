<template>
  <div>
    <gmap-map ref="gmapp" v-on:rightclick="creatNewMarker($event)" v-on:zoom_changed="updateZoom()" :center="center" :zoom="zoom_in" :options="{minZoom: 3}" style="width:100%; height: 94vh;">

      <gmap-marker v-for="place in places" :key="place.id" :position="getPosition(place)" @click="center=getPosition(place)" v-on:click="showSpot(place.id)" :icon="icon(place.type)" ></gmap-marker>

      <gmap-marker :visible="a" :position="getPosition(coordinates)" :icon="{ url: require('../assets/google_maps/new.png')}" ></gmap-marker>
    </gmap-map>

  </div>
</template>

<script>


export default {
    
  name: "GoogleMap",

  data() {
    return {
      // default to Montreal to keep it simple
      // change this to whatever makes sense
    center: { lat: 55.205448395768826, lng: 23.930382446707117 },
    places: [],
    types: [],
        place: {
            id:'',
            title:'',
            about:'',
            lat:'',
            lng:'',
            type:''
    }, 

    place_id:'',
    coordinates: { lat: 45.508, lng: -73.587 },
    zoom_in: 8,
    edit: false,
    currentPlace: null,
    a: false
    };
  },

  mounted() {
    this.geolocate();
    this.fetchPlaces();
    this.a = this.$parent.show_new;
  },

  methods: {

hidePointer: function(){
          this.a = false;
    },
    icon: function(type){
        if(type=="112" || type=="111"){
            return { url: require('../assets/google_maps/soccerball.png')};
        }
        else if(type=="223" || type=="222"){
            return { url: require('../assets/google_maps/basketball.png')};
        }
        else{
            return { url: require('../assets/google_maps/volleyball.png')};
        }
    },

    updateZoom: function(){
        this.$refs.gmapp.$mapPromise.then((map) => {
                this.zoom_in = map.getZoom();
            });
    },

    creatNewMarker: function(location){
            this.coordinates = {
                lat: location.latLng.lat(),
                lng: location.latLng.lng(),
            };
            this.$emit('openForm', this.coordinates);
            this.a = true;
      },

        
    showSpot: function(key){
        const foundPlace = this.places.find( place => place.id == key);
        this.place = foundPlace;
        this.zoom_in = 17;

        this.$emit('showSpot', key);
    },


    getPosition: function(place) {
      return {
        lat: parseFloat(place.lat),
        lng: parseFloat(place.lng)
    }
    },

    fetchPlaces() {
            fetch('api/places')
            .then(res => res.json())
            .then(res => {
                this.places = res.data;
            })
    },

    // receives a place object via the autocomplete component
    setPlace(place) {
      this.currentPlace = place;
    },

    geolocate: function() {
      navigator.geolocation.getCurrentPosition(position => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
            };
        });
        }
    }
    };
</script>


<style scoped>

#marker {
 display: none;
  }

</style>
