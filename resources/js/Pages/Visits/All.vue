<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Visitas
            </h2>
        </template>
        <div class="py-2">
            <div class="max-w-4xl mx-auto sm:px-2">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <inertia-link :href="route('visits.new')" class="mb-3">
                                <button  class="bg-white text-gray-800 font-bold rounded border-b-2 border-green-500 hover:border-green-600 hover:bg-green-500 hover:text-white shadow-lg py-2 px-6 inline-flex items-center">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>                                    <span class="mr-2">Iniciar</span>
                                </button>
                            </inertia-link>
                        <div class="mt-2 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Paciente
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vacunas colocadas
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Facturado
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="">Acciones</span>
                                </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="visit in visits" :key="visit.id">
                                <td class="px-6 py-3 whitespace-nowrap ">
                                    <inertia-link :href="route('visits.show',visit.id)" class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900   ">
                                            {{ visit.child.name}}
                                        </div>
                                    </div>
                                    </inertia-link>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <inertia-link :href="route('visits.show',visit.id)" class="text-sm text-gray-900" >
                                        {{visit.visit_date}}
                                    </inertia-link>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                       {{visit.vaccines.length}}
                                    </div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                       {{ (visit.invoced == true ? 'Sí' : "No")}}
                                    </div>
                                </td>
                                <td class="px-6 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex">
                                        <button aria-label="Edit user"
                                                class="p-1 focus:outline-none focus:shadow-outline text-yellow-500 hover:text-yellow-600"
                                                @click="newOrEdit(visit,'Editar vacuna')">
                                            <EditIcon size="1.2x"/>
                                        </button>
                                        <button aria-label="Delete user"
                                                class="p-1 focus:outline-none focus:shadow-outline text-red-500 hover:text-red-600"
                                                @click="planBeingDeleted = true, toDelete= visit">
                                            <Trash2Icon size="1.2x"/>
                                        </button>
                                        <button aria-label="Facturar"
                                                class="p-1 focus:outline-none focus:shadow-outline text-green-500 hover:text-green-600"
                                                @click="alert('Enviar a facturacion')">
                                            <DollarSignIcon size="1.2x"/>
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
                    <Pagination :pages="1" :init="1" :end="1"/>
                    </div>
            </div>
        </div>
        <jet-confirmation-modal :show="planBeingDeleted" @close="planBeingDeleted = null">
            <template #title>
                Borrar Vacuna
            </template>

            <template #content>
                ¿Estás seguro que deseas borrar la visita <span class="font-bold"> {{toDelete.name}}</span>?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="planBeingDeleted = null, toDelete = {}">
                    Cancelar
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteVisit" >
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
import { EditIcon, Trash2Icon,DollarSignIcon } from "vue-feather-icons";
import JetConfirmationModal from '@/Jetstream/ConfirmationModal';
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetButton from '@/Jetstream/Button'
import JetDangerButton from '@/Jetstream/DangerButton'


import JetDialogModal from '@/Jetstream/DialogModal'
import JetInputError from '@/Jetstream/InputError'

export default {
    props:['visits'],
    components: {
        AppLayout,
        Pagination,
        EditIcon,
        Trash2Icon,
        DollarSignIcon,
        JetConfirmationModal,
        JetSecondaryButton,
        JetButton,
        JetDangerButton,
        JetDialogModal
    },
    data: () =>({
        planBeingDeleted: null,
        title: '',
        toDelete: {}
    }),
    computed: {
        
    },
    created(){
       
    },
    methods:{
        ...mapActions({
            
        }),
        newOrEdit(visit, title){
            this.toNewOrEdit = visit
            this.modalTitle = title
            this.toggleNewOrEditVisitModal()
        },
        deleteVisit(){
            this.$inertia.delete(`/visits/${this.toDelete.id}`)
            .then( (data) =>{
                this.planBeingDeleted = null
            })
        }
    }
}
</script>
