<template>
<div>

    <div class="container-fluid position-absolute" style="z-index:2;">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-sm-12 col-lg-6">
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                    <div class="collapse" id="places_sort">
                    <div class="card card-body pt-4 pl-3 pr-3 pb-0">
                        <div class="row">
                            <div class="col-6">
                                <label for="sport_type-url">Sport type</label>
                                <div class="input-group mb-3">
                                <select @change="sort_places" v-model="rules.type" class="custom-select" id="sport_type">
                                    <option selected>All</option>
                                    <option v-for="type in allTypes.data" :key="type.id" :value="type.id"> {{ type.name }}</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="distance">Distance</label>
                                <div class="input-group mb-3">
                                <select @change="sort_places" v-model="rules.distance" class="custom-select" id="distance">
                                    <option selected>Any</option>
                                    <option value="1">1 km</option>
                                    <option value="3">3 km</option>
                                    <option value="5">5 km</option>
                                    <option value="10">10 km</option>
                                    <option value="25">25 km</option>
                                </select>
                                </div>
                            </div>        
                        </div>
                    </div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-2 col-lg-1">
                        <button type="button" id="search_button" class="btn btn-dark dropdown-toggle pt-2 pb-2" style="border-radius: 0px 0px 5px 5px;" data-toggle="collapse" data-target="#places_sort" aria-expanded="false" aria-controls="places_sort" onclick="this.blur();">
                            <i class="fas fa-search"></i>
                        </button>
                </div>
            </div>

            <div class="col-2"></div>
        </div>
    </div>


   

    <notifications group="foo" classes="my-style" ignoreDuplicates position="top left" />

    <!--===================================================Add event Modal==================================================-->
            <form @submit.prevent="addEvent">
            <div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="addEventCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 v-if="edit" class="modal-title" id="addEventLongTitle">Edit Event</h5>
                            <h5 v-else class="modal-title" id="addEventLongTitle">Add Event</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Title</label>
                                <input v-model="event.title" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter title" required>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">About</label>
                                <textarea v-model="event.about" class="form-control" id="exampleFormControlTextarea1" rows="6" required></textarea>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ date }}</span>
                                </div>

                                <input type="time" id="start" v-model="start" format="HH:mm" v-on:input="parseDate(1)" required>

                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2"><i class="far fa-clock"></i></span>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ date }}</span>
                                </div>

                                <input type="time" id="end" v-model="end" format="HH:mm" v-on:input="parseDate(1)" required>

                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2"><i class="far fa-clock"></i></span>
                                </div>
                            </div>

                            <div id="time_error"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button v-if="edit" id="add_event_btn" type="submit" class="btn btn-success float-right"> Update </button>
                            <button v-else id="add_event_btn" type="submit" class="btn btn-success float-right">Add <i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            </form>

    <!-- =====================================ADD NEW PLACE MODAL======================================================== -->
            <form @submit.prevent="addPlace">
                <div class="modal fade" id="addPlace" tabindex="-1" role="dialog" aria-labelledby="addPlaceCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addPlaceLongTitle">Add new sport spot</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Title" v-model="place.title">
                                </div>

                                <div class="input-group mb-3">
                                    <textarea class="form-control" rows="6" placeholder="About..." v-model="place.about" ></textarea>
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="inputGroupSelect01">Sport</label>
                                    </div>
                    
                                    <select class="custom-select" id="inputGroupSelect01" v-model="place.type">
                                        <option v-for="type in allTypes.data" :key="type.id" :value="type.id"> {{ type.name }}</option>
                                    </select>
                                </div>

                                <div class="custom-control custom-checkbox float-left mr-4">
                                    <input v-model="place.paid" type="checkbox" name="paid" class="custom-control-input" id="paid">
                                    <label class="custom-control-label" for="paid" >Paid</label>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success float-right">Add <i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
    <!--=======================================================================================================================================-->


        <div class="row full-height justify-content-end" style="width:100%; height:100%; padding:0; margin:0;">
            <div id="map">
                <Gmap v-bind:status='status' v-bind:location='ip' v-on:showSpot="showSpot($event)" v-on:openForm="openAdd($event)" ref="gmapp"> </Gmap>  
            </div>

            <!-- ==========================================SHOW SPOT INFO========================================================= -->
    

            <div id="show" class="show col-lg-4 col-sm-12 position-fixed" style="display:none; z-index:50;">
            
            <div class="card shadow-lg mt-4" style="height:85vh;">
            <div class="card-body overflow-auto overflow-x-hidden p-2">

                <div class="d-flex flex-column bd-highlight mb-3">
                    <div class="p-4 bd-highlight">
                        <button type="button" id="close_show" v-on:click="closeShow()" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        
                        <div v-if="this.type.image">
                            <h3> <img :src="'../../../storage/sport_logo/'+ this.type.image"> {{ show.title }} </h3>
                        </div>

                        <hr>

                        <div class="card-body">
                            <p class="card-text">{{ show.about }}</p>
                            <hr class="mt-4">
                            <p class="float-left m-0" v-if="show.paid == 1">
                               <i class="fas fa-coins"></i> <small> Paid </small>
                            </p>
                            <p class="float-right"> 
                                <i class="fas fa-road"></i> 
                                <small class="mr-3">This place is {{ measured_distance }} km from you</small>
                                <a :href="'https://www.google.co.uk/maps/dir//'+show.lat+','+show.lng" target="_blank" class="badge badge-dark"><i class="fas fa-map-marker-alt"></i> Show directions</a>
                            </p>
                        </div>

                        <span v-if="currentUser">
                            <button v-if="currentUser.isAdmin == 1" v-on:click="deletePlace(show.id)" class="btn btn-danger m-2"> <i class="fas fa-times"></i> Delete place</button>
                        </span>
                    </div>
                </div>
                
                
                <div class="card m-3 width:100%; height:100%;">
                    <div class="card-header ">
                        <h6>Events</h6>  
                    </div>
                    <div class="card-body p-0">
                        <Calendar v-bind:status='status' v-on:openAddEvent="openAddEvent()" v-bind:currentUser='currentUser' v-on:editEvent="editEvent($event)" v-on:getDate="getDate($event)" v-on:closeAdd="closeAddEvent()" ref="calendar"> </Calendar>
                        <div v-if="this.status === 1">
                            <!--<button v-on:click="openAddEvent()" class="btn btn-outline-success pt-2 pb-2 pr-4 pl-4 float-right">Add Event <i class="fas fa-plus"></i></button>-->
                        </div>
                        <div v-else>
                            <div class="alert alert-warning mt-3 pb-5" role="alert">
                            If you want to join or add events you need to login / register.<br>
                            <a href ="/register" class="btn btn-outline-secondary float-right ml-2">Register</a>
                            <a href ="/login"  class="btn btn-outline-secondary float-right mr-2">Login</a>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
            </div>


        </div>



            

        </div>

   
    </div>
