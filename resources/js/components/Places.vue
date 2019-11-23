<template>
<div>
    <div id="loading-screen">
    <div id="geras">MoSi</div>
    </div>

    <notifications group="foo" classes="my-style" ignoreDuplicates position="top center" />

        <div class="row" style="width:100%; padding:0; margin:0;">
            <div id="map" class="col-lg-12 p-0">
                <Gmap v-bind:status='status' v-on:showSpot="showSpot($event)" v-on:openForm="openAdd($event)" ref="gmapp"> </Gmap>

                
            </div>
            <div id="side" class="col-lg-0 p-0" style="display:none;">
                <div id="sidebar">



                <!-- ================================================================================================================= -->
                <!-- =====================================ADD NEW PLACE========================================START================== -->
                <!-- ================================================================================================================= -->

                    <div class="card m-3" id="createDiv" style="display:none; width:90%;">
                        <div class="card-header">

                            <button type="button" id="close_createDiv" v-on:click="closeAdd()" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>

                            <h6>Create new Marker</h6>

                        </div>

                        <form @submit.prevent="addPlace">
                            <div class="card-body">
                                
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Title" v-model="place.title">
                                    </div>

                                    <div class="input-group mb-3">
                                        <textarea class="form-control" placeholder="About..." v-model="place.about" ></textarea>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="inputGroupSelect01">Sport</label>
                                        </div>
                        
                                        <select class="custom-select" id="inputGroupSelect01" v-model="place.type">
                                            
                                            <option value="112">Soccer inside</option>
                                            <option value="111">Soccer</option>
                                            <option value="223">Basketball inside</option>
                                            <option value="222">Basketball</option>
                                            <option value="334">Volleyball inside</option>
                                            <option value="333">Voleyball</option>
                                        </select>
                                    </div>
                                
                            </div>

                            <div class="card-footer text-muted">
                                <p>
                                    <button type="submit" class="btn btn-danger m-2">Create</button>
                                </p>
                            </div>

                        </form>
                    </div>



                <!-- ================================================================================================================= -->
                <!-- =====================================SHOW SPOT INFO=======================================START================== -->
                <!-- ================================================================================================================= -->
                    <div id="show" class="show" style="display:none; width:100%; height:auto; padding-bottom:150px;" >
                            
                        <div class="d-flex flex-column bd-highlight mb-3">
                            
                            <div class="p-4 bd-highlight">
                                <button type="button" id="close_show" v-on:click="closeShow()" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                
                                <div v-if="type.id == '111' || type.id == '112'">
                                    <h3> <img src="../assets/google_maps/soccerball.png"> {{ show.title }}</h3>
                                </div>
                                <div v-if="type.id == '222' || type.id == '223'">
                                    <h3> <img src="../assets/google_maps/basketball.png"> {{ show.title }}</h3>
                                </div>
                                <div v-if="type.id == '333' || type.id == '334'">
                                    <h3> <img src="../assets/google_maps/volleyball.png"> {{ show.title }}</h3>
                                </div>

                                <hr>

                                <div class="card-body">
                                    <p class="card-text">{{ show.about }}</p>

                                    <hr class="mt-4">
                                    <p class="float-right"><small>This place is {{ measured_distance }} km from you</small></p>

                                </div>


                                <span v-if="currentUser">
                                <button v-if="currentUser.isAdmin == 1" v-on:click="deletePlace(show.id)" class="btn btn-danger m-2">Delete</button>
                                </span>

                            </div>


                            
                        </div>
                        
                        
                        <div class="card m-3 width:100%; height:100%;">
                            <div class="card-header ">
                                <h6>Events</h6>  
                            </div>
                            <div class="card-body">

                                <div v-if="this.status === 1">
                                    <button v-on:click="openAddEvent()" class="btn btn-outline-danger float-right">Add Event <i class="fas fa-plus"></i></button>
                                </div>
                                <div v-else>
                                    <div class="alert alert-warning pb-5" role="alert">
                                    If you want to join or add events you need to login / register.<br>
                                    <a href ="/register" class="btn btn-outline-secondary float-right ml-2">Register</a>
                                    <a href ="/login"  class="btn btn-outline-secondary float-right mr-2">Login</a>
                                    </div>
                                </div>

                                <Calendar v-bind:status='status' v-bind:currentUser='currentUser' v-on:getDate="getDate($event)" v-on:closeAdd="closeAddEvent()" ref="calendar"> </Calendar>
                            </div>
                        </div>

                        <div id="addEvent" class="card m-3 width:100%; height:100%;" style="display:none;">
                            <div class="card-header ">
                                <h6>Add Event</h6>
                            </div>
                            <div class="card-body">
                                
                                <form @submit.prevent="addEvent">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Title</label>
                                    <input v-model="event.title" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter title">
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">About</label>
                                    <textarea v-model="event.about" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                </div>



                                <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ date }}</span>
                                </div>
                                    <datetime  type="time" id="start" v-model="start" format="HH:mm" :value-zone="'local'" :minute-step="10" v-on:input="parseDate(0)" ></datetime>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2"><i class="far fa-clock"></i></span>
                                </div>
                                </div>



                                <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ date }}</span>
                                </div>
                                    <datetime  type="time" id="end" v-model="end" format="HH:mm" :value-zone="'local'" :minute-step="10" v-on:input="parseDate(1)" ></datetime>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2"><i class="far fa-clock"></i></span>
                                </div>
                                </div>
                                
                                
                                <br>

                                <button type="submit" class="btn btn-success float-right">Add <i class="fas fa-plus"></i></button>
                                </form>
                            </div>
                        </div>


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

