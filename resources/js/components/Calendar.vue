<template>
        <div>

            <div class="row">
                <div class="col-12 m-2" id="calendar">
                        <datepicker placeholder="Select Date" :monday-first="true" :highlighted="highlighted" :format="format" :value="todays_date" v-model="todays_date" @showCalendar="calendarOpened()" @closed="showEvents()"></datepicker>
                        <button dusk="add_new_event_btn" v-if="laterThanYesterday && status == 1" v-on:click="$emit('openAddEvent', todays_date)" class="btn btn-orange pt-2 pb-2 ml-3 float-left" >Add Event <i class="fas fa-plus"></i></button>
                </div>
                <small class="text-muted pl-4">Highlighted days on calendar shows that there are events on that day.</small>
            </div>
            
            <hr class="mt-0 mb-0">
        
            <div class="pt-3">

                <div v-for="event in show_events" v-bind:key="event.id" class="card mb-3" style="width: 95%; margin-left:auto; margin-right:auto;">
                <div class="card-body p-3">
                    <h5 class="card-title"> <a class="orange" target="_blank" :href="'event/' + event.id +'/'+ event.title"> {{ event.title }} </a></h5>
                    <h6 class="card-subtitle mb-3 text-muted">{{ countPeopleGoing(event.id) }} people going</h6>
                    <p class="card-text mb-2">{{ event.about }}</p>
                    <hr class="mt-2 mb-1">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item p-1"><i class="far fa-clock"></i> {{ returnTime(event.time_from)  }} - {{ returnTime(event.time_until) }}</li>
                        <li class="list-group-item card-subtitle mb-2 p-1 text-muted">Event created by <a target="_blank" :href="'/user/' + event.person_id.auth_id">{{ event.person_id.name }}</a></li>
                    </ul>

                    <div v-if="status === 1">
                        <div v-if="ifJoined(event.place_id, event.id, currentUser.id) == 0">
                            <button dusk="join_an_event_btn" id="join_btn" type="button" class="btn btn-success float-left" :disabled="isLoading" v-on:click="addPerson(event.place_id, event.id, currentUser.id, $event)"><i class="fas fa-user-plus"></i> Join</button>
                        </div>
                        <div v-else>
                            <button dusk="leave_an_event_btn" type="button" class="btn btn-secondary float-left" :disabled="isLoading" v-on:click="deletePerson(event.place_id, event.id, currentUser.id, $event)"><i class="fas fa-check"></i> Joined</button>
                        </div>
                    
                        <div v-if="currentUser.id == event.person_id.id">
                            <editevent :user="currentUser" v-on:fetch="fetchSpot(event.place_id)" :acceptedOrDeclined="false" :event="event"></editevent>
                        </div>
                    </div>
                    
                </div>
                </div>    

                <div v-if="show_events.length === 0" class="alert text-center alert-light mt-3" role="alert">
                    <i class="far fa-frown"></i> Sorry, but there are no events on this day... 
                </div>


            </div>
            </div>
</template>    


<script>
const Datepicker = () => import("vuejs-datepicker");
const editevent = () => import("../components/EditEvent.vue");

import { mapGetters, mapActions } from 'vuex';

export default {

    components: {
        'editevent': editevent,
        Datepicker
    },

    props: ['status', 'currentUser'], //checks if someone loged in and gets all information about user

    
    data(){
        return{
            isLoading: null,
            format:'yyyy-MM-dd',
            todays_date: new Date(),
            place_id: null,
            events: [],
            people_going: [],
            edit: false,
            laterThanYesterday: true,
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

        const foundEvents = this.events.filter( event => event.place_id == this.place_id);
        this.show_events = foundEvents.filter(event => this.getDate(event.time_from) == this.getDate(this.todays_date));
        this.$emit('getDate', {'date':this.todays_date.toISOString(), 'events':this.show_events});

        var newDate = new Date();
        newDate.setDate(newDate.getDate() - 1);

        if(this.todays_date >= newDate){
            this.laterThanYesterday = true;
        }
        else{
            this.laterThanYesterday = false;
        }
        
        
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

        //get cookie by name from cookie string
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

        //checks if calendar has been opened | listening for an event
        calendarOpened: function(){
            console.log('Calendar Opened');
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
    cursor: pointer;
}

.vdp-datepicker__calendar {

    margin-bottom: 60px;
}

</style>

