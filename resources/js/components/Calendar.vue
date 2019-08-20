<template>
        <div>
            <datepicker placeholder="Select Date"  :highlighted="highlighted" :format="format" :value="todays_date" v-model="todays_date" @closed="showEvents()"></datepicker>
            <div>

                <div v-for="event in show_events" v-bind:key="event.id" class="card mt-3"  style="width: 90%; margin-left:auto; margin-right:auto;">
                <div class="card-body">
                    <h5 class="card-title">{{ event.title }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ event.people_going }} people going</h6>
                    <p class="card-text">{{ event.about }}</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">From: {{ event.time_from }} </li>
                        <li class="list-group-item">To: {{ event.time_until }}</li>
                        <li class="list-group-item card-subtitle mb-2 text-muted">Event created by {{ event.organizator }}</li>
                    </ul>
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
    
    data(){
        return{
            format:'yyyy-MM-dd',
            todays_date: new Date(),
            place_id: null,
            events: [],
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
                people_going:'',
                organizator:''
            }
        }
    },

    created(){
        this.fetchEvents();

    },

    methods: {



        getDate: function(date){

             var new_date = new Date(date);

            return new_date.getUTCFullYear(0) +'-'+ new_date.getUTCMonth(0) + '-' +  new_date.getUTCDate(0);
        },


        showEvents: function(){

        const foundEvents = this.events.filter( event => event.place_id == this.place_id);
        this.show_events = foundEvents.filter(event => this.getDate(event.time_from) == this.getDate(this.todays_date));

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

    }
}
</script>

