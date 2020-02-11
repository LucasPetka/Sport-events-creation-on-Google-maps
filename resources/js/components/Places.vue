<template>
<div>
    <div id="loading-screen">
        <div id="geras">MoSi</div>
    </div>


    <div class="container-fluid position-fixed mt-5" style="z-index:2;">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-6">
                <div class="row">
                    <div class="col-1">
                        <button type="button" id="search_button" class="btn btn-dark dropdown-toggle pt-2 pb-2" style="border-radius: 0px 0px 5px 5px;" data-toggle="collapse" data-target="#places_sort" aria-expanded="false" aria-controls="places_sort">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <div class="col-6">
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
            </div>

            <div class="col-2"></div>
        </div>

    </div>


   

    <notifications group="foo" classes="my-style" ignoreDuplicates position="top left" />



    <!-- =====================================ADD NEW PLACE======================================================== -->
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


        <div class="row" style="width:100%; padding:0; margin:0;">
            <div id="map" class="col-lg-12 p-0">
                <Gmap v-bind:status='status' v-on:showSpot="showSpot($event)" v-on:openForm="openAdd($event)" ref="gmapp"> </Gmap>  
            </div>

            <div id="side" class="col-lg-0 p-0 mt-5" style="display:none;">
                <div id="sidebar">

                <!-- ==========================================SHOW SPOT INFO========================================================= -->
               
                    <div id="show" class="show" style="display:none; width:100%; height:auto; padding-bottom:270px;" >
                       
                            
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
                                    <p class="float-right"> 
                                        <i class="fas fa-road"></i> 
                                        <small>This place is {{ measured_distance }} km from you</small>
                                        <a :href="'https://www.google.co.uk/maps/dir//'+show.lat+','+show.lng" target="_blank" class="badge badge-dark ml-3"><i class="fas fa-map-marker-alt"></i> Show directions</a>
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
                            <div class="card-body">
                                <Calendar v-bind:status='status' v-on:openAddEvent="openAddEvent()" v-bind:currentUser='currentUser' v-on:editEvent="editEvent($event)" v-on:getDate="getDate($event)" v-on:closeAdd="closeAddEvent()" ref="calendar"> </Calendar>
                                <div v-if="this.status === 1">
                                    <!--<button v-on:click="openAddEvent()" class="btn btn-outline-success pt-2 pb-2 pr-4 pl-4 float-right">Add Event <i class="fas fa-plus"></i></button>-->
                                </div>
                                <div v-else>
                                    <div class="alert alert-warning pb-5" role="alert">
                                    If you want to join or add events you need to login / register.<br>
                                    <a href ="/register" class="btn btn-outline-secondary float-right ml-2">Register</a>
                                    <a href ="/login"  class="btn btn-outline-secondary float-right mr-2">Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>



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

                                            <datetime type="time" id="start" v-model="start" format="HH:mm" :value-zone="'local'" :minute-step="10" v-on:input="parseDate(0)" required></datetime>
                                        
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2"><i class="far fa-clock"></i></span>
                                            </div>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ date }}</span>
                                            </div>

                                            <datetime  type="time" id="end" v-model="end" format="HH:mm" :value-zone="'local'" :minute-step="10" v-on:input="parseDate(1)" required></datetime>
                                            
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

                    </div>
                </div>
            </div>
        </div>

   
    </div>
</template>


<script>
import Gmap from '../components/Gmap.vue';
import Calendar from '../components/Calendar.vue';
import { Datetime } from 'vue-datetime';
import Vue from 'vue';
import { mapGetters, mapActions } from 'vuex';

export default {
        components: {
        'Gmap': Gmap,
        'Calendar': Calendar,
        'datetime': Datetime,
    },

    props: ['status','currentUser'], //checks if someone loged in and gets all information about user
    

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
                personid:''
            },
            show: {         
                id:'',
                title:'',
                about:'',
                lat:'',
                lng:'',
                type:''
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
            date:'',
            measured_distance:null,
            start:new Date().toISOString(),
            end:new Date().toISOString(),
            coordinates:{lat:0, lng:0},
            edit: false,
            truee: true,
        }
    },

    //==============================ON LOAD FUNCTION==============================================
    mounted(){
        //animation
        $("#geras").animate({left: '45%', opacity: '1', top:'40%', fontSize:'50px'}, 900, function(){
            $("#geras").animate({left:'43%',top:'39%', fontSize:'80px'}, 300, function(){
            $("#loading-screen").animate({opacity:'0', width:'0%'}, 300, function(){
            $("#loading-screen").hide();});});});

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
        $("#map").removeClass("col-lg-12");
        $("#map").addClass("col-lg-8");
        $("#side").removeClass("col-lg-0");
        $('#side').show();
        $("#side").addClass("col-lg-4");
    },

    //Closes sidebar
    closeShow: function(){
        $("#map").removeClass("col-lg-8");
        $("#map").addClass("col-lg-12");
        $("#side").removeClass("col-lg-4");
        $('#side').hide();
        $("#side").addClass("col-lg-0");
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

        var dateee = d.toISOString();
        this.start = dateee;
        var dateee = dat.toISOString();
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

        this.parseDate(0); 
        this.parseDate(1);
    },

    //-----------------Closes event creation label---------------------------
    closeAddEvent: function(){
        
         $('#addEvent').modal('hide');
    },

    //---------------------Parse data in right format to choose when event starts and when ends--------------------
    parseDate(choose){
        var d = new Date(this.start);

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

        if(choose == 0){
            var d = new Date(this.start);
            var n = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds(); 
            this.event.time_from = n;
        } 
        else if(choose == 1){
            var d = new Date(this.end);
             var n = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds(); 
            this.event.time_until = n;
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

        dateee = d.toISOString();
        this.start = dateee;
        dateee = dat.toISOString();
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
        this.event.organizator = '';
        this.event.person_id = '';


    },

    sort_places() {
        
        this.$refs.gmapp.fetchPlaces_sort(this.rules);
        
    },

    }
}
</script>


<style>

#time_error{
    margin-top: -15px;
    margin-left: 110px;
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


.vdatetime-input{
    border-radius: 0px;
    box-shadow: none !important;
    border: solid 1px #b7b7b7;
    padding: 8px;
    color:#6C757D;

}

.vdatetime-popup__header {
    background: #28a745;
}

.vdatetime-time-picker__item--selected {
    color: #28a745;
}

.vdatetime-popup__actions__button {
    color: #28a745;
}



@media only screen and (max-width: 900px) {

#map {
    width: 100% !important; 
}
#show{
    width: 95% !important; 
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
