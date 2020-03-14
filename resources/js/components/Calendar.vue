<template>
        <div>

            <!-- ==============================Confirmation MODAL============================================== -->
            <div class="modal fade" id="confirmation_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-body">
                    <p class="h5 text-center mb-0 pb-0">Are you sure you want to delete this event?</p><br>
                    <p class="text-muted text-center mb-0 pb-0"><small> {{ event_mod.title }} </small></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" v-on:click="deleteEvent(event_mod.id)">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
                </div>
            </div>
            </div>


            <div class="row">
                <div class="col-12 m-2" id="calendar">
                        <datepicker placeholder="Select Date" :highlighted="highlighted" :format="format" :value="todays_date" v-model="todays_date" @closed="showEvents()"></datepicker>
                        <button v-on:click="$emit('openAddEvent')" class="btn btn-outline-success pt-2 pb-2 ml-3 float-left">Add Event <i class="fas fa-plus"></i></button>
                </div>
            </div>
            
            <hr class="mt-0">
        
            <div>

                <div v-for="event in show_events" v-bind:key="event.id" class="card mb-3"  style="width: 90%; margin-left:auto; margin-right:auto;">
                <div class="card-body">
                    <h5 class="card-title"> <a target="_blank" :href="'event/' + event.id"> {{ event.title }} </a></h5>
                    <h6 class="card-subtitle mb-3 text-muted">{{ countPeopleGoing(event.id) }} people going</h6>
                    <p class="card-text">{{ event.about }}</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Start: {{ returnTime(event.time_from)  }} <br> End: {{ returnTime(event.time_until) }}</li>
                        <li class="list-group-item card-subtitle mb-2 text-muted">Event created by <a target="_blank" :href="'/user/' + event.person_id.auth_id">{{ event.person_id.name }}</a></li>
                    </ul>

                    <div v-if="status === 1">
                        <div v-if="ifJoined(event.place_id, event.id, currentUser.id) == 0">
                            <button id="join_btn" type="button" class="btn btn-success float-left" :disabled="isLoading" v-on:click="addPerson(event.place_id, event.id, currentUser.id, $event)"><i class="fas fa-user-plus"></i> Join</button>
                        </div>
                        <div v-else>
                            <button type="button" class="btn btn-secondary float-left" :disabled="isLoading" v-on:click="deletePerson(event.place_id, event.id, currentUser.id, $event)"><i class="fas fa-check"></i> Joined</button>
                        </div>
                    

                        <div v-if="currentUser.id == event.person_id.id">
                            <button type="button" class="btn btn-danger float-right ml-2" v-on:click="openConfirmation(event)"> <i class="fas fa-trash-alt"></i> </button>
                            <button type="button" class="btn btn-primary float-right" v-on:click="editEvent(event.id)"> <i class="far fa-edit"></i> </button>
                        </div>

                    </div>
                </div>
                </div>    

                <div v-if="show_events.length === 0" class="alert text-center alert-light mt-3" role="alert">
                Sorry, but there are no events on this day... 
                </div>


            </div>
            </div>
</template>    


<script>
const Datepicker = () => import("vuejs-datepicker");

import { mapGetters, mapActions } from 'vuex';

