<template>
  <div oncontextmenu="return false;">
    
      <div id="geoloc_bar">
        <div class="input-group">
          <gmap-autocomplete class="form-control" @keyup.enter="locate" @place_changed="setPlace"></gmap-autocomplete> 
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" @click="locate">Locate</button>
          </div>
        </div>
      </div>

      <div id="refresh_button">
        <button type="button" class="btn btn-success" v-on:click="loadMarkers()">Refresh markers <i class="fas fa-redo"></i></button>
      </div>

    <gmap-map ref="gmapp" v-on:rightclick="openMenu($event)" v-on:zoom_changed="updateZoom()" :center="center" v-on:bounds_changed="update_bounds($event)" :zoom="zoom_in" v-bind:options="mapStyle" style="width:100%; height:94vh;">
      <gmap-cluster :zoom-on-click="true" :gridSize="40" :maxZoom="16">
      <gmap-marker v-for="place in allPlaces.data" :visible="place.visible" :key="place.id" :position="getPosition(place)" @click="center=getPosition(place)" v-on:click="showSpot(place.id)" :icon="icon(place.type)" v-on:mouseover="openInfoWindowTemplate(place)" v-on:mouseout="infoWindow.open=false"></gmap-marker>
      <gmap-info-window
          :options="{maxWidth:300, pixelOffset:{width:0, height:-25}}"
          :position="infoWindow.position"
          :opened="infoWindow.open"
          v-on:mouseout="infoWindow.open=false">
          <div v-html="infoWindow.template"></div>
      </gmap-info-window>
      </gmap-cluster>
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
import { mapGetters, mapActions } from 'vuex';

