<template>

<div>

    <notifications group="foo" classes="my-style" position="top left" style="margin-top:55px;" />

<!-- People going MODAL -->
<div class="modal fade" id="people_going" tabindex="-1" role="dialog" aria-labelledby="people_goingTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">People Going</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <ul class="list-group list-group-flush">
            <li class="list-group-item" v-for="user in people_going" :key="user.id">
                <a class="nav-link m-0 p-0" target="_blank" :href="'/user/' + user.person_id.auth_id">
                    <span v-if="user.person_id.provider == null">
                        <img class="rounded-circle" :src="'../../../images/avatars/'+user.person_id.avatar" width="30" height="30" alt="">
                    </span>
                    <span v-else>
                        <img class="rounded-circle" :src="user.person_id.avatar" width="30" height="30" alt="">
                    </span>
                    {{ user.person_id.name }} 
                </a> 
            </li>
        </ul>

      </div>
    </div>
  </div>
</div>


<div class="row mb-3">
    <div class="col-6">
    <b> <a href="#" data-toggle="modal" data-target="#people_going"> <i class="fas fa-user"></i> {{ people_going.length }} people going </a></b>
    </div>
    <div class="col-6">
        <editevent :user="user" v-on:fetchCreatedEvents="refresh" :acceptedOrDeclined="false" :event="event"></editevent>
        <div v-if="ifJoined(event.place_id, event.id, user.id) == 0">
            <button id="join_btn" type="button" class="btn btn-success float-right" :disabled="isLoading" v-on:click="addPerson(event.place_id, event.id, user.id, $event)"><i class="fas fa-user-plus"></i> Join</button>
        </div>
        <div v-else>
            <button type="button" class="btn btn-secondary float-right" :disabled="isLoading" v-on:click="deletePerson(event.place_id, event.id, user.id, $event)"><i class="fas fa-check"></i> Joined</button>
        </div>
    </div>
</div>


<div class="row">

    <div class="col-lg-8 mb-3">
        <div class="card card-default">
            <div class="card-header">Messages</div>
            <div class="card-body p-0">
                <ul class="list-unstyled" id="message_list" style="height:300px; overflow-y:scroll" v-chat-scroll="{smooth: true}">
                    <li class="p-2" v-for="message in messages"  :key="message.id">
                        
                        <span v-if="message.user.provider == null">
                            <img class="rounded-circle" :src="'../../../images/avatars/'+message.user.avatar" width="30" height="30" alt="">
                        </span>
                        <span v-else>
                            <img class="rounded-circle" :src="message.user.avatar" width="30" height="30" alt="">
                        </span>
    
                        <strong>{{ message.user.name }}</strong>
                        {{ message.message }}
                    </li>
                </ul>
            </div>

            <input @keydown="sendTyping" @keyup.enter="sendMessage" v-model="newMessage" type="text" name="message" placeholder="Enter your message..." class="form-control">
        </div>

        <span class="text-muted" v-if="activeUser">{{ activeUser.name }} is typing...</span>
    </div>

    <div class="col-lg-4">
        <div class="card card-default">
            <div class="card-header">Users online</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" v-for="user in users" :key="user.id"> {{ user.name }} </li>
                </ul>
            </div>
        </div>
    </div>

</div>
</div>
    
</template>

<script>
const editevent = () => import("../components/EditEvent.vue");

export default {
    components: {
        'editevent': editevent,
    },

    props:['user', 'event'],

    data(){
        return{
            messages: [],
            newMessage: '',
            users: [],
            isLoading:false,
            people_going: [],
            activeUser: false,
            typeTime: false,
            person: {
                place_id:'',
                event_id:'',
            },
        }
    },


    created(){

        this.fetchPeopleGoing();
        this.fetchMessages();
        Echo.join('event.' + this.event.id)
            .here(user => {
                this.users = user;
            })
            .joining(user => {
                this.users.push(user);
            })
            .leaving(user => {
                this.users = this.users.filter(u => u.id != user.id);
            })
            .listen('MessageSent', (event) => {
                this.messages.push(event.message);
            })
            .listenForWhisper('type', user => {
                this.activeUser = user;

                if(this.typeTime){
                    clearTimeout(this.typeTime)
                }

                this.typeTime = setTimeout(() => {
                    this.activeUser = false;
                }, 3000)
            })
    },


    methods: {

        fetchMessages(){
            axios.get('../messages/' + this.event.id).then(response =>{
                this.messages = response.data;
            })
        },

        refresh(){
            window.location.reload();
        },

        sendMessage(){
            this.messages.push({
                user: this.user,
                message: this.newMessage
            })
            axios.post('../messages', {message: this.newMessage, event_id: this.event.id });
            this.newMessage = '';
        },

        sendTyping(){
            Echo.join('event.' + this.event.id)
                .whisper('type', this.user);
        },

        fetchPeopleGoing() {
                fetch('../api/people_going/'+ this.event.id)
                .then(res => res.json())
                .then(res => {
                    this.people_going = res.data;

                    return this.people_going.length;
                })
            
        },

        getCookie(name) {
            var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return v ? v[2] : null;
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


        //Add person to the event
        addPerson(place, event, person, but) {
            
            this.isLoading = true
            setTimeout(() => {
                this.isLoading = false
            }, 2000);

            this.person.place_id = place;
            this.person.event_id = event;
        
            fetch('../api/person?api_token=' + this.getCookie("api_token"), {
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
         
            fetch('../api/person/'+ id + '?api_token=' + this.getCookie("api_token"), {
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