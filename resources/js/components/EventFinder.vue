<template>
        <div>
            <div class="row">  
            <div class="col-lg-6 mb-4">   
            <div class="card">
                <div class="card-body">

                    <form @keydown.enter.prevent="">

                        <gmap-autocomplete class="form-control mb-1" @place_changed="setPlace" :select-first-on-enter="true"></gmap-autocomplete>
                        
                        <gmap-map class="rounded" ref="gmapp" :center="center" :zoom="14" v-bind:options="mapStyle" style=" overflow:hidden; width:100%; height:300px;">
                            <gmap-marker @drag="updateCoordinates" :draggable="true" :position="getPosition(user_location)"></gmap-marker>
                            <GmapCircle
                                ref="circleRef"
                                :center="getPosition(user_location)"
                                :radius="1000*rules.distance"
                                :visible="true"
                                :options="{fillColor:'blue',fillOpacity:0.1}"
                            ></GmapCircle>
                        </gmap-map>

                        <p class="text-muted text-center">You can grab marker and move it all around.</p>

                        <div class="mb-2 mt-3 bg-light">Search Radius</div>
                        <vue-slider :adsorb="true" v-on:change="sliderChange()" :process-style="{ backgroundColor: '#F39448' }" :tooltip-style="{ backgroundColor: '#596151', borderColor: '#596151' }" class="mr-3 ml-3 mb-5 mb-3" v-model="rules.distance" :min="1" :max="20" :tooltip-formatter="formatter" :tooltip="'always'" :tooltip-placement="'bottom'"></vue-slider>

                        <div class="mb-1 bg-light">Event dates</div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-5">
                                <div style="width:35px" class="text-muted d-inline-block">From</div> <datepicker class="mb-1 mt-2 d-inline-block" placeholder="Select Date" :format="format" :value="rules.date_from" v-model="rules.date_from"></datepicker>
                            </div>
                            <div class="col-lg-5">
                                <div style="width:35px" class="text-muted d-inline-block">To</div> <datepicker class="mb-1 mt-2 d-inline-block" placeholder="Select Date" :format="format" :value="rules.date_until" v-model="rules.date_until"></datepicker>
                            </div>
                        </div>

                        <div class="mb-2 bg-light">Sport Types</div>
                        <div v-for="type in allTypes.data" :key="type.id" class="custom-control custom-checkbox d-inline-block mr-3">
                            <input type="checkbox" v-model="rules.types" :value="type.id" class="custom-control-input" :id="type.id">
                            <label class="custom-control-label" :for="type.id">{{ type.name }}</label>
                        </div>
                        

                        <div class="custom-control custom-checkbox mt-3 mb-3">
                            <input type="checkbox" name="paid" class="custom-control-input" v-model="rules.paid" id="paid">
                            <label class="custom-control-label" for="paid" >Paid</label>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlSelect2">People going</label>
                            <select v-model="rules.people_going" class="form-control" id="exampleFormControlSelect2">
                                <option 
                                    v-for="listValue in PeopleGoingList" 
                                    :key="listValue.value"
                                    :selected="rules.people_going == listValue.value"
                                    :value="listValue.value"
                                >
                                    {{listValue.name}}
                                </option>
                            </select>
                        </div>

                        <button type="button" v-on:click="findEvents" class="btn btn-orange btn-block mt-3"> <i class="fas fa-search"></i> Search</button>

                    </form>

                </div>
            </div>
            </div>  

            <div id="event_search_results" class="col-lg-6 mb-5">

                <div class="btn-toolbar justify-content-center mb-3" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mr-2" role="group" aria-label="First group">
                        <button type="button" id="btn1" v-on:click="updateNavingation($event)" class="btn btn-orange profile_nav" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-search"></i> Search</button>
                        <button type="button" id="btn2" v-on:click="updateNavingation($event)" class="btn btn-orange-secondary profile_nav" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Recommended <i class="fas fa-thumbs-up"></i></button>
                    </div>
                </div>

                <div id="accordion">
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div v-if="!loader_time_for_event_finder">
                            <div v-if="events.length != 0">
                            <div v-for="event in pageOfItems" :key="event.id" class="card mb-2">
                                <div class="card-body p-2">
                                    <a target="_blank" :href="'/event/' + event.id +'/'+ event.title" class="nav-link ml-2 p-0 extend">
                                        <img :src="'../../../storage/sport_logo/'+ event.type.image" :alt="event.title"> {{ event.title }}
                                    </a>

                                    <hr class="m-2">

                                    <div class="p-2">{{ event.about.slice(0, 140) }}...</div>

                                    <hr class="m-2">

                                    <div class="row p-2">
                                        <div class="col-3">
                                            <i class="far fa-calendar-alt"></i> {{ getDate(event.time_from) }}
                                        </div>
                                        <div class="col-3">
                                            <i class="far fa-clock"></i> {{ getTime(event.time_from) }}-{{ getTime(event.time_until) }}
                                        </div>
                                        <div class="col-3">
                                            <i class="fas fa-users"></i> {{ event.people_going }} going
                                        </div>
                                        <div class="col-3">
                                            <i class="fas fa-road"></i> {{ countDistance(event.lat, event.lng) }} km
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="text-center mb-4" v-else>
                                <span class="text-muted">Sorry, no events found..</span>
                            </div>

                            <div class="row justify-content-around">
                            <jw-pagination :pageSize="4" :maxPages="3" :items="events" @changePage="onChangePage"></jw-pagination>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="spinner-border mx-auto mt-5 text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        
                        <div v-if="recommended_events.length != 0">
                        <div v-for="event in recommended_events" :key="event.id" class="card mb-2">
                            <div class="card-body p-2">
                                <a target="_blank" :href="'/event/' + event.id +'/'+ event.title" class="nav-link ml-2 p-0 extend">
                                    <img :src="'../../../storage/sport_logo/'+ event.type.image" :alt="event.title"> {{ event.title }}
                                </a>

                                <hr class="m-2">

                                <div class="p-2">{{ event.about.slice(0, 140) }}...</div>

                                <hr class="m-2">

                                <div class="row p-2">
                                    <div class="col-3">
                                        <i class="far fa-calendar-alt"></i> {{ getDate(event.time_from) }}
                                    </div>
                                    <div class="col-3">
                                        <i class="far fa-clock"></i> {{ getTime(event.time_from) }}-{{ getTime(event.time_until) }}
                                    </div>
                                    <div class="col-3">
                                        <i class="fas fa-users"></i> {{ event.people_going }} going
                                    </div>
                                    <div class="col-3">
                                        <i class="fas fa-road"></i> {{ countDistance(event.lat, event.lng) }} km
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="text-center mb-4" v-else>
                            <span class="text-muted">Sorry, no events to recommend..</span>
                        </div>


                    </div>
                </div>




            </div>

            </div>



       
        </div>