export default {
    
  name: "GoogleMap",

  props: ['status'],  //checks if someone loged in to let them add new Marker

  data() {
    return {
    center: { lat: 55.205448395768826, lng: 23.930382446707117 }, //the center of the map "LITHUANIA"
    user_location: {lat: 0, lng: 0},
    user_loc_set: false,
    places: [],
    types: [],
    place: {
      id:'',
      title:'',
      about:'',
      lat:'',
      lng:'',
      type:'',
    },
    infoWindow: {
      position: {lat: 0, lng: 0},
      open: false,
      template: ''
    }, 
    addNewmark_coordinates: { lat: 0.0, lng: 0.0 },
    zoom_in: 13,
    bounds: null,
    currentPlace: null,
    marker_visibility: false,   
    
    mapStyle: {
      styles: mapstyle,
      options:{
        fullscreenControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        zoomControl: false
      }
    }
    };
  },

  created() {
    this.fetchTypesx();
    this.geolocation();
    this.checkVariable();
  },

  computed:  mapGetters(['allPlaces', 'allTypes']),

  methods: {

     ...mapActions(['fetchPlacesx', 'fetchTypesx', 'fetchPlacesx_sort']),

    //Opens info window above marker and sets the position and text
     openInfoWindowTemplate: async function(place) {
      var res = "";

        if(place.about.length > 100){
          res = place.about.slice(0, 100) + "...";
        }else{
          res = place.about;
        }

      const response = await axios.get('api/nearevent/' + place.id);
      
      var nearest = response.data.data;

      if(nearest.length > 0){
        this.infoWindow.template = '<h6>'+place.title+'</h6>'+res+'<hr>' +
        '<i class="fas fa-road"></i> <small>This place is '+
        this.measure_distance(place, this.user_location)+' km from you <br><br><span style="color:red; font-size: 13pt; "> LIVE - '+ nearest[0].title +'</span></small>';
      }
      else{
        this.infoWindow.template = '<h6>'+place.title+'</h6>'+res+'<hr>' + '<i class="fas fa-road"></i> <small>This place is '+
        this.measure_distance(place, this.user_location)+' km from you </small>';
      }

      this.infoWindow.position = this.getPosition(place);
      this.infoWindow.open = true;
   },
   
   //Waits for variable BOUNDS and then fetches places in those bounds from DB
    checkVariable: function(){
      if ( this.bounds != null )
      {
          this.loadMarkers();
          this.fetchPlaces();
      }
      else
      {
          window.setTimeout(this.checkVariable, 50);
      }
    },

    //sets map center, main location LITHUANIA, but if person has location ON then it shows person location
    geolocation : function() {

      if(!this.user_loc_set){
        if (navigator.geolocation) { //if location tracking is ON
          navigator.geolocation.getCurrentPosition((position) => {

            this.center = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            this.user_location = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            this.user_loc_set = true;

            this.checkVariable();

          }, err =>{    //if something goes wrong when locating just set map center
            this.center = {
              lat: 55.205448395768826, 
              lng: 23.930382446707117
            };
            this.zoom_in = 8;
          });
        }
        else{           //if tracking is OFF when just locate set map center
          this.center = {
            lat: 55.205448395768826, 
            lng: 23.930382446707117
          };
          this.zoom_in = 8;
        }
      }
    },


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
      this.fetchPlaces();

      this.zoom_in = this.zoom_in - 1;
      this.zoom_in = this.zoom_in + 1;

    },

    //Measures distance between two markers
    measure_distance(from, to){
    var distanceInMeters = google.maps.geometry.spherical.computeDistanceBetween(
        new google.maps.LatLng(this.getPosition(from)),
        new google.maps.LatLng(this.getPosition(to))
    );
    var distanceInKilometers = distanceInMeters * 0.001;
    console.log(distanceInKilometers);
    return  distanceInKilometers.toFixed(2);
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

        for (let i = 0; i < this.allTypes.data.length; i++) {
          if (type == this.allTypes.data[i].id){
            return { url: require('../../../public/storage/sport_logo/'+ this.allTypes.data[i].image)};
          }
        }
    },

    //knows the zoom in level of map
    updateZoom: function(){
      this.$refs.gmapp.$mapPromise.then((map) => {
          this.zoom_in = map.getZoom();
      });
    },

    //Opens right click menu
    openMenu: function(loc){
      if(this.status == 1){
      this.addNewmark_coordinates = {
      lat: loc.latLng.lat(),
      lng: loc.latLng.lng(),};

      this.marker_visibility = true;
      $("#pointerMenu").css({top: event.clientY, left: event.clientX}).show();
      }
    },

    //Closes right click menu
    closePointerMenu: function(){
      $("#pointerMenu").hide();
      this.marker_visibility = false;
    },

    //Opens create new marker box
    creatNewMarker: function(){
      this.$emit('openForm', this.addNewmark_coordinates);
      $("#pointerMenu").hide();
      this.marker_visibility = true;
    },

    //hides new place marker
    hidePointer: function(){
      this.marker_visibility = false;
    },
        
    //on click show information about this place
    showSpot: function(key){
      const foundPlace = this.places.find( place => place.id == key);
      this.place = foundPlace;
      var arg = [key, this.measure_distance(this.place,this.user_location)];
      this.smoothZoom(17, this.zoom_in);
      this.$emit('showSpot', arg);
    },

    //Gets position of the marker
    getPosition: function(place) {
      return {
        lat: parseFloat(place.lat),
        lng: parseFloat(place.lng)
      }
    },
    
    //gets all places from data base
    fetchPlaces() {
      
      var ne = this.bounds.getNorthEast();
      var sw = this.bounds.getSouthWest();

      this.fetchPlacesx(this.bounds);

      fetch('api/places/'+ne.lat()+'/'+sw.lat()+'/'+ne.lng()+'/'+sw.lng())
      .then(res => res.json())
      .then(res => {
          this.places = res.data;
      })
    },

    fetchPlaces_sort(rules) {
      var ne = this.bounds.getNorthEast();
      var sw = this.bounds.getSouthWest();
      console.log(rules);


      this.fetchPlacesx_sort([this.bounds, rules, this.user_location]);
    },

    }
    };
</script>


<style>

#refresh_button{
  position: absolute;
  bottom:20px;
  left: 49%;
  -webkit-transform: translate(-49%, -40%);
  transform: translate(-49%, -40%);
  z-index: 5;
}

#geoloc_bar{
  position: absolute;
  top:120px;
  left: 49%;
  -webkit-transform: translate(-49%, -40%);
  transform: translate(-49%, -40%);
  z-index: 1;
  background-color: white;
  padding: 10px 15px;
  border-radius: 8px;
  width: 300px;
}

#marker {
 display: none;
}


</style>
