<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Aseguradoras y Planes
            </h2>
        </template>
        <EditOrNew v-if="editingOrCreatingInsurance" :title="title" :data="toEdit" @refresh="search"/>
        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="-my-1  sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle  min-w-full ">
                            <div class="mb-3 flex justify-between">
                                <button @click="editOrNew(null,'Nueva aseguradora')" class="bg-white text-gray-600 font-bold rounded border-b-2 border-trendy-pink-300 hover:border-trendy-pink-600 hover:bg-trendy-pink-400 hover:text-white shadow-lg py-2 px-6 inline-flex items-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>         
                                    <span class="mr-2">Agregar</span>
                                </button>
                                 <div class="flex">
                                        <span class="text-sm border border-2 rounded-l px-4 py-2 bg-gray-300 whitespace-no-wrap">Buscar:</span>
                                        <input name="field_name" class="border border-2 rounded-r px-2 py-2 w-full"
                                                v-model="filter"
                                                @keyup="search"
                                                type="text" placeholder="Nombre de una aseguradora" />
                                </div>
                            </div>
                            <div v-if="insurancesCopy" class="flex flex-wrap mt-6">
                                <div v-for="insurance in insurancesCopy" :key="insurance.id" class="flex-none bg-white rounded-xl md:flex-2 my-3 shadow-lg p-8 transition duration-500 ease-in-out  hover:bg-white-600 transform hover:-translate-y-1 hover:scale-110 mx-3">
                                    <div  class="text-lg font-bold ">
                                        {{insurance.name}}
                                    </div>
                                    <div>
                                        {{insurance.phone}}
                                    </div>
                                    <div class="flex items-center justify-end mt-15 top-auto">
                                        <button @click="planBeingDeleted = true, toDelete = insurance" class="bg-white text-red-orange-500 px-4 py-2 rounded mr-auto hover:underline">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    <button @click="editOrNew(insurance, 'Editar aseguradora')" class=" bg-gray-200 text-gray-600 px-2 py-2 rounded-md ml-4 mr-2">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    </button>
                                    <inertia-link :href="route('plans',insurance.id)">
                                        <button class=" bg-trendy-pink-400 text-gray-200 px-2 py-2 rounded-md " >Planes</button>
                                    </inertia-link>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <jet-confirmation-modal :show="planBeingDeleted" @close="planBeingDeleted = null">
            <template #title>
                Borrar aseguradora
            </template>

            <template #content>
                ¿Estás seguro que deseas borrar la aseguradora <span class="font-bold"> {{toDelete.name}}</span>?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="planBeingDeleted = null, toDelete = {}">
                    Cancelar
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteInsurance" >
                    Confirmar
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Input from '@/Jetstream/Input'
import EditOrNew from './EditOrNew'
import { mapState, mapActions} from 'vuex'
import JetConfirmationModal from '@/Jetstream/ConfirmationModal';
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetDangerButton from '@/Jetstream/DangerButton'
import NProgress from 'nprogress'

export default {
    props: ['insurances'],
    components: {
        AppLayout,
        Input,
        EditOrNew,
        JetConfirmationModal,
        JetSecondaryButton,
        JetDangerButton
    },
    data:() =>({
        toEdit: null,
        toDelete: {},
        title: '',
        planBeingDeleted: null,
        filter: '',
        insurancesCopy: null
    }),
    computed:{
        ...mapState(['editingOrCreatingInsurance'])
    },
    mounted(){
        this.insurancesCopy = this.insurances;
    },
    methods:{
        ...mapActions({
            toggleNewOrEditInsuranceModal: "toggleNewOrEditInsuranceModal"
        }),
        editOrNew(insurance, title){
            this.toEdit = insurance
            this.title = title
            this.toggleNewOrEditInsuranceModal()
        },
        deleteInsurance(insurance){
            NProgress.start()
            /* this.$inertia.delete(`/insurances/${this.toDelete.id}`)
            .then( (data) =>{
                this.planBeingDeleted = null
                NProgress.done()
            }) */

            this.$inertia.delete(`/insurances/${this.toDelete.id}`,{
                onSuccess: () => {
                    this.planBeingDeleted = null
                    
                },
                onError: (errors) => {
                    // Handle validation errors
                },
                onFinish: () => {
                   NProgress.done()
                },
            })
            
        },
         search(){
            if(this.filter.length > 2 || this.filter == ''){
                NProgress.start()
                axios.get(`/insurances/all?name=${this.filter}`)
                .then(data => {
                    this.insurancesCopy = data.data
                    NProgress.done()
                })
            }
        }
      
    }
}
</script>