</template>    


<script>
const Datepicker = () => import("vuejs-datepicker");
import { mapGetters, mapActions } from 'vuex';
import mapstyle from '../assets/options.json'

export default {

    components: {
    Datepicker
    },

    props: ['status', 'currentUser', 'ip'], //checks if someone loged in and gets all information about user

    computed: mapGetters(['allTypes']),
    
    data(){
        return{
            center:{lat: 0, lng: 0},
            user_location: {lat: 0, lng: 0},
            user_loc_set: false,
            loader_time_for_event_finder:false,
            formatter: '{value} km',
            format:'yyyy-MM-dd',
            events: [],
            recommended_events:[],
            pageOfItems: [],
            data: ['1', '2', '3', '5', '10', '20'],
            rules:{
                lat: 0, lng: 0, distance: 1, types: [], date_from: new Date(), date_until: new Date(), paid: false, people_going: -1
            },
            recommended_rules:{lat: 0, lng: 0, status: '', liked_sports:[], date: new Date(), user_id: ''},
            PeopleGoingList: [
            {name: 'Any', value: -1},
            {name: 'More than 0', value: 0},
            {name: 'More than 5', value: 5},
            {name: 'More than 10', value: 10},
            {name: 'More than 20', value: 20},
            ],
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
        }
    },

    created(){
        this.user_location = this.ip;
        this.fetchTypesx();
    },

    //ONLOAD PAGE DO THIS
    mounted(){
        this.geolocation(); // get location of person
        this.sliderChange();
    },

    //================================METHODS========================================
    methods: {

        

        ...mapActions(['fetchTypesx']),

        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },

        sliderChange(){
            this.$refs.circleRef.$circlePromise.then(() => {
                const {$circleObject} = this.$refs.circleRef; //get google.maps.Circle object
                const map = $circleObject.getMap();
                map.fitBounds($circleObject.getBounds());
            })
        },

        updateCoordinates(location) {
            this.user_location = {
                lat: location.latLng.lat(),
                lng: location.latLng.lng(),
            };
        },

        getDate(date){
            let current_datetime = new Date(date)
            let formatted_date = current_datetime.getFullYear() + "-" + ('0' + (current_datetime.getMonth()+1)).slice(-2) + "-" + ('0' + current_datetime.getDate()).slice(-2)
            return formatted_date;
        },

        getTime(date){
            let current_datetime = new Date(date)
            let formatted_Time = ('0' + current_datetime.getHours()).slice(-2) + ":" + ('0' + current_datetime.getMinutes()).slice(-2)
            return formatted_Time;
        },

        countDistance(lat, lng){
            const event_location = {
                lat: lat,
                lng: lng
            };

            var distanceInMeters = google.maps.geometry.spherical.computeDistanceBetween(
                new google.maps.LatLng(this.getPosition(this.user_location)),
                new google.maps.LatLng(this.getPosition(event_location))
            );
            var distanceInKilometers = distanceInMeters * 0.001;
            console.log(distanceInKilometers);
            return  distanceInKilometers.toFixed(2);
        },

        //sets map center, main location LITHUANIA, but if person has location ON then it shows person location
        geolocation : function() {

        if(this.getCookie("lat") != null && this.getCookie("lng") != null){

            var lat = parseFloat(this.getCookie("lat"));
            var lng = parseFloat(this.getCookie("lng"));

            this.center = {lat: lat, lng: lng};
            this.user_location = {lat: lat, lng: lng};

            this.user_loc_set = true;

            this.findEvents();
            this.findRecommendedEvents();
        }else{

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

                    this.findEvents();
                    this.findRecommendedEvents();
                    console.log("trackingON");

                }, err =>{    //if something goes wrong when locating just set map center
                    
                    this.user_location = this.ip;
                    this.center = this.ip;
                    this.user_loc_set = true;
                    this.findEvents();
                    this.findRecommendedEvents();
                    console.log("somtehing wrong: " + err.message);
                });
                }
                else{           //if tracking is OFF when just locate set map center
                    this.user_location = this.ip;
                    this.center = this.ip;
                    this.user_loc_set = true;
                    this.findEvents();
                    this.findRecommendedEvents();
                    console.log("tracking OFF");
                }
            }

        }
        },

        //Get all people that going to events
        findEvents() {

            $('html, body').animate({
                    scrollTop: $("#event_search_results").offset().top
                }, 'fast');

            this.loader_time_for_event_finder = true;
            setTimeout(() => {
                this.loader_time_for_event_finder = false;
            }, 1000);

            this.rules.lat = this.user_location.lat;
            this.rules.lng = this.user_location.lng;

            this.rules.date_from = new Date(this.rules.date_from.setHours(0,0,0));
            this.rules.date_until = new Date(this.rules.date_until.setHours(23,59,59));

            const url = 'api/find_events';
            const options = {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json;charset=UTF-8'
                },
                body: JSON.stringify(this.rules)
            };

                fetch(url, options)
                .then(res => res.json())
                .then(res => {
                    this.events = res.data;
                })
        },

        findRecommendedEvents(){
            this.recommended_rules.lat = this.user_location.lat;
            this.recommended_rules.lng = this.user_location.lng;
            this.recommended_rules.status = this.status;
            this.recommended_rules.liked_sports = this.currentUser.liked_sports;
            this.recommended_rules.user_id = this.currentUser.id;

            const url = 'api/find_recommended_events';
            const options = {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json;charset=UTF-8'
                },
                body: JSON.stringify(this.recommended_rules)
            };

                fetch(url, options)
                .then(res => res.json())
                .then(res => {
                    this.recommended_events = res.data;
                })

        },

        //updates center
        setPlace(place) {
            this.center = {
                lat: parseFloat(place.geometry.location.lat()),
                lng: parseFloat(place.geometry.location.lng())
            }
            this.user_location = {
                lat: parseFloat(place.geometry.location.lat()),
                lng: parseFloat(place.geometry.location.lng())
            }
        },

        //Gets position of the marker
        getPosition: function(place) {
            return {
                lat: parseFloat(place.lat),
                lng: parseFloat(place.lng)
            }
        },

        getCookie(name) {
            var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return v ? v[2] : null;
        },

        updateNavingation(event){

            console.log(event.currentTarget.id);
            $.each($('.profile_nav'), function() {
                if(this.id == event.currentTarget.id){
                    $('#'+this.id).removeClass("btn-orange-secondary");
                    $('#'+this.id).addClass("btn-orange");
                }
                else{
                    $('#'+this.id).removeClass("btn-orange");
                    $('#'+this.id).addClass("btn-orange-secondary");
                }
            });

        },

        

    }
}
</script>

<style>

.vdp-datepicker input{
    border-radius: 5px 5px 5px 5px;
    width: 110px;
    box-shadow: none !important;
    border: solid 1px #b7b7b7;
    padding: 8px;
    outline: none !important;
}

.top .vdp-datepicker__calendar {
position: absolute;
top: -282px;
}



</style>

