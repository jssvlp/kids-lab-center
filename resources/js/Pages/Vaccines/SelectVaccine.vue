<template>
    <div>
        <label id="listbox-label" class="block text-sm font-medium text-gray-700">
            Vacunas
        </label>
        <div class="mt-1 relative">
            <div class="flex items-center">
                <button type="button" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label" class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div  class="flex items-center">
                        <input v-model="search" ref="search" @focus="setFocusOnInput"  input type="text" @keyup="filter" class="outline-none mt-1 block w-full ml-3 truncate" placeholder="Buscar">
                    </div>
                    <span class="ml-3 absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <!-- Heroicon name: selector -->
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>
                <button class="ml-4 inline-flex items-center h-10 px-5 text-indigo-100 transition-colors duration-150 bg-trendy-pink-500 rounded-lg focus:shadow-outline hover:bg-indigo-800" @click="push(selected)">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            
            
            <div class="absolute mt-1 w-full rounded-md bg-gray-200 shadow-lg">
            <ul v-show="selected == null && showVaccinesList == true " tabindex="-1"  role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-item-3" class="overflow-visible  rounded-md py-1 text-base ring-1 ring-black ring-opacity-5  focus:outline-none sm:text-sm">
                <li v-for="vaccine in dataFiltered" @click="select(vaccine)" :key="vaccine.id" id="listbox-item-0" role="option" class="text-gray-900 hover:bg-blue-200 cursor-pointer select-none relative py-2 pl-3 pr-9">
                <div  class="flex items-center" >
                   <div>
                        <span class="ml-3 block font-normal truncate">
                        {{vaccine.name}}
                        </span>
                        <span class="font-bold ml-3 block  truncate">RD${{vaccine.price}}</span>
                   </div>
                </div>

                <span v-if="selected == vaccine.id" class="absolute inset-y-0 right-0 flex items-center pr-4">
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
import JetButton from '@/Jetstream/Button'
import { mapActions } from 'vuex'
export default {
    props:['added','showVaccinesList'],
    components:{
        JetLabel,
        JetInput,
        JetButton
    },
    data:() => ({
        vaccines: [],
        search: '',
        filtered: [],
        selected: null,
        focused: false,
    }),
    computed:{
        dataFiltered(){
            return this.filter()
        }
    },
    methods:{
        ...mapActions([
            'setChildForNewVisit',
            'pushToVaccineList'
        ]),
        setFocusOnInput(){
            this.selected = null
            this.search = ''
            this.$emit('showOptions');
        },
        filter(){
            if(this.search == ''){
                this.filtered = this.vaccines
            }
            let matches = this.vaccines.filter(vaccine => vaccine.name.toLowerCase().includes(this.search.toLowerCase()));
            
            this.filtered = matches;
            
            if(this.added.length > 0){
                let addedOnlyNames = this.added.map( a => a.name);
                let notAdded = matches.filter(vaccine => !addedOnlyNames.includes(vaccine.name))
                this.filtered = notAdded;
            }
            return this.filtered
        },
        select(vaccine){
            this.selected = vaccine.id
            this.search = vaccine.name
            this.setChildForNewVisit(vaccine);
        },
        push(){
            this.$emit('pushed',this.selected)
            this.selected = null
            this.search = ''
            this.setFocusOnInput();
            //this.$emit('setFocusOnInput');
        }
       
    },
    mounted(){
        axios.get('/vaccines/list')
       .then( data =>{
           this.vaccines = data.data
           this.filtered = this.vaccines
           this.filter();
       })
    }
}
</script>

<style>

</style>