</template>


<script>
const Calendar = () => import("../components/Calendar.vue");
const Gmap = () => import("../components/Gmap.vue");

import { mapGetters, mapActions } from 'vuex';

export default {
        components: {
        'Gmap': Gmap,
        'Calendar': Calendar,
    },

    props: ['status','currentUser','ip'], //checks if someone loged in and gets all information about user
    

    data(){
        return{
            type: {
                id:'',
                name:''
            },
            place: {
                id:'',
                title:'',
                about:'',
                lat:'',
                lng:'',
                type:'',
                paid:'',
                personid:''
            },
            show: {         
                id:'',
                title:'',
                about:'',
                lat:'',
                lng:'',
                type:'',
                paid:''
            },
            event:{
                id:'',
                place_id:'',
                title:'',
                about:'',
                time_from:'',
                time_until:'',
                organizator:'',
                person_id:'',
            },
            rules:{
                type: 'All', distance: 'Any'
            },
            measured_distance:null,
            date:'',
            start:'2017-06-01T08:30',
            end:'2017-06-01T08:30',
            edit: false,
        }
    },

    //==============================ON LOAD FUNCTION==============================================
    mounted(){

        console.log(this.ip);

         $('#addPlace').on('hide.bs.modal', (e) => {
           this.$refs.gmapp.hidePointer();
        });

    },

    computed: mapGetters(['allPlaces', 'allTypes']),

    //===============================METHODS=======================================================
    methods: { 

    ...mapActions(['fetchPlacesx']),

    
    //Closes sidebar
    openShow: function(){
        
    },

    //Closes sidebar
    closeShow: function(){
        $("#show").slideUp("slow");
    },

    //------------------------Opens PLACE ADD label-----------------------------
    openAdd: function(cord){
        this.place.lat = cord.lat;
        this.place.lng = cord.lng;

        $('#addPlace').modal('show');  
    },

    //-------------------------Closes PLACE ADD label-------------------------------
    closeAdd: function(){
           $('#addPlace').modal('hide'); 
    },

    //------------------------Opens edit event creation label-------------------
    async editEvent(id){
        this.edit = true;

        const response = await axios.get('api/event/'+ id);
        console.log(response.data.data.title);
        var even = response.data.data;

        this.event = even;
        var d = new Date(even.time_from);
        var dat = new Date(even.time_until);

        var dateee = ('0'+d.getHours()).slice(-2) +":"+ ('0'+d.getMinutes()).slice(-2);
        this.start = dateee;
        var dateee = ('0'+dat.getHours()).slice(-2) +":"+ ('0'+dat.getMinutes()).slice(-2);
        this.end = dateee;

        $('#addEvent').modal('show');
        
    },

    //------------------------Opens add event creation label-------------------
    openAddEvent: function(){

        this.edit = false;
        this.event.place_id = this.show.id;
        this.event.person_id = this.currentUser.id;
        this.event.organizator = this.currentUser.name;

        $('#addEvent').modal('show');
    },

    //-----------------Closes event creation label---------------------------
    closeAddEvent: function(){
         $('#addEvent').modal('hide');
    },

    //---------------------Parse data in right format to choose when event starts and when ends--------------------
    parseDate(choose){
        if(this.start == this.end){
            $("#time_error").html("<span class='text-danger'><small>Times should not be equal!</small></span>");
            $("#add_event_btn").attr("disabled", true);
        }
        else if(this.start > this.end) {
            $("#time_error").html("<span class='text-danger'><small>Second time should be later!</small></span>");
            $("#add_event_btn").attr("disabled", true);
        }
        else{
            $("#time_error").html("");
            $('#add_event_btn').removeAttr("disabled");
        }
    },

    //---------------------Gets date in day chooser--------------------------------
    getDate: function(dateee){
        var d = new Date(dateee);
        var dat = new Date(dateee);
        this.date =  d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
        d.setMinutes(30);
        dat.setMinutes(30);
        dat.setHours(d.getHours() + 1);

        dateee = d.getHours() +":"+ d.getMinutes();
        this.start = dateee;
        dateee = dat.getHours() +":"+ dat.getMinutes();
        this.end = dateee;
    },

    //-----------------Show spot all info with all events happening in there--------------------
    showSpot: function(arg){

        const foundPlace = this.allPlaces.data.find( place => place.id == arg[0]);
        this.show = foundPlace;

        this.$refs.calendar.fetchSpot(this.show.id);

        const foundType = this.allTypes.data.find( type => type.id == this.show.type);
        this.type = foundType;

        const show = document.querySelector('#show');

        this.measured_distance = arg[1];

        this.openShow();

        if (show.style.display === 'none') {
            $("#show").slideDown("slow");
        }

        this.closeAddEvent();
    },

    //---------------------------Get Cookie-------------------------------------
    getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
    },


    //---------------------------------------Deletes place-------------------------------------------------
    deletePlace: function(id) {

        console.log(this.getCookie("api_token"));

        if(confirm('Are you sure?')){
            fetch('api/place/'+ id + '?api_token=' + this.getCookie("api_token"), {
                method: 'delete'
            })
                .then(res => res.json())
                .then(data => {
                    this.$refs.gmapp.fetchPlaces();
                })
                .catch(err => console.log(err));
                this.$refs.gmapp.fetchPlaces();
                this.show.title = '';
                this.show.about = '';
                this.show.lat = '';
                this.show.lng = '';
                this.show.type = '';
                this.type.name = '';

                Vue.notify({
                group: 'foo',
                title: 'Notification',
                type: 'error',
                text: 'The place has been deleted!'
                });

                $("#show").hide();
                this.closeShow(); 
        }
    },

    //------------------------------------------Adds new place--------------------------------------------
    addPlace() {
        this.place.personid = this.$props.currentUser.id;

            fetch('api/placequeue?api_token=' + this.getCookie("api_token"), {
                method: 'post',
                body: JSON.stringify(this.place),
                headers: {
                    'content-type': 'application/json'
                }
            })
            .then(res => res.json())
            .then( data=> {
                this.place.title = '';
                this.place.about = '';
                this.place.lat = '';
                this.place.lng = '';
                this.place.type = '';
                this.place.paid = '';
                this.$refs.gmapp.fetchPlaces();
            })
            .catch(err =>console.log(err));

            Vue.notify({
                group: 'foo',
                title: 'Congrats!!',
                type: 'success',
                duration: 10000,
                text: 'The new place has been sent for inspection and if everything is okay will be uploaded'
                });

                this.$refs.gmapp.fetchPlaces();
                this.$refs.gmapp.hidePointer();
                this.closeAdd();
                this.closeShow();

    },

    //-----------------------------------------Create new event-----------------------------------------------
     addEvent() {
        if(this.edit === false){

            this.event.time_from = this.date + " " + this.start;
            this.event.time_until = this.date + " " + this.end;

            (async () => {
            const Response = await fetch('api/event?api_token=' + this.getCookie("api_token"), {
                method: 'post',
                    body: JSON.stringify(this.event),
                    headers: {
                        'Accept': 'application/json',
                        'content-type': 'application/json'
                    }     
            });

            const content = await Response.json();
            await this.$refs.calendar.fetchEvents();
            await this.$refs.calendar.fetchSpot(this.show.id);
            })();


            Vue.notify({
                    group: 'foo',
                    title: 'Congrats!!',
                    type: 'success',
                    duration: 10000,
                    text: 'You have created an event !'
                    });
   
        } else{

            this.event.time_from = this.date + " " + this.start;
            this.event.time_until = this.date + " " + this.end;

            (async () => {
        const Response = await fetch('api/event?api_token=' + this.getCookie("api_token"), {
             method: 'put',
                body: JSON.stringify(this.event),
                headers: {
                    'Accept': 'application/json',
                    'content-type': 'application/json'
                }     
        });

        const content = await Response.json();
        await this.$refs.calendar.fetchEvents();
        await this.$refs.calendar.fetchSpot(this.show.id);
        })();

        this.edit = false;


        Vue.notify({
                group: 'foo',
                title: 'Congrats!!',
                type: 'success',
                text: 'You have updated an event !'
                });  
        }

        this.event.place_id = '';
        this.event.title = '';
        this.event.about = '';
        this.event.time_from = '';
        this.event.time_until = '';
        this.event.person_id = '';


    },

    sort_places() {
        
        this.$refs.gmapp.fetchPlaces_sort(this.rules);
        
    },

    }
}
</script>


<style>

/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: rgb(182, 182, 182); 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: rgb(136, 136, 136); 
}

#time_error{
    margin-top: -15px;
    margin-left: 110px;
}

#map {
    width: 100% !important; 
    height:100% !important;
}

.my-style {
    padding: 15px;
    margin-top: 65px;
    margin-left: 10px;
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


#sidebar{
    height: 94vh;
    overflow-y: auto;
}

#geras{
    position: absolute;
    color:white;
    font-size: 25px;
    opacity: 0.1;

}

#search_button{
    z-index: 50;
}

#places_sort{
    z-index: 51;
    width:100%;
}

#loading-screen {
    z-index: 100;
    position: absolute; top: 0; right: 0; bottom: 0; left: 0;
    background-color: #82cc75;
}

@media only screen and (max-width: 900px) {

#map {
    width: 100% !important; 
    height:100% !important;
}
#show{
    width: 100% !important; 
}
#createDiv{
    width: 95% !important;   
}
#sidebar{
    height: auto;
    overflow-y: hidden;
}

}
</style>
