<template>
        <div>

            <h4 class="mt-4">Recommended</h4>

            <div v-if="loaded_data == false" class="row">
                <div class="spinner-border text-dark mt-4 mx-auto" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div v-else>
                <div v-if="recommended_events.length != 0" style="height:340px; overflow: hidden; overflow-y: auto;">
                <div v-for="event in recommended_events" :key="event.id" class="card mb-2">
                    <div class="card-header p-2 shadow">
                        <a target="_blank" :href="'/event/' + event.id +'/'+ event.title" class="nav-link m-0 p-0 float-left extend">
                            <img :src="'../../../storage/sport_logo/'+ event.type.image" :alt="event.title"> {{ event.title }}
                        </a>

                    

                        <div class="float-right"><i class="fas fa-users "></i> {{ event.people_going }}</div>
                    </div>
                    <div class="card-body p-2">
                        <div class="row p-2">
                            <div class="col-4">
                                <i class="far fa-calendar-alt"></i> {{ getDate(event.time_from) }}
                            </div>
                            <div class="col-4">
                                <i class="far fa-clock"></i> {{ getTime(event.time_from) }}-{{ getTime(event.time_until) }}
                            </div>
                            <div class="col-4">
                                <i class="fas fa-road"></i> {{ countDistance(event.lat, event.lng) }} km
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="text-center mb-4 mt-3" v-else>
                    <span class="text-muted">Sorry, no events to recommend..</span>
                </div>
            </div>         

        </div>
                    
</template>    


<script>

import { mapGetters, mapActions } from 'vuex';
import mapstyle from '../assets/options.json';

export default {

    props: ['status', 'currentUser', 'ip'], //checks if someone loged in and gets all information about user

    computed: mapGetters(['allTypes']),
    
    data(){
        return{
            center:{lat: 0, lng: 0},
            user_location: {lat: 0, lng: 0},
            user_loc_set: false,
            format:'yyyy-MM-dd',
            recommended_events:[],
            pageOfItems: [],
            recommended_rules:{lat: 0, lng: 0, status: '', liked_sports:[], date: new Date(), user_id: ''},
            loaded_data: false
        }
    },

    created(){
        this.user_location = this.ip;
        this.fetchTypesx();
    },

    //ONLOAD PAGE DO THIS
    mounted(){
        this.geolocation(); // get location of person
    },

    //================================METHODS========================================
    methods: {

        ...mapActions(['fetchTypesx']),

        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
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

            this.$gmapApiPromiseLazy().then(() => { 
                var distanceInMeters = google.maps.geometry.spherical.computeDistanceBetween(
                    new google.maps.LatLng(this.getPosition(this.user_location)),
                    new google.maps.LatLng(this.getPosition(event_location))
                );

                var distanceInKilometers = distanceInMeters * 0.001;
                return  distanceInKilometers.toFixed(2);
            });

        },

        //sets map center, main location LITHUANIA, but if person has location ON then it shows person location
        geolocation : function() {
            if(this.getCookie("lat") != null && this.getCookie("lng") != null){

                var lat = parseFloat(this.getCookie("lat"));
                var lng = parseFloat(this.getCookie("lng"));

                this.center = {lat: lat, lng: lng};
                this.user_location = {lat: lat, lng: lng};

                this.user_loc_set = true;

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

                        this.findRecommendedEvents();
                        //console.log("trackingON");

                    }, err =>{    //if something goes wrong when locating just set map center
                        
                        this.user_location = this.ip;
                        this.center = this.ip;
                        this.user_loc_set = true;
                        this.findRecommendedEvents();
                        //console.log("somtehing wrong: " + err.message);
                    });
                    }
                    else{           //if tracking is OFF when just locate set map center
                        this.user_location = this.ip;
                        this.center = this.ip;
                        this.user_loc_set = true;
                        this.findRecommendedEvents();
                    // console.log("tracking OFF");
                    }
                }
            }
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
                    this.loaded_data = true;
                })

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

        

    }
}
</script>

