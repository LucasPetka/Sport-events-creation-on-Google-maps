<template>
        <div>
            
            <div class="input-group mb-3 mx-auto">
            <datepicker placeholder="Select Date"  :highlighted="highlighted" :format="format" :value="todays_date" v-model="todays_date" @closed="showEvents()"></datepicker>
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2"><i class="far fa-calendar-alt"></i></span>
            </div>
            </div>
            
            <div>

                <div v-for="event in show_events" v-bind:key="event.id" class="card mt-3"  style="width: 90%; margin-left:auto; margin-right:auto;">
                <div class="card-body">
                    <h5 class="card-title">{{ event.title }}</h5>
                    <h6 class="card-subtitle mb-3 text-muted">{{ countPeopleGoing(event.id) }} people going</h6>
                    <p class="card-text">{{ event.about }}</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Start: {{ returnTime(event.time_from)  }} <br> End: {{ returnTime(event.time_until) }}</li>
                        <li class="list-group-item card-subtitle mb-2 text-muted">Event created by {{ event.organizator }}</li>
                    </ul>

                    <div v-if="ifJoined(event.place_id, event.id, currentUser.id) == 0">
                        <button id="join_btn" type="button" class="btn btn-success" v-on:click="addPerson(event.place_id, event.id, currentUser.id, $event)">Join</button>
                    </div>
                    <div v-else>
                        <button type="button" class="btn btn-secondary" disabled>Joined</button>
                    </div>
                    
                    
                </div>
                </div>    

                <div v-if="show_events.length === 0" class="alert alert-light" role="alert">
                Sorry, but there are no events on this day... 
                </div>

            </div>
        </div>    
</template>

<script>
import Datepicker from 'vuejs-datepicker';

export default {

    components: {
    Datepicker
    },

    props: ['status', 'currentUser'],
    
    data(){
        return{
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

    created(){
        this.fetchEvents();
        this.fetchPeopleGoing();

    },

    methods: {

        getDate: function(date){

             var new_date = new Date(date);

            return new_date.getUTCFullYear(0) +'-'+ new_date.getUTCMonth(0) + '-' +  new_date.getUTCDate(0);
        },

        sendDate: function(date){

             var new_date = new Date(date);

            return new_date.getUTCFullYear() +'-'+ new_date.getUTCMonth() + '-' +  new_date.getUTCDate() + 'T' + new_date.getUTCHours() + ':' + new_date.getUTCMinutes()+ ':' + new_date.getUTCSeconds() + '.' + new_date.getUTCMilliseconds() + 'Z';
        },


        ifJoined: function(place, event, user){

            var ans = 0;
            
            this.people_going.forEach(myFunction);
            function myFunction(person, index) {

                if(person.place_id == place && person.event_id == event && person.person_id == user){
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


        showEvents: function(){

        const foundEvents = this.events.filter( event => event.place_id == this.place_id);
        this.show_events = foundEvents.filter(event => this.getDate(event.time_from) == this.getDate(this.todays_date));
        this.$emit('getDate', this.todays_date.toISOString());
        console.log("Todays date: " + this.todays_date);
        console.log("Place id: " + this.place_id);
        this.$emit('closeAdd');


        },

        returnTime: function(time){
           var timews = time.split(" ");
           var time =  timews[1].split(":");
            return time[0] + ":" + time[1];
        },

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



        setId: function(id){
            this.todays_date = new Date();

            this.place_id = id;
            const foundEvents = this.events.filter( event => event.place_id == this.place_id);
            this.show_events = foundEvents.filter(event => this.getDate(event.time_from) == this.getDate(this.todays_date));

            this.highlighted = {
            dates:null,
            };
            var highlightedDays = []; 

            foundEvents.forEach(function(event) {

                var date = new Date(event.time_from);

                highlightedDays.push(date);
            });

            this.highlighted = {
            dates:highlightedDays,
            };
        },



        fetchEvents() {
            fetch('api/events')
            .then(res => res.json())
            .then(res => {
                this.events = res.data;
            })
    },



    fetchPeopleGoing() {
            fetch('api/people_going')
            .then(res => res.json())
            .then(res => {
                this.people_going = res.data;
            })
    },



        addPerson(place, event, person, but) {
            this.person.place_id = place;
            this.person.event_id = event;
            this.person.person_id = person;
        
            fetch('api/person', {
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

                var target = $( but.target );

                    target.html('Joined');
                    target.attr("disabled", true);
                    if(target.hasClass("btn btn-success"))
                    target.removeClass("btn btn-success").addClass("btn btn-secondary");;

            })
            .catch(err =>console.log(err));

        }

    }
}
</script>

<style>

.vdp-datepicker input{
    border-radius: 5px 0px 0px 5px;
    box-shadow: none !important;
    border: solid 1px #b7b7b7;
    padding: 8px;
}

</style>