export default {

    components: {
    Datepicker
    },

    props: ['status', 'currentUser'], //checks if someone loged in and gets all information about user

    
    data(){
        return{
            event_mod: {
                id:'',
                place_id:'',
                title:'',
                about:'',
                time_from:'',
                time_until:'',
                organizator:'',
                people_going:0,
            },
            isLoading: null,
            format:'yyyy-MM-dd',
            todays_date: new Date(),
            place_id: null,
            events: [],
            people_going: [],
            edit: false,
            show_events: [],
            highlighted: {
                dates:[],
            },
            event: {
                id:'',
                place_id:'',
                title:'',
                about:'',
                time_from:'',
                time_until:'',
                organizator:'',
                people_going:0
            },
            person: {
                place_id:'',
                event_id:'',
                person_id:''
            },
            
        }
    },

    //ONLOAD PAGE DO THIS
    mounted(){
        //this.fetchEvents(); // get all events from data base
        this.fetchPeopleGoing(); // get all people going from data base
    },

    computed: mapGetters(['allPlaces','allTypes']),

    

    //================================METHODS========================================
    methods: {

    ...mapActions(['fetchPlacesx', 'fetchTypesx']),

        //Create todays date variable
        getDate: function(date){

             var new_date = new Date(date);

            return new_date.getUTCFullYear(0) +'-'+ new_date.getUTCMonth(0) + '-' +  new_date.getUTCDate(0);
        },


        openConfirmation: function(event){
            this.event_mod = event;
            $('#confirmation_modal').appendTo("body").modal('show');
        },
        
        //Check if person already joined to current event
        ifJoined: function(place, event, user){

            var ans = 0;
            
            this.people_going.forEach(myFunction);
            function myFunction(person, index) {

                if(person.place_id == place && person.event_id == event && person.person_id.id == user){
                    ans = 1;

                } else{
                    if(ans == 1){
                        ans= 1;
                    }
                    else{
                    ans = 0;
                    }
                }
                
            }

            return ans;
        },


        //Show all events in exact place and day
        showEvents: function(){

        //this.todays_date =

        const foundEvents = this.events.filter( event => event.place_id == this.place_id);
        this.show_events = foundEvents.filter(event => this.getDate(event.time_from) == this.getDate(this.todays_date));
        this.$emit('getDate', this.todays_date.toISOString());
        this.$emit('closeAdd');
        },

        //Shows fixed format of time
        returnTime: function(time){
           var timews = time.split(" ");
           var time =  timews[1].split(":");
            return time[0] + ":" + time[1];
        },


        //Counts how much people joined specific event
        countPeopleGoing(event_id){
            
            var people = 0;
            this.people_going.forEach(myFunction);
            function myFunction(person, index) {

                if(person.event_id == event_id){
                   people++;
                } 
            
            }

            return people;
        },


        //Gets id of place and shows information
        fetchSpot: function(id){

            this.place_id = id; // gets the place where we looking at

            fetch('api/events/' + id)
            .then(res => res.json())
            .then(res => {
            
                this.events = res.data;
                
                //Shows all events which are on that spot and and that day
                this.showEvents();
    
                //Highlights all days when events happening
                var highlightedDays = []; 

                var foundEvents = this.events.filter( event => event.place_id == this.place_id);
                
                this.highlighted = {
                dates:null,
                };

                foundEvents.forEach(function(event) {
                    var date = new Date(event.time_from);
                    highlightedDays.push(date);
                });

                this.highlighted = {
                dates:highlightedDays,
                };
            })

        },


        //Get all people that going to events
        fetchPeopleGoing() {
                fetch('api/people_going')
                .then(res => res.json())
                .then(res => {
                    this.people_going = res.data;
                })
        },

        getCookie(name) {
            var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return v ? v[2] : null;
        },


        //Add person to the event
        addPerson(place, event, person, but) {
            
            this.isLoading = true
            setTimeout(() => {
                this.isLoading = false
            }, 2000);

            this.person.place_id = place;
            this.person.event_id = event;
            this.person.person_id = person;
        
            fetch('api/person?api_token=' + this.getCookie("api_token"), {
                method: 'post',
                body: JSON.stringify(this.person),
                headers: {
                    'content-type': 'application/json'
                }
            })
            .then(res => res.json())
            .then( data=> {
                this.person.place_id = '';
                this.person.event_id = '';
                this.person.person_id = '';

                this.fetchPeopleGoing();

            })
            .catch(err =>console.log(err));

            Vue.notify({
                group: 'foo',
                title: 'Congrats!!',
                type: 'success',
                text: 'You have joined an event !'
                });

        },

        
        //Delete person from event
        deletePerson: function(place, event, person, but) {

            this.isLoading = true
            setTimeout(() => {
                this.isLoading = false
            }, 2000);

            const first = this.people_going.filter( oneper => oneper.person_id.id == person);
            const second = first.filter( oneper => oneper.event_id == event);
            const third = second.filter( oneper => oneper.place_id == place);

            var id = third[0].id;
         
            fetch('api/person/'+ id + '?api_token=' + this.getCookie("api_token"), {
                method: 'delete'
            })
                .then(res => res.json())
                .then(data => {

                    this.fetchPeopleGoing();

                })
                .catch(err => console.log(err));

                Vue.notify({
                group: 'foo',
                title: 'Notification',
                type: 'error',
                text: 'You left the event !'
                });

        },

        //Edit event
        editEvent: function(eventId){
            this.$emit('editEvent', eventId);
        },

        //Delete event from database
        deleteEvent: function(eventId) {
         
            fetch('api/event/'+ eventId + '?api_token=' + this.getCookie("api_token"), {
                method: 'delete'
            })
                .then(res => res.json())
                .then(data => {

                    fetch('api/events')
                    .then(res => res.json())
                    .then(res => {
                        this.events = res.data;
                        this.showEvents();
                        this.fetchSpot(this.place_id);
                    })

                })
                .catch(err => console.log(err));

                Vue.notify({
                group: 'foo',
                title: 'Notification',
                type: 'error',
                text: 'You have deleted an event'
                });

                $('#confirmation_modal').modal('hide');

        },

    }
}
</script>

<style>

#calendar .vdp-datepicker input{
    border-radius: 5px 5px 5px 5px;
    width: 110px;
    box-shadow: none !important;
    border: solid 1px #b7b7b7;
    padding: 8px;
    float: left;
    outline: none !important;
}

</style>

