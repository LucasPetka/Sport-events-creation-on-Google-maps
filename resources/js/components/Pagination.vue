<template>
    <div>

            <div v-for="place in pageOfItems" :key="place.id">{{place.title}}</div>

            <jw-pagination :items="places" @changePage="onChangePage"></jw-pagination>

    </div>
</template>

<script>
// an example array of items to be paged


export default {

    props: ['places'],

    data() {
        return {
            pageOfItems: []
        };
    },

    mounted(){
        console.log(this.places);
    },

    methods: {
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        }
    }
};
</script>