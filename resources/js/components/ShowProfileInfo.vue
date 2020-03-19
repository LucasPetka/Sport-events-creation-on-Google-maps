<template>

<div>

    <notifications group="foo" classes="my-style" position="top left" style="margin-top:55px;" />

<!------------------------------------------Re-submit MODAL-------------------------------------->
    <form @submit.prevent="reSubmit">
    <div class="modal fade" id="editPlace" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Re-submit declined sport place</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                
                    <div class="form-group">
                        <input v-model="place.title" type="text" class="form-control" maxlength="45" placeholder="Title" name="title">
                    </div>
                    <div class="form-group">
                        <textarea v-model="place.about" rows="6" class="form-control" maxlength="350" placeholder="Title" name="about"> </textarea>       
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <div class="custom-control custom-checkbox float-left mr-4">
                            <input v-model="place.paid"  type="checkbox" name="paid" class="custom-control-input" id="paid">
                            <label class="custom-control-label" for="paid" >Paid</label>
                        </div>
                    </div>

                    <gmap-map class="rounded" ref="gmapp" :center="getPosition(center)" :zoom="14" v-bind:options="mapStyle" style=" overflow:hidden; width:100%; height:300px;">
                        <gmap-marker @drag="updateCoordinates" :draggable="true" :position="getPosition(place_coordinates)"></gmap-marker>
                    </gmap-map>
                
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success"> Re-submit <i class="fas fa-plus"></i> </button>
            </div>
        </div>
        </div>
    </div>
    </form>




    <div class="row mb-3">
        <div class="col-12">
            <button type="button" class="btn btn-success m-1 float-right" data-toggle="collapse" data-target="#createdEvents" aria-expanded="false" aria-controls="createdEvents">
                <i class="far fa-calendar-alt"></i> Events
            </button>
            <button type="button" class="btn btn-success m-1 float-right" data-toggle="collapse" data-target="#createdPlaces" aria-expanded="false" aria-controls="createdPlaces">
                <i class="fas fa-map-marked-alt"></i>  Places  
            </button>
            <button type="button" class="btn btn-primary m-1 float-right"  data-toggle="collapse" data-target="#goingto" aria-expanded="false" aria-controls="goingto">
                <i class="fas fa-calendar-check"></i> Participating <span class="badge badge-light">{{ goingToEvents.length }}</span>
            </button>
        </div>
    </div>

