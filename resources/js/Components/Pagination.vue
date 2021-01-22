<template>
    <ul class="flex justify-center mt-3">
        <li v-for="link in links" :key="link.label" v-on:click.prevent="setCurrent(link)" class="mx-1 px-3 py-2 bg-gray-200 hover:bg-gray-700 hover:text-gray-200 rounded-lg cursor-pointer"  v-bind:class="{ 'bg-gray-700': link.active, 'text-gray-200': link.active, 'text-gray-700' :!link.active}">
            <a  class="font-bold"  href="#">{{getLabel(link.label)}}</a>
        </li>
    </ul>
</template>

<script>
import axios from 'axios'
export default {
    props:["links"],
    data: () =>({
        current: null
    }),

    methods: {
        getLabel(label){
            if(label == '&laquo; Previous'){
                return 'Anterior'
            }else if( label =='Next &raquo;'){
                return 'Siguiente'
            }else{
                return label
            }
        },
        setCurrent(page){
            axios.get(page.url)
            .then(data => {
                this.$emit('next',data.data)
            })
        },
        
    },
    computed:{
        
    }

}
</script>

<style>

</style>