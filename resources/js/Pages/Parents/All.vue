<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Padres
            </h2>
        </template>
        <EditOrNew v-if="editingOrCreatingParent" :title="modalTitle" :data="editThisParent"/>
        <EditOrNewChildren v-if="editingOrCreatingChildren" :title="'Nuevo niño/a'" :data="newChild"/>
        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="-my-1 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="mb-3">
                                <button @click="newOrEdit(null,'Nuevo padre/madre')" class="bg-white text-gray-800 font-bold rounded border-b-2 border-green-500 hover:border-green-600 hover:bg-green-500 hover:text-white shadow-lg py-2 px-6 inline-flex items-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                    <span class="mr-2">Agregar</span>
                                </button>
                            </div>
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre completo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Telefono
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Hijos
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                    </tr>
                                </thead>
                                <tbody v-if="parents.length > 0" class="bg-white divide-y divide-gray-200">
                                    <tr  v-for="parent in parents" :key="parent.id" >
                                    <td  class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" :src="(parent.kinship == 'Madre' ? '/Images/mom.png' : '/Images/dad.png')" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                            {{parent.name}}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                            {{ parent.kinship }}
                                            </div>
                                        </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{parent.phone}}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span @click="addChild(parent)" class="bg-green-600  text-white rounded cursor-pointer p-1" >Agregar hijo/a</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex">
                                            <button aria-label="Edit user"
                                                    class="p-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600"
                                                    @click="newOrEdit(parent, 'Editar padre/madre')">
                                                <EditIcon/>
                                            </button>
                                            <button aria-label="Delete user"
                                                    class="p-1 focus:outline-none focus:shadow-outline text-red-500 hover:text-red-600"
                                                    @click="planBeingDeleted = true, toDelete= parent.id">
                                                <Trash2Icon/>
                                            </button>
                                        </div>
                                    </td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Paginacion -->
                    <Pagination :pages="pagination.last_page" :init="1" :end="10"/>
                </div>
            </div>
        </div>
        <jet-confirmation-modal :show="planBeingDeleted" @close="planBeingDeleted = null">
            <template #title>
                Borrar padre/madre
            </template>

            <template #content>
                ¿Estás seguro que deseas borrar este padre?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="planBeingDeleted = null, toDelete = ''">
                    Cancelar
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteParent" >
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
import Modal from '@/Jetstream/Modal'
import { mapState, mapActions} from 'vuex'
import { EditIcon, Trash2Icon,UserPlusIcon } from "vue-feather-icons";
import EditOrNew from './EditOrNew'
import JetConfirmationModal from '@/Jetstream/ConfirmationModal';
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetDangerButton from '@/Jetstream/DangerButton'
import Button from '../../Jetstream/Button.vue'
import EditOrNewChildren from '../Children/EditOrNew'
import axios from 'axios'
export default {
    components: {
        AppLayout,
        Pagination,
        Modal,
        EditIcon,
        Trash2Icon,
        UserPlusIcon,
        EditOrNew,
        JetConfirmationModal,
        JetSecondaryButton,
        JetDangerButton,
        Button,
        EditOrNewChildren
    },
    data: () => ({
        toggleModal: false,
        editingOrCreating: false,
        editThisParent: null,
        modalTitle: '',
        planBeingDeleted: null,
        toDelete: null,
        newChild: null,
        parents: [],
        pagination: {}

    }),
    computed:{
        ...mapState(['editingOrCreatingParent','editingOrCreatingChildren'])
    },
    methods:{
        ...mapActions({
            toggleNewOrEditParentModal: "toggleNewOrEditParentModal",
            toggleNewOrEditChildrenModal: "toggleNewOrEditChildrenModal",
            
        }),
        openModal(){
            this.openInformationModal();
        },
        newOrEdit(parent, title){
            this.editThisParent = parent
            this.modalTitle = title
            this.toggleNewOrEditParentModal()
        },
        deleteParent(){
            this.$inertia.delete(`/parents/${this.toDelete}`)
            .then( (data) =>{
                this.planBeingDeleted = null
            })
        },
        addChild(parent){
            this.newChild = {
                'parent' : parent
            }

            this.toggleNewOrEditChildrenModal();

        },
        getChildren(children){
            var result = '';

            children.forEach(child => {
                if(result == ''){
                    result = child.name
                }
                else{
                    result = result + ', ' + child.name
                }
                 
            });
            return result;
        }
        
    },
    mounted(){
       axios.get('parents/all')
       .then( data =>{
           this.parents = data.data.data
           this.pagination = data.data
           console.log(this.pagination)
       })
    },
    getParents()
    {
        
    }
}
</script>
