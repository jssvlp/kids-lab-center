<template>
    <div>
        <label id="listbox-label" class="block text-sm font-medium text-gray-700">
            Padre/Madre
        </label>
        <div class="mt-1 relative">
            <button type="button" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label" class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <div  class="flex items-center">
                <input v-model="search" @focus="selected = null, search = ''" input type="text" @keyup="filter" class="outline-none mt-1 block w-full ml-3 truncate" placeholder="Buscar">
            </div>
            <span class="ml-3 absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <!-- Heroicon name: selector -->
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </span>
            </button>
    
            <div class="absolute mt-1 w-full rounded-md bg-white shadow-lg">
            <ul v-show="selected == null  " tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-item-3" class="overflow-visible max-h-56 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5  focus:outline-none sm:text-sm">
                <li v-for="parent in filtered" @click="select(parent)" :key="parent.id" id="listbox-item-0" role="option" class="text-gray-900 hover:bg-blue-200 cursor-pointer select-none relative py-2 pl-3 pr-9">
                <div  class="flex items-center" >
                    <img :src="(parent.kinship == 'Madre' ? '/Images/mom.png' : '/Images/dad.png')" alt="" class="flex-shrink-0 h-6 w-6 rounded-full">
                    <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                    <span class="ml-3 block font-normal truncate">
                    {{parent.name}}
                    </span>
                </div>

                <span v-if="selected == parent.id" class="absolute inset-y-0 right-0 flex items-center pr-4">
                    <!-- Heroicon name: check -->
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                </li>

        
            </ul>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import { mapActions } from 'vuex'
export default {
    components:{
        JetLabel,
        JetInput
    },
    data:() => ({
        parents: [],
        search: '',
        filtered: [],
        selected: null
    }),
    methods:{
        ...mapActions([
            'setParentForNewChild'
        ]),
        filter(){
            if(this.search == ''){
                this.filtered = this.parents
            }
            this.filtered = this.parents.filter(parent => parent.name.toLowerCase().includes(this.search.toLowerCase()));
        },
        select(parent){
            this.selected = parent.id
            this.search = parent.name
            this.setParentForNewChild(parent);
        },
    },
    mounted(){
        axios.get('parents/list')
       .then( data =>{
           this.parents = data.data
           this.filtered = this.parents
       })
    }
}
</script>

<style>

</style>