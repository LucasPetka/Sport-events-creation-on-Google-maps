<template>
        <div>

            Username: {{ currentUser.name }}<br>
            E-mail: {{ currentUser.email }}  <br>
            Joined: {{ currentUser.created_at }}  <br>

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

