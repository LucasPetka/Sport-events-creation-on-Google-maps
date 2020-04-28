<template>
  <div id="press" oncontextmenu="return false;">
    
      <div id="geoloc_bar">
        <div class="input-group">
          <gmap-autocomplete class="form-control" id="autocomplete_gmap" name="autocomplete_gmap" :select-first-on-enter="true" @place_changed="setPlace"></gmap-autocomplete> 
          <div class="input-group-append">
            <button class="btn btn-orange" data-toggle="tooltip" data-placement="bottom" title="Firstly input your address" @click="updateUserLocation_byCenter(center)">Set your location</button>
          </div>
        </div>
      </div>

      <div id="refresh_button">
        <button type="button" class="btn btn-orange-secondary" v-on:click="loadMarkers()">Refresh markers <i class="fas fa-redo"></i></button>
      </div>

    <gmap-map ref="gmapp" :map-type-id="mapType" v-on:rightclick="openMenu($event)" v-on:dragend="loadMarkers()" v-on:zoom_changed="updateZoom()" :center="center" v-on:bounds_changed="update_bounds($event)" :zoom="zoom_in" v-bind:options="mapStyle" style=" overflow:hidden; width:100%; height:94vh;">
      <gmap-cluster :zoom-on-click="true" :gridSize="20" :maxZoom="15">

      <gmap-marker v-for="place in allPlaces.data"
        :visible="place.visible" 
        :key="place.id" 
        :position="getPosition(place)" 
        @click="center=getPosition(place)" 
        v-on:click="showSpot(place.id)" 
        :icon="icon(place)" 
        v-on:mouseover="openInfoWindowTemplate(place), hovered = true" 
        v-on:mouseout="infoWindow.open = false, hovered = false" >
      </gmap-marker>

      <gmap-info-window
          :options="{maxWidth:300, pixelOffset:{width:0, height:-25}}"
          :position="infoWindow.position"
          :opened="infoWindow.open"
          v-on:mouseout="infoWindow.open=false">
          <div v-html="infoWindow.template"></div>
      </gmap-info-window>
      </gmap-cluster>
      <gmap-marker :visible="marker_visibility" :position="getPosition(addNewmark_coordinates)" :icon="{ url: require('../assets/google_maps/new.png')}" ></gmap-marker>
      <gmap-marker :position="getPosition(user_location)"  @dragend="updateUserLocation($event.latLng)"  :draggable="true" :icon="{ url: require('../assets/google_maps/current_loc.png')}" ></gmap-marker>
    </gmap-map>

    <div class="dropdown-menu dropdown-menu-sm pt-1 pb-1" id="pointerMenu" style="display:none; position: absolute;">
          <a class="dropdown-item" dusk="add_new_place_button" href="#" v-on:click="creatNewMarker()"><i class="fas fa-map-marker-alt"></i> New place</a>
          <div class="dropdown-divider m-0"></div>
          <a class="dropdown-item" href="#" v-on:click="closePointerMenu()"><i class="fas fa-times"></i> Cancel</a>
    </div>

  </div>
</template>

<script>
import mapstyle from '../assets/options.json';

