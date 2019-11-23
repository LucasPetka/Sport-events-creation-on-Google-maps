<template>
        <div>

            Username: {{ currentUser.name }}<br>
            E-mail: {{ currentUser.email }}  <br>
            Joined: {{ currentUser.created_at }}  <br>
            Role: <span v-if="currentUser.isAdmin == 1"> Admin</span> <span v-else> User</span><br>

            <a v-if="currentUser.isAdmin == 1" class="btn btn-dark float-right" href="/admin" role="button">Admin panel</a>

        </div>    
</template>

<script>

export default {

    components: {
    
    },
    
    props: ['currentUser'],
    
    data(){
        return{
            user: {
                id:'',
                name:'',
                email:'',
                verif:'',
                created_at:'',
                updated_at:''
            }
        }
    },

    created(){
        this.fetchUser();

    },

    methods: {

        fetchUser() {
            fetch('api/user')
            .then(res => res.json())
            .then(res => {
                this.user = this.currentUser;
            })
    },

    }
}
</script>