<div class="accordion" id="accordionExample">


        <!-- ======================================================PLACES==============================================================-->

        <div class="collapse" id="createdPlaces" data-parent="#accordionExample">
            <p class="h4 ml-5 mb-0 pb-0 text-right">Places</p>

            <nav class="mt-2">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active mr-1" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"><i class="far fa-paper-plane"></i> Submited <span class="badge badge-pill badge-dark active">{{ submitedPlaces.length }}</span></a>
                    <a class="nav-item nav-link mr-1" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false"><i class="far fa-check-circle"></i> Accepted <span class="badge badge-pill badge-dark">{{ acceptedPlaces.length }}</span></a>
                    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false"><i class="far fa-times-circle"></i> Declined <span class="badge badge-pill badge-dark">{{ declinedPlaces.length }}</span></a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div v-if="submitedPlaces.length != 0">
                            <div v-for="place in submited_places_pageOfItems" :key="place.id" class="card mt-2 mb-3">
                                <div class="card-body">
                                    {{ place.title }}
                                    <div class="float-right"> 
                                        <img :src="'../storage/sport_logo/' + place.image"> 
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-8">
                                            {{ place.about.slice(0, 140) }}...
                                        </div>
                                        <div class="col-4">
                                            <button type="button" class="btn btn-outline-success btn-lg float-right" data-toggle="modal" :data-target="'#submited_map' + place.id ">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </button>
                                        </div>
                                        <div class="modal fade" :id="'submited_map' + place.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">{{ place.title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body p-0">
                                                <smallmap v-bind:place='place' v-bind:size='"width:auto; height: 450px;"'> </smallmap>
                                            </div>  
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-around">
                                <jw-pagination :pageSize="3"  :items="submitedPlaces" @changePage="submited_places_onChangePage"></jw-pagination>
                            </div>
                        </div>
                        <p v-else class="text-center mt-4 text-muted"> No submited places.. </p> 
                    
                </div>

                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div v-if="acceptedPlaces.length != 0">
                        <div v-for="place in accepted_places_pageOfItems" :key="place.id" class="card mt-2 mb-3">
                                <div class="card-body">
                                    {{ place.title }}
                                    <div class="float-right"> 
                                        <img :src="'../storage/sport_logo/' + place.image"> 
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-8">
                                            {{ place.about.slice(0, 140) }}...
                                        </div>
                                        <div class="col-4">
                                            <button type="button" class="btn btn-outline-success btn-lg float-right" data-toggle="modal" :data-target="'#accepted_map' + place.id ">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </button>
                                        </div>
                                        <div class="modal fade" :id="'accepted_map' + place.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">{{ place.title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body p-0">
                                                <smallmap v-bind:place='place' v-bind:size='"width:auto; height: 450px;"'> </smallmap>
                                            </div>  
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-around">
                                <jw-pagination :pageSize="3"  :items="acceptedPlaces" @changePage="accepted_places_onChangePage"></jw-pagination>
                            </div>
                        </div>
                        <p v-else class="text-center mt-4 text-muted"> No accepted places.. </p> 
                    
                </div>

                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div v-if="declinedPlaces.length != 0">
                        <div v-for="place in declined_places_pageOfItems" :key="place.id" class="card mt-2 mb-3">
                            <div class="card-body">
                                {{ place.title }}
                                <div class="float-right"> 
                                    <img :src="'../storage/sport_logo/' + place.image"> 
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-8">
                                        {{ place.about.slice(0, 140) }}...
                                    </div>


                                    <div class="col-4">
                                        <button type="button" v-on:click="deletePlace(place.id)" class="btn btn-outline-danger float-right m-1" >
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button type="button" v-on:click="openResubmit(place)" class="btn btn-outline-primary float-right m-1">
                                            <i class="far fa-edit"></i>
                                        </button>
                                    </div>

                                    
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-around">
                            <jw-pagination :pageSize="3"  :items="declinedPlaces" @changePage="declined_places_onChangePage"></jw-pagination>
                        </div>
                    </div> 
                    <p v-else class="text-center mt-4 text-muted"> No declined places.. </p> 
                    

                </div>
            </div>
                
        </div>



        <!-- ======================================================GOING TO EVENTS==============================================================-->
        <div class="collapse show" id="goingto" data-parent="#accordionExample">
        <p class="h4 ml-5 mb-3 mb-0 pb-0">Going to</p>

        <div v-if="goingToEvents != 0">
            <div v-for="event in goingto_events_pageOfItems" :key="event.id" class="card mb-3 shadow-sm">
                    <div class="card-header">
                        <a target="_blank" :href="'/event/' + event.id" class="nav-link m-0 p-0">
                            <img :src="'../storage/sport_logo/' + event.image ">  {{ event.title }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-8">
                                {{ event.about.slice(0, 140) }}...
                            </div>

                            <div class="col-lg-3">
                                <hr class="mt-0">
                                    <div class="mb-1"><i class="far fa-calendar-alt"></i> {{ getDate(event.time_from) }}</div>
                                    <div class="mb-1">{{ getWeekDay(event.time_from) }}</div>
                                    <div><i class="far fa-clock"></i> {{ getTime(event.time_from) }} - {{ getTime(event.time_until) }}</div>                        
                                <hr class="mb-0">
                            </div>

                            <div class="col-lg-1 pl-1"> 
                                
                                <button type="button" class="btn btn-outline-success float-right" data-toggle="modal" :data-target="'#created_map' + event.id">
                                    <i class="fas fa-map-marked-alt"></i>
                                </button>
                            </div>

                            <!------------------------------------------MAP MODAL-------------------------------------->
                            <div class="modal fade" :id="'created_map'+ event.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle"><img :src="'../storage/sport_logo/' + event.image">  {{ event.title }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <smallmap v-bind:place='event' v-bind:size='"width:100%; height:450px;"'> </smallmap>
                                    </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-around">
                    <jw-pagination :pageSize="3"  :items="goingToEvents" @changePage="goingto_events_onChangePage"></jw-pagination>
                </div>
            </div>
        <p v-else class="text-center mt-4 text-muted"> No events created.. </p>   
        </div>


        <!-- ======================================================CREATED EVENTS==============================================================-->
        <div class="collapse" id="createdEvents" data-parent="#accordionExample">
            <p class="h4 ml-5 mb-0 pb-0 text-right">Events</p>

            <nav class="mt-2 mb-2">
                <div class="nav nav-tabs" id="nav-tab2" role="tablist">
                    <a class="nav-item nav-link active mr-1" id="nav-submited-tab" data-toggle="tab" href="#nav-submited" role="tab" aria-controls="nav-submited" aria-selected="true"><i class="far fa-paper-plane"></i> Submited <span class="badge badge-pill badge-dark">{{ submitedEvents.length }}</span></a>
                    <a class="nav-item nav-link mr-1" id="nav-accepted-tab" data-toggle="tab" href="#nav-accepted" role="tab" aria-controls="nav-accepted" aria-selected="false"><i class="far fa-check-circle"></i> Accepted <span class="badge badge-pill badge-dark">{{ createdEvents.length }}</span></a>
                    <a class="nav-item nav-link" id="nav-declined-tab" data-toggle="tab" href="#nav-declined" role="tab" aria-controls="nav-declined" aria-selected="false"><i class="far fa-times-circle"></i> Declined <span class="badge badge-pill badge-dark">{{ declinedEvents.length }}</span></a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent2">

                <!--===============================SUBMITED========================================-->
                <div class="tab-pane fade show active" id="nav-submited" role="tabpanel" aria-labelledby="nav-submited-tab">
                    <div v-if="submitedEvents != 0">
                        <div v-for="event in submited_events_pageOfItems" :key="event.id" class="card mb-3">
                                <div class="card-header">
                                        <img :src="'../storage/sport_logo/' + event.image ">  {{ event.title }}
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-lg-8">
                                            {{ event.about.slice(0, 140) }}...
                                        </div>

                                        <div class="col-lg-3">
                                            <hr class="mt-0">
                                                <div class="mb-1"><i class="far fa-calendar-alt"></i> {{ getDate(event.time_from) }}</div>
                                                <div class="mb-1">{{ getWeekDay(event.time_from) }}</div>
                                                <div><i class="far fa-clock"></i> {{ getTime(event.time_from) }} - {{ getTime(event.time_until) }}</div>                        
                                            <hr class="mb-0">
                                        </div>

                                        <div class="col-lg-1 pl-1"> 
                                            <button type="button" class="btn btn-outline-success float-right" data-toggle="modal" :data-target="'#created_mappp' + event.id">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </button>
                                        </div>

                                        <!------------------------------------------MAP MODAL-------------------------------------->
                                        <div class="modal fade" :id="'created_mappp'+ event.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle"><img :src="'../storage/sport_logo/' + event.image">  {{ event.title }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <smallmap v-bind:place='event' v-bind:size='"width:100%; height:450px;"'> </smallmap>
                                                </div>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-around">
                                <jw-pagination :pageSize="3"  :items="submitedEvents" @changePage="submited_events_onChangePage"></jw-pagination>
                            </div>
                        </div>
                    <p v-else class="text-center mt-4 text-muted"> No events created.. </p>  
                </div> 

                <!--===============================ACCEPTED========================================-->
                <div class="tab-pane fade" id="nav-accepted" role="tabpanel" aria-labelledby="nav-accepted-tab">
                    <div v-if="createdEvents != 0">
                        <div v-for="event in created_events_pageOfItems" :key="event.id" class="card mb-3">
                                <div class="card-header">
                                    <a target="_blank" :href="'/event/' + event.id" class="nav-link m-0 p-0 float-left">
                                        <img :src="'../storage/sport_logo/' + event.image ">  {{ event.title }}
                                    </a> 
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-lg-7">
                                            {{ event.about.slice(0, 140) }}...
                                            <h6 v-if="event.people_going > 1 || event.people_going == 0" class="text-left mt-3"> <i class="fas fa-user"></i> {{ event.people_going }} people going</h6>
                                            <h6 v-else class="text-left mt-3"> <i class="fas fa-user"></i> {{ event.people_going }} person going</h6>

                                        </div>

                                        <div class="col-lg-3">
                                            <hr class="mt-0 mb-1">
                                                <div class="mb-1"><i class="far fa-calendar-alt"></i> {{ getDate(event.time_from) }}</div>
                                                <div class="mb-1">{{ getWeekDay(event.time_from) }}</div>
                                                <div><i class="far fa-clock"></i> {{ getTime(event.time_from) }} - {{ getTime(event.time_until) }}</div>                        
                                            <hr class="mb-0 mt-1">
                                        </div>

                                        <div class="col-lg-2 pl-1 mt-2"> 
                                            <editevent :user="user" v-on:fetch="fetchCreatedEvents()" :acceptedOrDeclined="false" :event="event"></editevent>
                                            <button type="button" class="btn btn-outline-success float-right" data-toggle="modal" :data-target="'#created_mappp' + event.id">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </button>
                                        </div>

                                        <!------------------------------------------MAP MODAL-------------------------------------->
                                        <div class="modal fade" :id="'created_mappp'+ event.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle"><img :src="'../storage/sport_logo/' + event.image">  {{ event.title }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <smallmap v-bind:place='event' v-bind:size='"width:100%; height:450px;"'> </smallmap>
                                                </div>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-around mb-5">
                                <jw-pagination :pageSize="2" :maxPages="3"  :items="createdEvents" @changePage="created_events_onChangePage"></jw-pagination>
                            </div>
                        </div>
                    <p v-else class="text-center mt-4 text-muted"> No events created.. </p>  
                </div> 

                <!--===============================DECLINED========================================-->
                <div class="tab-pane fade" id="nav-declined" role="tabpanel" aria-labelledby="nav-declined-tab">
                    <div v-if="declinedEvents != 0">
                        <div v-for="event in declined_events_pageOfItems" :key="event.id" class="card mb-3">
                                <div class="card-header">
                                    <img :src="'../storage/sport_logo/' + event.image ">  {{ event.title }}     
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-lg-7">
                                            {{ event.about.slice(0, 140) }}...
                                        </div>

                                        <div class="col-lg-3">
                                            <hr class="mt-0">
                                                <div class="mb-1"><i class="far fa-calendar-alt"></i> {{ getDate(event.time_from) }}</div>
                                                <div class="mb-1">{{ getWeekDay(event.time_from) }}</div>
                                                <div><i class="far fa-clock"></i> {{ getTime(event.time_from) }} - {{ getTime(event.time_until) }}</div>                        
                                            <hr class="mb-0">
                                        </div>

                                        <div class="col-lg-2 pl-1"> 
                                            <editevent :user="user" v-on:fetch="fetchDeclinedEvents(), fetchSubmitedEvents()" :acceptedOrDeclined="true" :event="event"></editevent>
                                            <button type="button" class="btn btn-outline-success float-right" data-toggle="modal" :data-target="'#created_mappp' + event.id">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </button>
                                        </div>

                                        <!------------------------------------------MAP MODAL-------------------------------------->
                                        <div class="modal fade" :id="'created_mappp'+ event.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle"><img :src="'../storage/sport_logo/' + event.image">  {{ event.title }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <smallmap v-bind:place='event' v-bind:size='"width:100%; height:450px;"'> </smallmap>
                                                </div>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="row justify-content-around mb-5">
                            <jw-pagination :pageSize="3"  :items="declinedEvents" @changePage="declined_events_onChangePage"></jw-pagination>
                        </div>
                        </div>
                    <p v-else class="text-center mt-4 text-muted"> No events created.. </p>  
                </div>


            </div>
        
        
        
        </div>

    
</div>




</div>
    
</template>

<script>
const editevent = () => import("../components/EditEvent.vue");
const smallmap = () => import("../components/SmallMap.vue");
import mapstyle from '../assets/options.json';

export default {
    components: {
        'editevent': editevent,
        'smallmap': smallmap,
    },

    props:['user'],

    data(){
        return{
            weekDays:['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday',],
            createdEvents: [],
            goingToEvents: [],
            acceptedPlaces: [],
            submitedPlaces: [],
            declinedPlaces: [],
            submitedEvents: [],
            declinedEvents: [],
            center: { lat: 0.0, lng: 0.0 },
            place: {
                id:'',
                title:'',
                about:'',
                lat: 0.0,
                lng: 0.0,
                type:'',
                paid:'',
                personid:''
            },
            place_coordinates: { lat: 0.0, lng: 0.0 },
            mapStyle: {
            styles: mapstyle,
            options:{
                minZoom: 10,
                gestureHandling: 'greedy',
                fullscreenControl: false,
                mapTypeControl: true,
                scaleControl: false,
                streetViewControl: false,
                zoomControl: false
            }
            },
            submited_events_pageOfItems: [],
            declined_events_pageOfItems: [],
            created_events_pageOfItems: [],
            goingto_events_pageOfItems: [],
            accepted_places_pageOfItems: [],
            declined_places_pageOfItems: [],
            submited_places_pageOfItems: [],
        }
    },


    created(){
        this.fetchSubmitedEvents();
        this.fetchDeclinedEvents();
        this.fetchCreatedEvents();
        this.fetchGoingToEvents();
        this.fetchAcceptedPlaces();
        this.fetchDeclinedPlaces();
        this.fetchSubmitedPlaces();

    },


    methods: {

        updateCoordinates(location) {
            this.place_coordinates = {
                lat: location.latLng.lat(),
                lng: location.latLng.lng(),
            };
        },

        openResubmit(place){
            this.place = place;
            if(place.paid == 0){
                this.place.paid = false;
            }else{
                this.place.paid = true;
            }
            this.center = { lat: place.lat, lng: place.lng };
            this.place_coordinates = { lat: place.lat, lng: place.lng };

            $('#editPlace').modal('show');  
        },

        reSubmit(){
            this.place.lat = this.place_coordinates.lat;
            this.place.lng = this.place_coordinates.lng;

            (async () => {
            const Response = await fetch('/resubmit/'+this.place.id, {
                method: 'post',
                    body: JSON.stringify(this.place),
                    headers: {
                        'Accept': 'application/json',
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }     
            });

                const content = await Response.json();

                if(content == true){
                Vue.notify({
                    group: 'foo',
                    title: 'Congrats!!',
                    type: 'success',
                    text: 'Place have been submited again !'
                });  
                }
                else{
                    Vue.notify({
                        group: 'foo',
                        title: 'Error!!',
                        type: 'error',
                        text: 'Incorrect form'
                    });  
                }

                await this.fetchDeclinedPlaces();
                await this.fetchSubmitedPlaces();
            })();

            $('#editPlace').modal('hide');
        },

        deletePlace(place_id){

            (async () => {
            const Response = await fetch('/decplace/'+place_id, {
                method: 'delete',
                    headers: {
                        'Accept': 'application/json',
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }     
            });

                const content = await Response.json();

                if(content == true){
                Vue.notify({
                    group: 'foo',
                    title: 'Congrats!!',
                    type: 'success',
                    text: 'Place have been deleted !'
                });  
                }
                else{
                    Vue.notify({
                        group: 'foo',
                        title: 'Error!!',
                        type: 'error',
                        text: 'Unable to delete place..'
                    });  
                }


                await this.fetchDeclinedPlaces();
            })();


        },

        //Gets position of the marker
        getPosition: function(place) {
            return {
                lat: parseFloat(place.lat),
                lng: parseFloat(place.lng)
            }
        },

        getDate(date){
            let current_datetime = new Date(date)
            let formatted_date = current_datetime.getFullYear() + "-" + ('0' + (current_datetime.getMonth()+1)).slice(-2) + "-" + ('0' + current_datetime.getDate()).slice(-2)
            return formatted_date;
        },

        getWeekDay(date){
            let current_datetime = new Date(date);

            return this.weekDays[current_datetime.getDay()];
        },

        getTime(date){
            let current_datetime = new Date(date)
            let formatted_Time = ('0' + current_datetime.getHours()).slice(-2) + ":" + ('0' + current_datetime.getMinutes()).slice(-2)
            return formatted_Time;
        },


        fetchCreatedEvents(){
            axios.get('../returncreatedevents').then(response =>{
                this.createdEvents = response.data;
            })
        },

        fetchGoingToEvents(){
            axios.get('../returngoingtoevents').then(response =>{
                this.goingToEvents = response.data;
            })
        },

        fetchAcceptedPlaces(){
            axios.get('../returnacceptedplaces').then(response =>{
                this.acceptedPlaces = response.data;
            })
        },

        fetchDeclinedPlaces(){
            axios.get('../returndeclinedplaces').then(response =>{
                this.declinedPlaces = response.data;
            })
        },

        fetchSubmitedPlaces(){
            axios.get('../returnsubmitedplaces').then(response =>{
                this.submitedPlaces = response.data;
            })
        },

        fetchDeclinedEvents(){
            axios.get('../returndeclinedevents').then(response =>{
                this.declinedEvents = response.data;
            })
        },

        fetchSubmitedEvents(){
            axios.get('../returnsubmitedevents').then(response =>{
                this.submitedEvents = response.data;
            })
        },



        //=================PAGINATION=======================

        submited_events_onChangePage(pageOfItems) {
            this.submited_events_pageOfItems = pageOfItems;
        },
        declined_events_onChangePage(pageOfItems) {
            this.declined_events_pageOfItems = pageOfItems;
        },
        created_events_onChangePage(pageOfItems) {
            this.created_events_pageOfItems = pageOfItems;
        },
        goingto_events_onChangePage(pageOfItems) {
            this.goingto_events_pageOfItems = pageOfItems;
        },
        accepted_places_onChangePage(pageOfItems) {
            this.accepted_places_pageOfItems = pageOfItems;
        },
        declined_places_onChangePage(pageOfItems) {
            this.declined_places_pageOfItems = pageOfItems;
        },
        submited_places_onChangePage(pageOfItems) {
            this.submited_places_pageOfItems = pageOfItems;
        },




    }



}
</script>

<style scoped>

.nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
    color: #ffffff;
    background-color: #3490dc;
    border-color: #dee2e6 #dee2e6 #cccccc;
}

.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
    background: #F8FAFC;
}

.nav-tabs {
    border-bottom: 0px solid #f8fafc;
}

.my-style {
    padding: 15px;
    margin-left: 10px;
    margin-top: 10px;
    width: 290px;
 
    font-size: 14px;
    border-radius: 5px;
    border-left: solid rgb(99, 156, 88) 5px;


    color: #ffffff;
    background: #82CC75;
}

.success {
    background: #82CC75;
}

.error {
    background: #DC4146;
    border-left: solid rgb(177, 52, 56) 5px;
}

</style>