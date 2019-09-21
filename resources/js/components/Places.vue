<template>
    <div>
        <div class="row" style="width:100%; padding:0; margin:0;">
            <div id="map" style="width:100%;">
                <Gmap v-bind:status='status' v-on:showSpot="showSpot($event)" v-on:openForm="openAdd($event)" ref="gmapp"> </Gmap>
            </div>
        </div>

        <div class="card popup-content">
            <div class="card-header">
                        <button type="button" id="close_createDiv" v-on:click="closeSide()" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        <h6>Controls</h6>
            </div>
            <div class="card-body" style=" height:92%; overflow-y: auto;">
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
                                        <label class="input-group-text" for="inputGroupSelect01">Options</label>
                                    </div>

                                    <select class="custom-select" id="inputGroupSelect01" v-model="place.type">
                                        <option selected>Sporto šaka...</option>
                                        <option value="112">Futbolas salėje</option>
                                        <option value="111">Futbolas lauke</option>
                                        <option value="223">Krepšinis salėje</option>
                                        <option value="222">Krepšinis lauke</option>
                                        <option value="334">Tinklinis salėje</option>
                                        <option value="333">Tinklinis lauke</option>
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




                    <div id="show" class="show" style="display:none; width:100%; height:auto; padding-bottom:150px;" >
                        
                        <div class="card m-3">
                        <div class="card-header">
                            <button type="button" id="close_show" v-on:click="closeShow()" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            <h6>{{ show.title }}</h6>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ show.about }}</p>
                        </div>
                        <div class="card-footer text-muted">
                            <p>Sport type: {{ type.name }}</p>
                            <p>
                               <!-- <button v-on:click="deletePlace(show.id)" class="btn btn-danger m-2">Delete</button>-->
                            </p>
                        </div>
                        </div>
                    
                        <div class="card m-3 width:100%; height:100%;">
                            <div class="card-header ">
                                <h6>Events

                                    
                                </h6>
                                
                                   
                                
                            </div>
                            <div class="card-body">

                                <div v-if="this.status === 1">
                                 <button v-on:click="openAddEvent()" class="btn btn-outline-danger float-right">Add Event <i class="fas fa-plus"></i></button>
                                </div>
                                <div v-else>
                                    <div class="alert alert-warning pb-5" role="alert">
                                    If you want to join or add events you need to login / register.<br>
                                    <button type="button" class="btn btn-outline-secondary float-right ml-2">Register</button>
                                    <button type="button" class="btn btn-outline-dark float-right mr-2">Login</button>
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
</template>


<script>
import Gmap from '../components/Gmap.vue';
import Calendar from '../components/Calendar.vue';
import { Datetime } from 'vue-datetime';

export default {

        components: {
        'Gmap': Gmap,
        'Calendar': Calendar,
        'datetime': Datetime
    },

    props: ['status','currentUser'],
    

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
                type:''
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
            place_id:'',
            date:'',
            start:new Date().toISOString(),
            end:new Date().toISOString(),
            coordinates:{lat:0, lng:0},
            edit: false,
        }
    },

    created(){
        this.fetchPlaces();
        this.fetchTypes();
        
    },

    methods: { 



    closeShow: function(){
            if($("#createDiv").is(":visible")){
                 $("#show").slideUp("slow");
            }
            else{
                $("#show").slideUp("slow");
                $(".popup-content").fadeOut("slow");
            }
    },



    closeSide: function(){
            $(".popup-content").fadeOut("slow");
    },



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
    },



    openAdd: function(cord){
        this.place.lat = cord.lat;
        this.place.lng = cord.lng;

        const create = document.querySelector('#createDiv');

        if (create.style.display === 'none') {
            $(".popup-content").fadeIn( "slow" );
            $("#createDiv").slideDown("slow");
        }
            
    },



    closeAddEvent: function(){
         $("#addEvent").hide("fast");
    },



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

    parseDate(choose){

        var d = new Date(this.start);

        this.date =  d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();

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

    getDate: function(dateee){
    
    this.start = dateee;
    this.end = dateee;

    },

    




    showSpot: function(id){

        const foundPlace = this.places.find( place => place.id == id);
        this.show = foundPlace;

        this.$refs.calendar.setId(this.show.id);

        const foundType = this.types.find( type => type.id == this.show.type);
        this.type = foundType;

        const show = document.querySelector('#show');

        if (show.style.display === 'none') {
            $(".popup-content").fadeIn( "slow" );
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


    deletePlace: function(id) {
        if(confirm('Are you sure?')){
            fetch('api/place/'+ id, {
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

                this.closeShow();
                
        }
    },

    addPlace() {
        if(this.edit === false){
            fetch('api/place', {
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

                this.$refs.gmapp.fetchPlaces();
                this.$refs.gmapp.hidePointer();
                this.closeAdd();

        } else{

        }
    },



    addEvent() {
        if(this.edit === false){
            fetch('api/event', {
                method: 'post',
                body: JSON.stringify(this.event),
                headers: {
                    'content-type': 'application/json'
                }
            })
            .then(res => res.json())
            .then( data=> {
                this.event.place_id = '';
                this.event.title = '';
                this.event.about = '';
                this.event.time_from = '';
                this.event.time_until = '';
                this.event.organizator = '';
                this.event.person_id = '';
                this.$refs.calendar.fetchEvents();
                
                setTimeout(() => { 
                    this.$refs.calendar.showEvents();
                    this.$refs.calendar.setId(this.show.id);
                 }, 800)
                
                
            })
            .catch(err =>console.log(err));

        } else{

        }
    },

    }
}
</script>


<style>

.popup-content {
    display:none;
    position: fixed;
    bottom: 110px;
    right: 25px;
    z-index: 2;
    width: 30%;
    height:75vh;
    overflow: auto;
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
  #side_bar{
    width: 100% !important; 
  }
  #show{
    width: 95% !important; 
  }
  #createDiv{
    width: 95% !important;   
  }

}
</style>