export default {
        components: {
        'Gmap': Gmap,
        'Calendar': Calendar,
        'datetime': Datetime,
    },

    props: ['status','currentUser'], //checks if someone loged in and gets all information about user
    

    data(){
        return{
            places: [],
            types:[],
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
        $("#geras").animate({left: '45%', opacity: '1', top:'40%', fontSize:'50px'}, 1500, function(){
            $("#geras").animate({left:'43%',top:'39%', fontSize:'80px'}, 500, function(){
            $("#loading-screen").animate({opacity:'0', width:'0%'}, 500, function(){
            $("#loading-screen").hide();});});});

        this.fetchPlaces(); //fetcing all places
        this.fetchTypes();  //fething all sport types
    },



    //===============================METHODS=======================================================
    methods: { 

    
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

    //Opens place addition label
    openAdd: function(cord){
        this.place.lat = cord.lat;
        this.place.lng = cord.lng;

        const create = document.querySelector('#createDiv');

        if (create.style.display === 'none') {

            this.openShow();
            $("#createDiv").slideDown("slow");
        }
            
    },

    //Closes place adition label
    closeAdd: function(){
           if($("#close_show").is(":visible")){
                 $("#createDiv").slideUp("slow");
                 this.$refs.gmapp.hidePointer();
            }
            else{
                $("#createDiv").slideUp("slow");
                this.$refs.gmapp.hidePointer();
                $(".popup-content").fadeOut("slow");
            }
            this.closeShow();
    },


    //Opens event creation label
    openAddEvent: function(){

        this.event.place_id = this.show.id;
        this.event.person_id = this.currentUser.id;
        this.event.organizator = this.currentUser.name;

        const create = document.querySelector('#addEvent');

        if (create.style.display === 'none') {
            $("#addEvent").slideDown("slow");

            $('.card-body').animate({
                scrollTop: $('#addEvent').position().top - 40
            }, 1000);
        } 
    },

    //Closes event creation label
    closeAddEvent: function(){
         $("#addEvent").hide("fast");
    },

    //Parse data in right format to choose when event starts and when ends
    parseDate(choose){
        var d = new Date(this.start);

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

    //Gets date in day chooser
    getDate: function(dateee){
        var d = new Date(dateee);
        this.date =  d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
        d.setMinutes(30);
        dateee = d.toISOString();
        this.start = dateee;
        this.end = dateee;
    },

    //Show spot all info with all events happening in there
    showSpot: function(arg){

        const foundPlace = this.places.find( place => place.id == arg[0]);
        this.show = foundPlace;

        this.$refs.calendar.fetchSpot(this.show.id);

        const foundType = this.types.find( type => type.id == this.show.type);
        this.type = foundType;

        const show = document.querySelector('#show');

        this.measured_distance = arg[1];

        this.openShow();

        if (show.style.display === 'none') {
            $("#show").slideDown("slow");
        }

        this.closeAddEvent();
    },


    fetchPlaces() {
            fetch('api/places')
            .then(res => res.json())
            .then(res => {
                this.places = res.data;
            })
    },


    fetchTypes() {
            fetch('api/types')
            .then(res => res.json())
            .then(res => {
                this.types = res.data;
            })
    },


    getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
    },


    //Deletes place
    deletePlace: function(id) {

        console.log(this.getCookie("api_token"));

        if(confirm('Are you sure?')){
            fetch('api/place/'+ id + '?api_token=' + this.getCookie("api_token"), {
                method: 'delete'
            })
                .then(res => res.json())
                .then(data => {
                    this.fetchPlaces();
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

    //Adds new place
    addPlace() {
        this.place.personid = this.$props.currentUser.id;

        if(this.edit === false){
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
                this.fetchPlaces();
            })
            .catch(err =>console.log(err));

            Vue.notify({
                group: 'foo',
                title: 'Congrats!!',
                type: 'success',
                text: 'The new place has been sent for inspection and if everything is okay will be uploaded'
                });

                this.$refs.gmapp.fetchPlaces();
                this.$refs.gmapp.hidePointer();
                this.closeAdd();
                this.closeShow();


        } else{

        }
    },

    //Create event
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
                text: 'You have created an event !'
                });

                this.event.place_id = '';
                this.event.title = '';
                this.event.about = '';
                this.event.time_from = '';
                this.event.time_until = '';
                this.event.organizator = '';
                this.event.person_id = '';      
        } else{

        }
    },

    }
}
</script>


<style>

.my-style {
    padding: 15px;
    margin-top: 0px;
    width: 300px;
 
    font-size: 14px;
    border-radius: 0px 0px 10px 10px;


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

#loading-screen {
    position: fixed;
    z-index: 100;
    background-color: #82cc75;
    height: 100%;
    width: 100%;
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
