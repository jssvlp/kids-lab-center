<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Vacunas
            </h2>
        </template>
        <EditOrNew v-if="editingOrCreatingVaccine" :title="title" :data="toNewOrEdit" @refresh="search"/>
        <div class="py-2">
            <div class="max-w-2xl mx-auto sm:px-2">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="mb-3 flex justify-between">
                                <button @click="newOrEdit(null,'Nueva vacuna')" class="bg-white text-gray-800 font-bold rounded border-b-2 border-green-500 hover:border-green-600 hover:bg-green-500 hover:text-white shadow-lg py-2 px-6 inline-flex items-center">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>                                    <span class="mr-2">Agregar</span>
                                </button>
                                 <div class="flex">
                                        <span class="text-sm border border-2 rounded-l px-4 py-2 bg-gray-300 whitespace-no-wrap">Buscar:</span>
                                        <input name="field_name" class="border border-2 rounded-r px-2 py-2 w-full"
                                                v-model="filter"
                                                @keyup="search"
                                                type="text" placeholder="Nombre de una vacuna" />
                                </div>
                            </div>
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio
                                </th>
                                
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="">Acciones</span>
                                </th>
                                </tr>
                            </thead>
                            <tbody v-if="vaccines.data" class="bg-white divide-y divide-gray-200">
                                <tr v-for="vaccine in vaccines.data" :key="vaccine.id">
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900   ">
                                            {{vaccine.name}}
                                        </div>
                                        
                                    </div>
                                    </div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">RD$ {{vaccine.price}}</div>
                                    
                                </td>
                                <td class="px-6 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex">
                                        <button aria-label="Edit user"
                                                class="p-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600"
                                                @click="newOrEdit(vaccine,'Editar vacuna')">
                                            <EditIcon size="1.2x"/>
                                        </button>
                                        <button aria-label="Delete user"
                                                class="p-1 focus:outline-none focus:shadow-outline text-red-500 hover:text-red-600"
                                                @click="planBeingDeleted = true, toDelete= vaccine">
                                            <Trash2Icon size="1.2x"/>
                                        </button>
                                    </div>
                                </td>
                                </tr>
                                <!-- More rows... -->
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                     <Pagination  :links="vaccines.links" @next="refresh" />
                    </div>
            </div>
        </div>
        <jet-confirmation-modal :show="planBeingDeleted" @close="planBeingDeleted = null">
            <template #title>
                Borrar Vacuna
            </template>

            <template #content>
                ¿Estás seguro que deseas borrar la vacuna <span class="font-bold"> {{toDelete.name}}</span>?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="planBeingDeleted = null, toDelete = {}">
                    Cancelar
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteVaccine" >
                    Confirmar
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
        
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Pagination from '@/Components/Pagination'
import { mapState, mapActions} from 'vuex'
import { EditIcon, Trash2Icon } from "vue-feather-icons";
import JetConfirmationModal from '@/Jetstream/ConfirmationModal';
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetButton from '@/Jetstream/Button'
import JetDangerButton from '@/Jetstream/DangerButton'
import EditOrNew from './EditOrNew'

import JetDialogModal from '@/Jetstream/DialogModal'
import NProgress from 'nprogress'

export default {
    components: {
        AppLayout,
        Pagination,
        EditIcon,
        Trash2Icon,
        JetConfirmationModal,
        JetSecondaryButton,
        JetButton,
        EditOrNew,
        JetDangerButton,
        JetDialogModal
    },
    data: () =>({
        toNewOrEdit: null,
        toDelete: {},
        title: '',
        planBeingDeleted: null,
        planBeingAdd: null,
        newChild: null,
        title: '',
        vaccines: {},
        filter: ''
    }),
    computed: {
        ...mapState(['editingOrCreatingChild','parentForNewChild','editingOrCreatingVaccine'])
    },
    mounted(){
        NProgress.start()
        axios.get('vaccines/all/paginated')
        .then( data =>{
            this.vaccines = data.data
            NProgress.done()
        })
    },
    methods:{
        ...mapActions({
            toggleNewOrEditVaccineModal : 'toggleNewOrEditVaccineModal'
        }),
        newOrEdit(vaccine, title){
            this.toNewOrEdit = vaccine
            this.modalTitle = title
            console.log(vaccine)
            this.toggleNewOrEditVaccineModal()
        },
        deleteVaccine(vaccine){
            NProgress.start()
            this.$inertia.delete(`/vaccines/${this.toDelete.id}`)
            .then( (data) =>{
                this.planBeingDeleted = null
                NProgress.done()
            })
        },
        refresh(data){
            this.vaccines = data
        },
        search(){
            if(this.filter.length > 2 || this.filter == ''){
                NProgress.start()
                axios.get(`/vaccines/all/paginated?name=${this.filter}`)
                .then(data => {
                    this.vaccines = data.data
                    NProgress.done()
                })
            }
        }
    }
}
</script>
