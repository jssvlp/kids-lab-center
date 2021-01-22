<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Padres
            </h2>
        </template>
        <EditOrNew v-if="editingOrCreatingParent" :title="modalTitle" :data="editThisParent" @refresh="search"/>
        <EditOrNewChildren v-if="editingOrCreatingChild" :title="'Nuevo niño/a'" :data="newChild"/>
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="mb-3 flex justify-between">
                                <button @click="newOrEdit(null,'Nuevo padre/madre')" class="bg-white text-gray-800 font-bold rounded border-b-2 border-green-500 hover:border-green-600 hover:bg-green-500 hover:text-white shadow-lg py-2 px-6 inline-flex items-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                    <span class="mr-2">Agregar</span>
                                </button>
                                <div>
                                    <div class="flex">
                                        <span class="text-sm border border-2 rounded-l px-4 py-2 bg-gray-300 whitespace-no-wrap">Buscar:</span>
                                        <input name="field_name" class="border border-2 rounded-r px-4 py-2 w-full"
                                                v-model="filter"
                                                @keyup="search"
                                                 type="text" placeholder="Escribe un nombre" />
                                    </div>
                                </div>
                            </div>
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                    <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre completo
                                    </th>
                                    <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Telefono
                                    </th>
                                    <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Hijos
                                    </th>
                                    <th scope="col" class="relative px-6 py-2">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                    </tr>
                                </thead>
                                <tbody v-if="parents.data" class="bg-white divide-y divide-gray-200">
                                    <tr  v-for="parent in parents.data" :key="parent.id" >
                                    <td  class="px-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                        <div class="flex-shrink-0 h-7 w-7">
                                            <img class="h-7 w-7 rounded-full" :src="(parent.kinship == 'Madre' ? '/Images/mom.png' : '/Images/dad.png')" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 font-bold">
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
                                        <span @click="addChild(parent)" class="bg-green-600  text-white rounded cursor-pointer p-1" >Agregar</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex">
                                            <button aria-label="Edit user"
                                                    class="p-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600"
                                                    @click="newOrEdit(parent, 'Editar padre/madre')">
                                                <EditIcon size="1.2x"/>
                                            </button>
                                            <button aria-label="Delete user"
                                                    class="p-1 focus:outline-none focus:shadow-outline text-red-500 hover:text-red-600"
                                                    @click="planBeingDeleted = true, toDelete= parent">
                                                <Trash2Icon size="1.2x"/>
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
                    <Pagination  :links="parents.links" @next="refresh" />
                </div>
            </div>
        </div>
        <jet-confirmation-modal :show="planBeingDeleted" @close="planBeingDeleted = null">
            <template #title>
                Borrar padre/madre
            </template>

            <template #content>
                ¿Estás seguro que deseas borrar a <span class="font-bold">{{toDelete.name}}</span>?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="planBeingDeleted = null, toDelete = {}">
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
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import Button from '../../Jetstream/Button.vue'
import EditOrNewChildren from '../Children/EditOrNew'
import axios from 'axios'
import Input from '../../Jetstream/Input.vue'
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
        EditOrNewChildren,
        JetInput,
        JetLabel,
        Input
    },
    data: () => ({
        toggleModal: false,
        editingOrCreating: false,
        editThisParent: null,
        modalTitle: '',
        planBeingDeleted: null,
        toDelete: {},
        newChild: null,
        parents: {},
        filter: ''

    }),
    computed:{
        ...mapState(['editingOrCreatingParent','editingOrCreatingChild'])
    },
    methods:{
        ...mapActions({
            toggleNewOrEditParentModal: "toggleNewOrEditParentModal",
            toggleNewOrEditChildModal: "toggleNewOrEditChildModal",
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
            this.$inertia.delete(`/parents/${this.toDelete.id}`)
            .then( (data) =>{
                this.planBeingDeleted = null
            })
        },
        addChild(parent){
            this.newChild = {
                'id': null,
                'dad_or_mom' : parent
            }
            this.toggleNewOrEditChildModal();
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
        },
        refresh(data){
            this.parents = data
        },
        search(){
            console.log(this.filter)
            axios.get(`/parents/all?name=${this.filter}`)
            .then(data => {
                this.parents = data.data
            })
        }
        
    },
    mounted(){
       axios.get('parents/all')
       .then( data =>{
           this.parents = data.data
       })
    },
    getParents()
    {
        
    }
}
</script>
