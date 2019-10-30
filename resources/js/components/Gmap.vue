<template>
  <div oncontextmenu="return false;">
    
      <div id="geoloc_bar">
        <div class="input-group">
        <gmap-autocomplete class="form-control" @keyup.enter="locate"
          @place_changed="setPlace">
        </gmap-autocomplete>
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" @click="locate">Locate</button>
        </div>
      </div>
      </div>

      <div id="refresh_button">
        <button type="button" class="btn btn-success" v-on:click="loadMarkers()">Refresh markers <i class="fas fa-redo"></i></button>
      </div>

      
      


    <gmap-map ref="gmapp" v-on:rightclick="openMenu($event)" v-on:zoom_changed="updateZoom()" v-on:bounds_changed="update_bounds($event)" :center="center" :zoom="zoom_in" v-bind:options="mapStyle"  style="width:100%; height: 94vh;">

      
      <gmap-marker v-for="place in places" :visible="place.visible" :key="place.id" :position="getPosition(place)" @click="center=getPosition(place)" v-on:click="showSpot(place.id)" :icon="icon(place.type)" ></gmap-marker>
      

      <gmap-marker :visible="marker_visibility" :position="getPosition(addNewmark_coordinates)" :icon="{ url: require('../assets/google_maps/new.png')}" ></gmap-marker>


    </gmap-map>

    <div class="dropdown-menu dropdown-menu-sm" id="pointerMenu" style="display:none; position: absolute;">
          <a class="dropdown-item" v-on:click="creatNewMarker()">Add Marker</a>
          <a class="dropdown-item" v-on:click="closePointerMenu()">Close</a>
    </div>

  </div>
</template>

<script>

import mapstyle from '../assets/options.json'

export default {
    
  name: "GoogleMap",

  props: ['status'],  //checks if someone loged in to let them add new Marker

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
            type:'',
            visible: true
    }, 
    addNewmark_coordinates: { lat: 0.0, lng: 0.0 },
    zoom_in: 8,
    bounds: null,
    currentPlace: null,
    marker_visibility: false,   
    mapStyle: {
      styles: mapstyle,
    }

    };
  },

  mounted() {
    this.fetchPlaces();
    this.marker_visibility = this.$parent.show_new;
  },

  methods: {


    //smooth zooms in using timeout beetween zoom functions
    smoothZoom(max, cnt) {
        if (cnt >= max) {
            return 100;
        }
        else {
          setTimeout(() => {  this.smoothZoom(max, cnt + 1); this.zoom_in = cnt;}, 120)
        }
        
    },  


    //update bounds where are the spots are showing
    update_bounds(bounds){
      this.bounds = bounds;
    },


    //load all sports spots that are in bounds of view to reduce data traffic
    loadMarkers(){
        var ne = this.bounds.getNorthEast();
        var sw = this.bounds.getSouthWest();

        this.places.forEach(myFunction);
        
        function myFunction(place) {
          if(place.lat < ne.lat() && place.lat > sw.lat() && place.lng < ne.lng() && place.lng > sw.lng()){ 
            place.visible = true;
          }
          else{
            place.visible = false;
          }
        }

      this.zoom_in = this.zoom_in - 1;
      this.zoom_in = this.zoom_in + 1;

    },


    //updates center
    setPlace(place) {
      this.currentPlace = place;
    },


    //locates inputed geographic name
    locate() {
      if (this.currentPlace) {
        const marker = {
          lat: this.currentPlace.geometry.location.lat(),
          lng: this.currentPlace.geometry.location.lng()
        };
        this.center = marker;
       
        this.currentPlace = null;
          this.smoothZoom(13, this.zoom_in);
          setTimeout(() => { this.loadMarkers(); }, 800)
      }

    },


    //Sets the icon for the place id
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



    openMenu: function(loc){
      if(this.status == 1){
      this.addNewmark_coordinates = {
      lat: loc.latLng.lat(),
      lng: loc.latLng.lng(),};

      this.marker_visibility = true;
      $("#pointerMenu").css({top: event.clientY, left: event.clientX}).show();
      }
    },

    closePointerMenu: function(){
      $("#pointerMenu").hide();
      this.marker_visibility = false;
    },



    creatNewMarker: function(){
            this.$emit('openForm', this.addNewmark_coordinates);
            $("#pointerMenu").hide();
            this.marker_visibility = true;
      },


    hidePointer: function(){
      this.marker_visibility = false;
    },




        
    showSpot: function(key){
        const foundPlace = this.places.find( place => place.id == key);
        this.place = foundPlace;

        this.smoothZoom(17, this.zoom_in);

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

    }
    };
</script>


<style scoped>

#refresh_button{
  position: absolute;
  bottom:20px;
  right:45%;
  z-index: 5;
}

#geoloc_bar{
  position: absolute;
  top:70px;
  right:41%;
  z-index: 5;
  background-color: white;
  padding: 10px 15px;
  border-radius: 8px;
  width: 350px;
}

#marker {
 display: none;
  }

</style>
