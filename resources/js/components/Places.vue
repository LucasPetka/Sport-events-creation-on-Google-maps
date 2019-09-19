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
                                <Calendar v-bind:status='status' v-bind:currentUser='currentUser' v-on:getDate="getDate($event)" v-on:closeAdd="closeAddEvent()" ref="calendar"> </Calendar>
                                 <button v-on:click="openAddEvent()" class="btn btn-outline-danger float-right">Add Event on this day +</button>


                            </div>
                        </div>

                        <div id="addEvent" class="card m-3 width:100%; height:100%;" style="display:none;">
                            <div class="card-header ">
                                <h6>Add Event</h6>
                            </div>
                            <div class="card-body">
                                
                                <form>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Title</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter title">
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">About</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                </div>

                                <datetime  type="time" v-model="start" format="yyyy-MM-dd HH:mm" :value-zone="'local'" ></datetime>

                                <datetime  type="time" v-model="end" format="yyyy-MM-dd HH:mm" :value-zone="'local'" ></datetime>

                                {{ this.currentUser.name  }}
                                <br>

                                

                                <button type="submit" class="btn btn-primary">Submit</button>
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
            place_id:'',
            new_id:'',
            coordinates:{lat:0, lng:0},
            pagination: {},
            edit: false,
            show_new: false,
            start: new Date().toISOString(),
            end: new Date().toISOString(),
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

        const create = document.querySelector('#addEvent');

        if (create.style.display === 'none') {
            $("#addEvent").slideDown("slow");
            
            //$("#show").animate({ scrollTop: $("#addEvent").offset().top }, "slow");

            $('.card-body').animate({
                scrollTop: $('#addEvent').position().top - 40
            }, 1000);
        }
            
    },

    getDate: function(dateee){
    
    this.start= dateee;
    this.end= dateee;

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
        }
    }
}
</script>


<style scoped>

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

.vdatetime-input
{
    border-radius:5px;
    background-color:black;
    color:red;
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
