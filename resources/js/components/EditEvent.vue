<template>

<div>

    <!-- ==============================Confirmation MODAL============================================== -->
    <div class="modal fade" :id="'confirmation_modal' + event.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-body">
            <p class="h5 text-center mb-0 pb-0">Are you sure you want to delete this event?</p><br>
            <p class="text-muted text-center mb-0 pb-0"><small> {{ event_mod.title }} </small></p>
        </div>
        <div class="modal-footer justify-content-center">
            <button v-if="acceptedOrDeclined == false" type="button" class="btn btn-danger" v-on:click="deleteEvent(event_mod.id)">Delete</button>
            <button v-else type="button" class="btn btn-danger" v-on:click="deleteDeclinedEvent(event_mod.id)">Delete</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
        </div>
    </div>
    </div>

    <notifications group="foo" classes="my-style" position="top left" style="margin-top:55px;" />

    
        <button dusk="edit_event_btn" type="button" class="btn btn-primary float-right ml-3 mb-2" v-on:click="editEvent(event.id)"> <i class="fas fa-edit"></i> </button>
        <button type="button" class="btn btn-danger float-right ml-3 mb-2" style="width:42.2px" v-on:click="openConfirmation(event)"> <i class="fas fa-trash-alt"></i></button>


    <!-- ==============================Edit MODAL============================================== -->
    <form @submit.prevent="addEvent">
            <div class="modal fade" :id="'addEvent'+event.id" tabindex="-1" role="dialog" aria-labelledby="addEventCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 v-if="acceptedOrDeclined" class="modal-title" id="addEventLongTitle">Re-submit Event</h5>
                            <h5 v-else class="modal-title" id="addEventLongTitle">Edit Event</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="edit_event_title">Title</label>
                                <input name="edit_event_title" v-model="event_for_sending.title" type="text" class="form-control" id="edit_event_title" maxlength="45" aria-describedby="emailHelp" placeholder="Enter title" required>
                            </div>

                            <div class="form-group">
                                <label for="edit_event_about">About</label>
                                <textarea name="edit_event_about" v-model="event_for_sending.about" class="form-control" id="edit_event_about" maxlength="350" rows="6" required></textarea>
                            </div>

                        <div class="row">
                            <ul class="list-group list-group-horizontal mb-5 mx-auto">
                                <li class="list-group-item"><i class="far fa-calendar-alt"></i> {{ date }}</li>
                                <li class="list-group-item"> {{ getWeekDay(event_for_sending.time_from) }} </li>
                                <li class="list-group-item"><i class="far fa-clock"></i> {{ event_time[0] }} -  {{ event_time[1] }}</li>
                            </ul>
                        </div>
                               
                            <vue-slider :tooltip-style="{ backgroundColor: '#313638', borderColor: '#313638' }"  :adsorb="true" v-if="event_time != ''" :process="process" :tooltip="'always'" v-on:drag-end="parseDate(event.id)" class="mr-3 ml-3 mb-5" v-model="event_time" :data="data" :marks="marks" :enable-cross="false">
                                 <template v-slot:dot="{ value, focus }">
                                    <div :class="['custom-dot', { focus }]"></div>
                                </template>
                            </vue-slider>

                            <div class="row">
                                <div :id="'time_error'+event.id" class="mx-auto"></div>
                            </div>
                            <div class="row">
                                <div :id="'time_error_second'+event.id" class="mx-auto"></div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button v-if="acceptedOrDeclined" :id="'add_event_btn'  + event.id" v-on:click="resubmitEvent" class="btn btn-success float-right" :disabled="isLoading"> Re-submit </button>
                            <button dusk="edit_event_submit_btn" v-else :id="'add_event_btn'  + event.id" v-on:click="addEvent" class="btn btn-success float-right" :disabled="isLoading"> Update </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


</div>
    
</template>

<script>

