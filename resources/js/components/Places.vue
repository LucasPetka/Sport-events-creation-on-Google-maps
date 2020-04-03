<template>
<div>


    <!-- =========================================SORT PLACES DROPDOWN BAR==============================================-->
    <div class="container-fluid position-absolute" style="z-index:2;">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-sm-12 col-lg-8">
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                    <div class="collapse" id="places_sort">
                    <div class="card card-body pt-4 pl-3 pr-3 pb-0">
                        <div class="row">
                            <div class="col-lg-4 col-md-4">
                                <label for="sport_type-url">Sport type</label>
                                <div class="input-group mb-3">
                                <select @change="sort_places" v-model="rules.type" class="custom-select" id="sport_type">
                                    <option selected>All</option>
                                    <option v-for="type in allTypes.data" :key="type.id" :value="type.id"> {{ type.name }}</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
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
                            <div class="col-lg-4 col-md-4">
                                <label for="paid">Paid</label>
                                <div class="input-group mb-3">
                                <select @change="sort_places" v-model="rules.paid" class="custom-select" id="paid">
                                    <option selected>Any</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                </div>
                            </div>         
                        </div>
                    </div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-2 col-lg-1">
                        <button type="button" id="search_button" class="btn btn-orange-dark pt-2 pb-2 white" style="border-radius: 0px 0px 5px 5px; white-space:nowrap;" data-toggle="collapse" data-target="#places_sort" aria-expanded="false" aria-controls="places_sort" onclick="this.blur();">
                            
                            <span v-if="search_expanded"><i class="fas fa-search"></i> <small><i class="fas fa-chevron-up"></i></small></span>
                            <span v-else ><i class="fas fa-search"></i> <small><i class="fas fa-chevron-down"></i></small></span>

                        </button>
                </div>
            </div>

            <div class="col-2"></div>
        </div>
    </div>



    <!-- ============================================================INSTRUCTIONS============================================================================ -->
    
    <div class="position-fixed" style="z-index:15; top:400px;">
        <button type="button" class="btn btn-orange-dark rounded-right orange" style="border-radius: 0px 5px 5px 0px;" data-toggle="modal" data-target="#infoModal"> <i class="fas fa-info fa-lg m-1"></i> </button>
    </div>

        <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModallLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="infoModalLabel"><i class="fas fa-info fa-lg m-1"></i></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <h5 class="text-center mb-2">About "MoSi" - TESTING VERSION</h5>
            
                <p class="text-center text-secondary m-4"> <b>"MoSi"</b> from Latin words <i>"Motus Simul"</i> which means - movement together. </p>
                <p> This web based application solves the problem for those people who can’t find friends or 
                new teammates for a game or perhaps people are new in town and don’t 
                even know where to go to find a stadium or court. The main concept - 
                an application where you can create and join sports events displayed 
                on GoogleMaps (which been added by people) to go out and spend 
                time with new people or find new sport spots around the area if 
                you are a new community member. <i> You can find instructions below... </i> </p>

                <div id="accordion">
                <div class="card">
                    <div class="card-header p-1" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        How to find events?
                        </button>
                    </h5>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <b class="m-2">Method #1</b>
                        <p>
                            Press on sport place, then open calendar by clicking at date
                            in a box if there is an event on that date the day number 
                            will be higlighted. This is the best way to search for events
                            by seeing what's around you.          
                        </p>
                        <b class="m-2">Method #2</b>
                        <p>
                            At the top there is an orange button "Event Finder".
                            In there you can find filter system which will help
                            you to narrow your search and make it more accurate.       
                        </p>
                    </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header p-1" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        How to add a new event?
                        </button>
                    </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        For adding a new event firstly you have to sign-in or sign-up
                        if you're still not registered. Press on sport place, 
                        choose a date by clicking at date with a box and just 
                        press the green button "+ Add event".
                    </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header p-1" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        How to add a new place?
                        </button>
                    </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card-body">
                        For adding a new event firstly you have to sign-in or sign-up
                        if you're still not registered. Then by clicking right-click 
                        the mouse context-menu will pop-up and there you can choose 
                        option to add a new Place/Marker.
                    </div>
                    </div>
                </div>
                </div>

        </div>
        </div>
    </div>
    </div>


   

    <notifications group="foo" classes="my-style" position="top left" style="margin-top:55px;" />

    <!--===================================================Add event Modal==================================================-->
            <form @submit.prevent="addEvent">
            <div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="addEventCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 class="modal-title" id="addEventLongTitle">Add Event</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="add_event_title">Title</label>
                                <input v-model="event.title" type="text" class="form-control" id="add_event_title" maxlength="45" placeholder="Enter title" required>
                            </div>

                            <div class="form-group">
                                <label for="add_event_about">About</label>
                                <textarea v-model="event.about" class="form-control" id="add_event_about" maxlength="350" rows="6" required></textarea>
                            </div>


                            <div class="row">
                                <ul class="list-group list-group-horizontal mb-5 mx-auto">
                                    <li class="list-group-item"><i class="far fa-calendar-alt"></i> {{ date }}</li>
                                    <li class="list-group-item"> {{ getWeekDay(date) }} </li>
                                    <li class="list-group-item"><i class="far fa-clock"></i> {{ event_time[0] }} -  {{ event_time[1] }}</li>
                                </ul>
                            </div>
                               

                            <vue-slider :tooltip-style="{ backgroundColor: '#313638', borderColor: '#313638' }"  :adsorb="true" v-if="event_time != ''" :process="process" :tooltip="'always'" v-on:drag-end="parseDate()" class="mr-3 ml-3 mb-5" v-model="event_time" :data="data" :marks="marks" :enable-cross="false">

                                 <template v-slot:dot="{ value, focus }">
                                    <div :class="['custom-dot', { focus }]"></div>
                                </template>
                            </vue-slider>

                            <div class="row">
                                <div id="time_error" class="mx-auto"></div>
                            </div>
                            <div class="row">
                                <div id="time_error_second" class="mx-auto"></div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button id="add_event_btn" type="submit" class="btn btn-orange float-right" :disabled="isLoading">Add <i class="fas fa-plus"></i></button>
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
                                <input type="text" class="form-control" placeholder="Title" maxlength="45" v-model="place.title" required>
                                </div>

                                <div class="input-group mb-3">
                                    <textarea class="form-control" rows="6" placeholder="About..." maxlength="350" v-model="place.about" required></textarea>
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="sport_type">Sport</label>
                                    </div>
                    
                                    <select class="custom-select" id="sport_type" v-model="place.type" required>
                                        <option v-for="type in allTypes.data" :key="type.id" :value="type.id"> {{ type.name }}</option>
                                    </select>
                                </div>

                                <div class="custom-control custom-checkbox float-left mr-4">
                                    <input v-model="place.paid" type="checkbox" name="paid" class="custom-control-input" id="paidpp">
                                    <label class="custom-control-label" for="paidpp" >Paid</label>
                                </div>




                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-orange float-right" :disabled="isLoading">Add <i class="fas fa-plus"></i></button>
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
                        </div>

                        <hr class="mt-4">
                            <p class="float-left m-0" v-if="show.paid == 1">
                                <i class="fas fa-coins"></i> <small>Paid</small> <br>
                               <a class="nav-link pl-0" :href="'place_owner/'+show.id">Are you the owner?</a>
                            </p>
                            <p class="float-right"> 
                                <i class="fas fa-road"></i> 
                                <small class="mr-3">This place is {{ measured_distance }} km from you</small>
                                <a :href="'https://www.google.co.uk/maps/dir//'+show.lat+','+show.lng" target="_blank" class="badge badge-dark"><i class="fas fa-map-marker-alt"></i> Show directions</a>
                            </p>

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
                        <div v-if="this.status === 0">
                            <div class="alert alert-warning mt-3 pb-1 text center" role="alert">
                            <p class="text-center"> If you want to join or add events you need to login / register. </p>
                            <div class="form-group">
                                <div class="col-sm-12 col-sm-offset-2 text-center">
                                    <a href ="/login"  class="btn btn-outline-secondary mr-2">Login</a>
                                    <a href ="/register" class="btn btn-outline-secondary ml-2">Register</a>
                                </div>
                            </div>
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
            weekDays:['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday',],
            process: dotsPos => [],
            search_expanded:false,
            isLoading: null,
            event_time:['', ''],
            data: [],
            marks:{
                '06:00': '06:00',
                '09:00': '09:00',
                '12:00': '12:00',
                '15:00': '15:00',
                '18:00': '18:00',
                '21:00': '21:00',
                '23:30': '23:30',
            },
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
                type: 'All', distance: 'Any', paid: 'Any'
            },
            measured_distance:null,
            date:'',
            start:'08:30',
            end:'08:30',
            edit: false,
        }
    },

    //==============================ON LOAD FUNCTION==============================================
    mounted(){

        this.fillArrayWithTimes();

        $('#addPlace').on('hide.bs.modal', (e) => {
           this.$refs.gmapp.hidePointer();
        });

        $('#places_sort').on('show.bs.collapse', (e) => {
           this.search_expanded = true;
        });

        $('#places_sort').on('hide.bs.collapse', (e) => {
           this.search_expanded = false;
        });

        $('#addEvent').on('hide.bs.modal', (e) => {
           $("#time_error").html("");
           $("#time_error_second").html("");
        });

        this.openInfo();

    },

    computed: mapGetters(['allPlaces', 'allTypes']),

    //===============================METHODS=======================================================
    methods: { 

    ...mapActions(['fetchPlacesx']),

    openInfo: function(){

        if(this.getCookie("visited") != "true"){
            $('#infoModal').modal('show');
            console.log("OPEN");
        }
        
            var today = new Date();
            var expire = new Date();
            expire.setTime(today.getTime() + 3600000*24*7);
            document.cookie = "visited=true; expires=" + expire.toGMTString();
        
    },

    fillArrayWithTimes: function(){
        const hours = 24;
        var times_arr = [];
        for (let i = 6; i <= 23; i++) {
            times_arr.push(('0'+i).slice(-2) + ":" + '00');
            times_arr.push(('0'+i).slice(-2) + ":" + '30'); 
        }
        this.data = times_arr;
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

    //------------------------Opens add event creation label-------------------
    openAddEvent: function(){

        this.edit = false;
        this.event.place_id = this.show.id;
        this.event.person_id = this.currentUser.id;
        this.event.organizator = this.currentUser.name;
        this.event_time = [this.event_time[0], this.event_time[1]];

        this.parseDate();


        $('#addEvent').modal('show');
    },

    //-----------------Closes event creation label---------------------------
    closeAddEvent: function(){
         $('#addEvent').modal('hide');
    },

    //---------------------Parse data in right format to choose when event starts and when ends--------------------
    parseDate(){
        if(this.event_time[0] == this.event_time[1]){
            $("#time_error").html("<span class='text-danger'><small>Times should not be equal!</small></span>");
            $("#add_event_btn").attr("disabled", true);
        }
        else{
            $("#time_error").html("");
            $('#add_event_btn').removeAttr("disabled");
        }

        $.ajax({
            type: "GET",
            url: "/validate_time?start="+ this.date +" "+ this.event_time[0]+"&end="+ this.date +" "+ this.event_time[1]+"&place_id="+this.event.place_id+"&event_id="+this.event.id,     
            success: function(result){

                console.log(result);

                if(!result.found){
                    $("#time_error_second").html("");
                    $('#add_event_btn').removeAttr("disabled");
                }  
                else{
                    $("#time_error_second").html("<span class='text-danger'><small>Time overlaping with other events</small></span>");
                    $("#add_event_btn").attr("disabled", true);
                }
                
            }
        });


    },

    //---------------------Gets date in day chooser--------------------------------
    getDate: function(dateee){
        var d = new Date(dateee.date);
        var dat = new Date(dateee.date);
        this.date =  d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
        d.setMinutes(30);
        dat.setMinutes(30);
        dat.setHours(d.getHours() + 2);
        dateee.date = d.getHours() +":"+ d.getMinutes();
        this.event_time[0] = dateee.date;
        dateee.date = dat.getHours() +":"+ dat.getMinutes();
        this.event_time[1] = dateee.date;

        var processArray = [];
        dateee.events.forEach(event => {
            var start_time = this.getTime(event.time_from);
            var end_time = this.getTime(event.time_until);
            var first = this.data.indexOf(start_time) * 2.85714285714;
            var second = this.data.indexOf(end_time) * 2.85714285714;
            processArray.push([first, second, { backgroundColor: 'red' }]);
        });

        this.process = (dotsPos => processArray);
    },

    getTime(date){
        let current_datetime = new Date(date)
        let formatted_Time = ('0' + current_datetime.getHours()).slice(-2) + ":" + ('0' + current_datetime.getMinutes()).slice(-2)
        return formatted_Time;
    },

    getWeekDay(date){
        let current_datetime = new Date(date);
        return this.weekDays[current_datetime.getDay()];
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

        if(confirm('Are you sure?')){
            fetch('api/place/'+ id + '?api_token=' + this.getCookie("api_token"), {
                method: 'delete'
            })
                .then(res => res.json())
                .then(data => {
                    this.$refs.gmapp.fetchPlaces_sort();
                })
                .catch(err => console.log(err));
                this.$refs.gmapp.fetchPlaces_sort();
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

        this.isLoading = true
        setTimeout(() => {
            this.isLoading = false
        }, 2000);

        (async () => {
            const Response = await fetch('api/placequeue?api_token=' + this.getCookie("api_token"), {
                method: 'post',
                body: JSON.stringify(this.place),
                headers: {
                    'Accept': 'application/json',
                    'content-type': 'application/json'
                }     
            });

            const content = await Response.json();

            if(content == true){
                Vue.notify({
                    group: 'foo',
                    title: 'Congrats!!',
                    type: 'success',
                    duration: 10000,
                    text: 'The new place has been sent for inspection and if everything is okay will be uploaded'
                });  
            }
            else{
                Vue.notify({
                    group: 'foo',
                    title: 'Error!!',
                    type: 'error',
                    duration: 10000,
                    text: content.message
                });
            }
            
            })();

        this.place.title = '';
        this.place.about = '';
        this.place.lat = '';
        this.place.lng = '';
        this.place.type = '';
        this.place.paid = '';
        

        this.$refs.gmapp.hidePointer();
        this.closeAdd();
        this.closeShow();

    },

    //-----------------------------------------Create new event-----------------------------------------------
     addEvent() {

        this.isLoading = true
        setTimeout(() => {
            this.isLoading = false
        }, 2000);

            this.event.time_from = this.date + " " + this.event_time[0]
            this.event.time_until = this.date + " " + this.event_time[1];

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
            await this.$refs.calendar.fetchSpot(this.show.id);

            if(content == true){
                Vue.notify({
                    group: 'foo',
                    title: 'Congrats!!',
                    type: 'success',
                    duration: 10000,
                    text: 'Event has been sent for inspection. Will be added if everything is okay.'
                });  
            }
            else{
                Vue.notify({
                    group: 'foo',
                    title: 'Error!!',
                    type: 'error',
                    text: 'There was incorect values in the form!'
                });  
            }
            
            })();

        this.event.id= '';
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

.btn-link {
    font-weight: 800 !important;
    color: rgb(110, 110, 110) !important;
    text-decoration: none;
}
.btn-link:hover {
    font-weight: 800;
    color: #F39448 !important;
    text-decoration: none;
}

.custom-dot {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: rgb(201, 201, 201);
    border: solid 2px rgb(122, 122, 122);
    transition: all .3s;
}
.custom-dot:hover {
    background-color: #313638;
    border: solid 1px rgb(122, 122, 122);
}
.custom-dot.focus {
    background-color: rgba(201, 201, 201, 0.726);
    border: solid #313638;
    border-radius: 0%;
    margin-left: 3px;
    width: 3px;
    height: 25px;
}

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

#map {
    width: 100% !important; 
    height:100% !important;
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


#sidebar{
    height: 94vh;
    overflow-y: auto;
}


#search_button{
    z-index: 50;
}

#places_sort{
    z-index: 51;
    width:100%;
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