import { mapGetters, mapActions } from 'vuex';
export default {
    
  name: "GoogleMap",

  props: ['status', 'location'],  //checks if someone loged in to let them add new Marker

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
      paid:'',
      highlighted:'',
    },
    infoWindow: {
      position: {lat: 0, lng: 0},
      open: false,
      template: ''
    }, 
    markerHoverTime: false,
    hovered: false,
    addNewmark_coordinates: { lat: 0.0, lng: 0.0 },
    zoom_in: 13,
    bounds: null,
    currentPlace: null,
    marker_visibility: false,
    rules:{type: 'All', distance: 'Any', paid: 'Any'},   
    mapType: 'roadmap',
    mapStyle: {
      styles: mapstyle,
      options:{
        minZoom: 10,
        gestureHandling: 'greedy',
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
    this.center = this.location;
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

        const response = await axios.get('api/liveevent/' + place.id);
        var nearest = response.data.data;

        if(nearest.length > 0){
          this.infoWindow.template = '<h6>'+place.title+'</h6>'+res+'<hr>' +          
          '<i class="fas fa-road"></i> <small>This place is '+
          this.measure_distance(place, this.user_location)+' km from you <hr><h6><span class="badge badge-danger">Live</span> '+ nearest[0].title +'</h6></small>';
        }
        else{
          this.infoWindow.template = '<h6>'+place.title+'</h6>'+res+'<hr>' + '<i class="fas fa-road"></i> <small>This place is '+
          this.measure_distance(place, this.user_location)+' km from you </small>';
        }

        this.infoWindow.position = this.getPosition(place);

        this.markerHoverTime = setTimeout(() => {
            if(this.hovered){
              this.infoWindow.open = true;
            }
        }, 500)
      

   },
   
   //Waits for variable BOUNDS and then fetches places in those bounds from DB
    checkVariable: function(){
      if ( this.bounds != null )
      {
          this.loadMarkers();
      }
      else
      {
          window.setTimeout(this.checkVariable, 10);
      }
    },

    //sets map center, main location LITHUANIA, but if person has location ON then it shows person location
    geolocation : function() {

      if(this.getCookie("lat") != null && this.getCookie("lng") != null){

        var lat = parseFloat(this.getCookie("lat"));
        var lng = parseFloat(this.getCookie("lng"));

        this.center = {lat: lat, lng: lng};
        this.user_location = {lat: lat, lng: lng};

        this.user_loc_set = true;
        this.checkVariable();
      }else{

        if (navigator.geolocation) { //if location tracking is ON
          navigator.geolocation.getCurrentPosition((position) => {
            this.bounds = null;
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

            this.center = this.location;
            this.zoom_in = 13;

            this.user_location = this.location;
            this.user_loc_set = true;

            this.checkVariable();
          });
        }
        else{           //if tracking is OFF when just locate set map center
          this.center = this.location;
          this.zoom_in = 13;

          this.user_location = this.location;
          this.user_loc_set = true;

          this.checkVariable();
        }

      }

    },

    updateUserLocation(location) {
        this.user_location = {
            lat: location.lat(),
            lng: location.lng(),
        };
        var today = new Date();
        var expire = new Date();
        expire.setTime(today.getTime() + 3600000*24*7);
        document.cookie = "lat="+ location.lat() +"; expires=" + expire.toGMTString();
        document.cookie = "lng="+ location.lng() +"; expires=" + expire.toGMTString();
    },

    updateUserLocation_byCenter(location) {
        this.user_location = {
            lat: location.lat,
            lng: location.lng,
        };
        var today = new Date();
        var expire = new Date();
        expire.setTime(today.getTime() + 3600000*24*7);
        document.cookie = "lat="+ location.lat +"; expires=" + expire.toGMTString();
        document.cookie = "lng="+ location.lng +"; expires=" + expire.toGMTString();
    },

    //---------------------------Get Cookie-------------------------------------
    getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
    },

    //smooth zooms in using timeout beetween zoom functions
    smoothZoom(max, cnt) {
        if (cnt >= max) {
            return 100;
        }
        else {
          setTimeout(() => {  this.smoothZoom(max, cnt + 1); this.zoom_in = cnt;}, 120)
          this.updateZoom();
        }
    },  


    //update bounds where are the spots are showing
    update_bounds(bounds){
      this.bounds = bounds;
    },


    //load all sports spots that are in bounds of view to reduce data traffic
    loadMarkers(){
      this.fetchPlaces_sort(this.rules);

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

    return  distanceInKilometers.toFixed(2);
    },


    //updates center
    setPlace(place) {
      
        const LocatedPlace = {
          lat: place.geometry.location.lat(),
          lng: place.geometry.location.lng()
        };
        this.center = LocatedPlace;
        this.currentPlace = null;

        this.smoothZoom(13, this.zoom_in);
        setTimeout(() => { this.loadMarkers(); }, 800)
      
    },


    //Sets the icon for the place id
    icon: function(place){

        for (let i = 0; i < this.allTypes.data.length; i++) {
          if (place.type == this.allTypes.data[i].id){
            if(place.highlighted == "1"){
                return { url: require('../../../public/storage/sport_logo/'+ this.allTypes.data[i].image_h)};
            }else{
                return { url: require('../../../public/storage/sport_logo/'+ this.allTypes.data[i].image)};
            }
          }
        }
    },

    //knows the zoom in level of map
    updateZoom: function(){
      this.$refs.gmapp.$mapPromise.then((map) => {
          this.zoom_in = map.getZoom();

          if(this.zoom_in >= 17){
            this.mapType = "satellite";
          }
          else{
            this.mapType = "roadmap";
          }

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
      const foundPlace = this.allPlaces.data.find( place => place.id == key);
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

    fetchPlaces_sort(rules) {
      var ne = this.bounds.getNorthEast();
      var sw = this.bounds.getSouthWest();
      this.rules = rules;

      this.fetchPlacesx_sort([this.bounds, rules, this.user_location]);
    },

    }
    };
</script>


<style>

.dropdown-item:hover{
  color: rgb(228, 108, 9);
}

.dropdown-item:focus{
  background-color: rgb(235, 235, 235);
}


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
  top:130px;
  left: 49%;
  -webkit-transform: translate(-49%, -40%);
  transform: translate(-49%, -40%);
  z-index: 1;
  background-color: white;
  padding: 10px 15px;
  border-radius: 8px;
  width: 500px;
}

#marker {
 display: none;
}

@media only screen and (max-width: 1670px) {
  #geoloc_bar{
    width: 400px;
  }
}



@media only screen and (max-width: 540px) {
  #geoloc_bar{
    width: 95%;
  }
}


</style>
