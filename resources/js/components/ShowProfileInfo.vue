<template>

<div>

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
                        <input v-model="place.title" type="text" class="form-control" placeholder="Title" name="title">
                    </div>
                    <div class="form-group">
                        <textarea v-model="place.about" rows="6" class="form-control" placeholder="Title" name="about"> </textarea>       
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
            <button type="button" class="btn btn-primary m-1 float-right" data-toggle="collapse" data-target="#createdEvents" aria-expanded="false" aria-controls="createdEvents">
                <i class="far fa-calendar-alt"></i>  Your created events <span class="badge badge-light">{{ createdEvents.length }}</span>
            </button>
            <button type="button" class="btn btn-primary m-1 float-right"  data-toggle="collapse" data-target="#goingto" aria-expanded="false" aria-controls="goingto">
                <i class="fas fa-calendar-check"></i> Events you have joined <span class="badge badge-light">{{ goingToEvents.length }}</span>
            </button>
            <button type="button" class="btn btn-success m-1 float-right" data-toggle="collapse" data-target="#createdPlaces" aria-expanded="false" aria-controls="createdPlaces">
                <i class="fas fa-map-marked-alt"></i>  Places  
            </button>
        </div>
    </div>

<div class="accordion" id="accordionExample">


        <!-- ======================================================PLACES==============================================================-->

        <div class="collapse" id="createdPlaces" data-parent="#accordionExample">
            <p class="h4 text-center">Places</p>

            <nav class="mt-2">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Submited places</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Accepted places</a>
                    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Declined places</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div v-if="submitedPlaces.length != 0">
                            <div v-for="place in submitedPlaces" :key="place.id" class="card mt-2 mb-3">
                                <div class="card-body">
                                    {{ place.title }}
                                    <div class="float-right"> 
                                        <img :src="'../storage/sport_logo/' + place.image"> 
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-8">
                                            {{ place.about }}
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
                        </div>
                        <p v-else class="text-center mt-4 text-muted"> No submited places.. </p> 
                    
                </div>

                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div v-if="acceptedPlaces.length != 0">
                        <div v-for="place in acceptedPlaces" :key="place.id" class="card mt-2 mb-3">
                                <div class="card-body">
                                    {{ place.title }}
                                    <div class="float-right"> 
                                        <img :src="'../storage/sport_logo/' + place.image"> 
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-8">
                                            {{ place.about }}
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
                        </div>
                        <p v-else class="text-center mt-4 text-muted"> No accepted places.. </p> 
                    
                </div>

                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div v-if="declinedPlaces.length != 0">
                        <div v-for="place in declinedPlaces" :key="place.id" class="card mt-2 mb-3">
                            <div class="card-body">
                                {{ place.title }}
                                <div class="float-right"> 
                                    <img :src="'../storage/sport_logo/' + place.image"> 
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-8">
                                        {{ place.about }}
                                    </div>


                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-danger btn-lg float-right m-1" >
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button type="button" v-on:click="openResubmit(place)" class="btn btn-outline-primary btn-lg float-right m-1">
                                            <i class="far fa-edit"></i>
                                        </button>
                                    </div>

                                    
                                </div>
                            </div>
                        </div>
                    </div> 
                    <p v-else class="text-center mt-4 text-muted"> No declined places.. </p> 
                    

                </div>
            </div>
                
        </div>



        <!-- ======================================================GOING TO EVENTS==============================================================-->
        <div class="collapse" id="goingto" data-parent="#accordionExample">
        <p class="h4 text-center">Going to</p>
        <hr>
        <div v-if="goingToEvents != 0">
            <div v-for="event in goingToEvents" :key="event.id" class="card mb-3">
                    <div class="card-header">
                        <a target="_blank" :href="'/event/' + event.id" class="nav-link m-0 p-0">
                            <img :src="'../storage/sport_logo/' + event.image ">  {{ event.title }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-8">
                                {{ event.about }}
                            </div>

                            <div class="col-lg-3">
                                <hr class="mt-0">
                                    <div class="mb-1"><i class="far fa-calendar-alt"></i> {{ getDate(event.time_from) }}</div>
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
            </div>
        <p v-else class="text-center mt-4 text-muted"> No events created.. </p>   
        </div>


        <!-- ======================================================CREATED EVENTS==============================================================-->
        <div class="collapse show" id="createdEvents" data-parent="#accordionExample">
        <p class="h4 text-center">Created</p>
        <hr>
        <div v-if="createdEvents != 0">
            <div v-for="event in pageOfItems" :key="event.id" class="card mb-3">
                    <div class="card-header">
                        <a target="_blank" :href="'/event/' + event.id" class="nav-link m-0 p-0">
                            <img :src="'../storage/sport_logo/' + event.image ">  {{ event.title }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-8">
                                {{ event.about }}
                            </div>

                            <div class="col-lg-3">
                                <hr class="mt-0">
                                    <div class="mb-1"><i class="far fa-calendar-alt"></i> {{ getDate(event.time_from) }}</div>
                                    <div><i class="far fa-clock"></i> {{ getTime(event.time_from) }} - {{ getTime(event.time_until) }}</div>                        
                                <hr class="mb-0">
                            </div>

                            <div class="col-lg-1 pl-1"> 
                                <editevent :user="user" v-on:fetchCreatedEvents="fetchCreatedEvents()" :event="event"></editevent>
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
                    <jw-pagination :pageSize="3"  :items="createdEvents" @changePage="onChangePage"></jw-pagination>
                </div>
            </div>
        <p v-else class="text-center mt-4 text-muted"> No events created.. </p>   
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
            createdEvents: [],
            goingToEvents: [],
            acceptedPlaces: [],
            submitedPlaces: [],
            declinedPlaces: [],
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
            pageOfItems: []
        }
    },


    created(){
        this.fetchCreatedEvents();
        this.fetchGoingToEvents();
        this.fetchAcceptedPlaces();
        this.fetchDeclinedPlaces();
        this.fetchSubmitedPlaces();

    },


    methods: {

        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },

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
                await this.fetchDeclinedPlaces();
                await this.fetchSubmitedPlaces();
            })();

            $('#editPlace').modal('hide');
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




    }



}
</script>