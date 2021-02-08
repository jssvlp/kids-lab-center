<template>
    <ul class="flex justify-center mt-3">
        <li v-for="link in links" :key="link.label" v-on:click.prevent="setCurrent(link)" class="mx-1 px-3 py-2 bg-gray-200 hover:bg-trendy-pink-500 hover:text-gray-200 rounded-lg cursor-pointer"  v-bind:class="{ 'bg-trendy-pink-500': link.active, 'text-gray-200': link.active, 'text-trendy-pink-400' :!link.active}">
            <a  class="font-bold"  href="#">{{getLabel(link.label)}}</a>
        </li>
    </ul>
</template>

<script>
import axios from 'axios'
import NProgress from 'nprogress'
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
            NProgress.start()
            axios.get(page.url)
            .then(data => {
                this.$emit('next',data.data)
                NProgress.done()
            })
        },
        
    },
    computed:{
        
    }

}
</script>

<style>

</style>