export default {

    props:['event', 'user', 'acceptedOrDeclined'],

    data(){
        return{
            weekDays:['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday',],
            isLoading: false,
            process: dotsPos => [],
            events: [],
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
            event_time:['', ''],
            event_for_sending:[],
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
            date:'',
            person: {
                place_id:'',
                event_id:'',
            },
        }
    },


    created(){
        this.fillArrayWithTimes();   
        this.event_for_sending = this.event;
    },

    mounted(){

        $("[id^='addEvent']").on('hide.bs.modal', (e) => {
           $("[id^='time_error']").html("");
           $("[id^='time_error_second']").html("");
        });

    },


    methods: {

        openConfirmation: function(event){
            this.event_mod = event;
            $('#confirmation_modal' + event.id).appendTo("body").modal('show');
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

        //------------------------Opens edit event creation label-------------------
        async editEvent(id){

            var response = [];

            if(this.acceptedOrDeclined == false){
                response = await axios.get('../../api/event/'+ id);
            }
            else{
                response = await axios.get('../../api/declinedevent/'+ id);
            }

            var even = response.data.data;

            this.event_for_sending = even;
            var d = new Date(even.time_from);
            var dat = new Date(even.time_until);

            var dateee = ('0'+d.getHours()).slice(-2) +":"+ ('0'+d.getMinutes()).slice(-2);
            this.event_time[0] = dateee;
            var dateee = ('0'+dat.getHours()).slice(-2) +":"+ ('0'+dat.getMinutes()).slice(-2);
            this.event_time[1] = dateee;

            this.event_time = [this.event_time[0], this.event_time[1]];
            this.getDate(d);

            this.fetchEvents(even.place_id, id, this.date);

            $('#addEvent'+this.event.id).appendTo("body").modal('show');
            
        },

        getDate: function(date){
            var d = new Date(date);
            this.date =  d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
        },

        getWeekDay(date){
            let current_datetime = new Date(date);

            return this.weekDays[current_datetime.getDay()];
        },

        getCookie(name) {
            var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return v ? v[2] : null;
        },

        //-----------------Closes event creation label---------------------------
        closeAddEvent: function(){
            $('#addEvent'+this.event.id).modal('hide');
        },

        fetchEvents(id, event_id, date){
            fetch('../../api/events/' + id + '/' + event_id + '/' + date)
            .then(res => res.json())
            .then(res => {

                var processArray = [];
                res.forEach(event => {
                    var start_time = this.getTime(event.time_from);
                    var end_time = this.getTime(event.time_until);
                    var first = this.data.indexOf(start_time) * 2.85714285714;
                    var second = this.data.indexOf(end_time) * 2.85714285714;
                    processArray.push([first, second, { backgroundColor: 'red' }]);
                });

                this.process = (dotsPos => processArray);
            })
        },


        getTime(date){
            let current_datetime = new Date(date)
            let formatted_Time = ('0' + current_datetime.getHours()).slice(-2) + ":" + ('0' + current_datetime.getMinutes()).slice(-2)
            return formatted_Time;
        },

        //---------------------Parse data in right format to choose when event starts and when ends--------------------
        parseDate(event_id){

            if(this.event_time[0] == this.event_time[1]){
                $("#time_error" + event_id).html("<span class='text-danger'><small>Times should not be equal!</small></span>");
                $("#add_event_btn"  + event_id).attr("disabled", true);
            }
            else{
                $("#time_error" + event_id).html("");
                $('#add_event_btn'  + event_id).removeAttr("disabled");
            }

            $.ajax({
                type: "GET",
                url: "/validate_time?start="+ this.date +" "+ this.event_time[0]+"&end="+ this.date +" "+ this.event_time[1]+"&place_id="+this.event.place_id+"&event_id="+this.event.id,     
                success: function(result){

                    console.log(result.found);

                    if(!result.found){
                        $("#time_error_second" + event_id).html("");
                        $('#add_event_btn').removeAttr("disabled");

                    }  
                    else{
                        $("#time_error_second" + event_id).html("<span class='text-danger'><small>Time overlaping with other events</small></span>");
                        $("#add_event_btn"  + event_id).attr("disabled", true);
                    }
                    
                }
            });


        },

        //-----------------------------------------Create new event-----------------------------------------------
        addEvent() {

            this.isLoading = true
            setTimeout(() => {
                this.isLoading = false
            }, 2000);

            this.event_for_sending.time_from = this.date + " " + this.event_time[0];
            this.event_for_sending.time_until = this.date + " " + this.event_time[1];

            (async () => {
                const Response = await fetch('../../api/event?api_token=' + this.getCookie("api_token"), {
                    method: 'put',
                        body: JSON.stringify(this.event_for_sending),
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
                    text: 'You have updated an event !'
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

            

            $('#addEvent'+this.event_for_sending.id).modal('hide');

            this.$emit('fetch');
            
            this.event_for_sending.id= '';
            this.event_for_sending.place_id = '';
            this.event_for_sending.title = '';
            this.event_for_sending.about = '';
            this.event_for_sending.time_from = '';
            this.event_for_sending.time_until = '';
            this.event_for_sending.person_id = '';
        },

        deleteDeclinedEvent(event_id){
            (async () => {
            const Response = await fetch('/decevent/' + event_id, {
                method: 'delete',
                    headers: {
                        'Accept': 'application/json',
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }     
            });

                const content = await Response.json();

                if(content == true){
                Vue.notify({
                    group: 'foo',
                    title: 'Congrats!!',
                    type: 'success',
                    text: 'Event have been deleted !'
                });  
                }
                else{
                    Vue.notify({
                        group: 'foo',
                        title: 'Error!!',
                        type: 'error',
                        text: 'Unable to delete event..'
                    });  
                }
                
                $('#confirmation_modal' + event_id).modal('hide');

                this.$emit('fetch');
            })();

        },

         //Delete event from database
        deleteEvent: function(eventId) {

            (async () => {
            const Response = await fetch('../../api/event/'+ eventId + '?api_token=' + this.getCookie("api_token"), {
                method: 'delete',
                    headers: {
                        'Accept': 'application/json',
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }     
            });

                const content = await Response.json();

                if(content == true){
                Vue.notify({
                    group: 'foo',
                    title: 'Congrats!!',
                    type: 'success',
                    text: 'Event have been deleted !'
                });  
                }
                else{
                    Vue.notify({
                        group: 'foo',
                        title: 'Error!!',
                        type: 'error',
                        text: 'Unable to delete event..'
                    });  
                }


                this.$emit('fetch');
                $('#confirmation_modal' + eventId).modal('hide');

            })();


        },

        //-----------------------------------------Re-submit new event-----------------------------------------------
        resubmitEvent() {

            this.isLoading = true
            setTimeout(() => {
                this.isLoading = false
            }, 2000);

            this.event_for_sending.time_from = this.date + " " + this.event_time[0];
            this.event_for_sending.time_until = this.date + " " + this.event_time[1];

            (async () => {
                const Response = await fetch('../resubmit_event', {
                    method: 'post',
                        body: JSON.stringify(this.event_for_sending),
                        headers: {
                            'Accept': 'application/json',
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }     
            });

            const content = await Response.json();

            if(content == true){
                Vue.notify({
                    group: 'foo',
                    title: 'Congrats!!',
                    type: 'success',
                    text: 'You have re-submited an event, wait for confirmation.'
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

            $('#addEvent'+this.event_for_sending.id).modal('hide');

            this.$emit('fetch');
            
            this.event_for_sending.id= '';
            this.event_for_sending.place_id = '';
            this.event_for_sending.title = '';
            this.event_for_sending.about = '';
            this.event_for_sending.time_from = '';
            this.event_for_sending.time_until = '';
            this.event_for_sending.person_id = '';
        },


    }



}
</script>

<style>

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

body.modal-open {
    overflow: visible;
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

</style>