<template>
    <div>
        <label id="listbox-label" class="block text-sm font-medium text-gray-700">
            Plan Ars
        </label>
        <div class="mt-1 relative">
            <button type="button" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label" class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <div  class="flex items-center">
                <input v-model="search"  @focus="onFocus" input type="text" @keyup="filter" class="outline-none mt-1 block w-full ml-3 truncate" placeholder="Buscar">
            </div>
            <span class="ml-3 absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <!-- Heroicon name: selector -->
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </span>
            </button>
    
            <div class="absolute mt-1 w-full rounded-md bg-white shadow-lg">
            <ul v-show="selected == null && showList == true" tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-item-3" class="overflow-visible max-h-56 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5  focus:outline-none sm:text-sm">
                <li v-for="plan in filtered" v-on:click="select(plan)" :key="plan.id" id="listbox-item-0" role="option" class="text-gray-900 hover:bg-blue-200 cursor-pointer select-none relative py-2 pl-3 pr-9">
                <div  class="flex items-center" >
                    <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                    <span class="ml-3 block  truncate font-bold">
                    {{plan.name}}
                    </span>
                </div>
                <div  class="flex items-center" >
                    <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                    <span class="ml-3 block  truncate font-normal">
                    {{plan.ars}}
                    </span>
                </div>

                <span v-if="selected == plan.id" class="absolute inset-y-0 right-0 flex items-center pr-4">
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
import { mapActions, mapState } from 'vuex'
export default {
    props:['selectedForEdition','showList'],
    components:{
        JetLabel,
        JetInput
    },
    data:() => ({
        plans: [],
        search: '',
        filtered: [],
        selected: null,
        selectCached: null
    }),
    created(){
        if(this.planForEditOrNewChild != null){
            this.selected = this.planForEditOrNewChild.id
            this.search = this.planForEditOrNewChild.name
        } 
    },
    computed:{
        ...mapState(['planForEditOrNewChild'])
    },
    methods:{
        ...mapActions([
            'setPlanForEditOrNewChild'
        ]),
        filter(){
            if(this.search == ''){
                this.filtered = this.plans
            }else{
                const byPlans = this.plans.filter(plan => plan.name.normalize('NFD').replace(/[\u0300-\u036f]/g,"").toLowerCase().includes(this.search.toLowerCase()));
                const byArs   =  this.plans.filter(plan => plan.ars.normalize('NFD').replace(/[\u0300-\u036f]/g,"").toLowerCase().includes(this.search.toLowerCase()));
                const all     = byPlans.concat(byArs)
                const uniques = all.filter((item, pos) => all.indexOf(item) === pos)
                this.filtered = uniques
            }
        },
        select(plan){
            console.log(plan)
            this.selected = plan.id
            this.search = plan.name
            this.listVisible = false
            this.setPlanForEditOrNewChild(plan);
            this.$emit('selected');
        },
        hideList(){
            if(this.search == '' && !this.selected){
                this.selected = this.selectCached
                this.search = this.plans[this.selected].name;
            }
        },
        onFocus(){
            this.search = ''
            this.listVisible = true
            this.selectCached = this.selected
            this.selected = null
            this.$emit('onFocus')
        }
    },
    mounted(){
        axios.get('plans/list')
       .then( data =>{
           this.plans = data.data
           this.filtered = this.plans
       })
    }
}
</script>

<style>

</style>