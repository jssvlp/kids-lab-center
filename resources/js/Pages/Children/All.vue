<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Niños/as
            </h2>
        </template>
        <EditOrNew v-if="editingOrCreatingChild" :title="title" :data="toNewOrEdit" @refresh="search"/>
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="mb-3 flex justify-between">
                                <button @click="planBeingAdd = true" class="bg-white text-gray-600 font-bold rounded border-b-2 border-trendy-pink-300 hover:border-trendy-pink-600 hover:bg-trendy-pink-400 hover:text-white shadow-lg py-2 px-6 inline-flex items-center">
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
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Papi/Mami
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sexo
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha de nacimiento
                                </th>
                                <th scope="col" class="relative px-6 py-2">
                                    <span class="sr-only">Edit</span>
                                </th>
                                </tr>
                            </thead>
                            <tbody  v-if="children.data" class="bg-white divide-y divide-gray-200">
                                <tr v-for="child in children.data" :key="child.id">
                                <td class="px-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                    <div class="flex-shrink-0 h-7 w-7">
                                        <img class="h-7 w-7 rounded-full" :src="child.gender == 'Niño' ?'/Images/boy.svg':'/Images/girl.svg'" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 font-bold">
                                            {{child.name}}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{child.age}}
                                        </div>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{child.dad_or_mom.name}}</div>
                                    
                                </td>
                                <td class="px-6  whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{child.gender}}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{child.birth_date | formatLargeDate}}
                                </td>
                                <td class="px-6 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex">
                                        <button aria-label="Edit user"
                                                class="p-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600"
                                                @click="editChild(child)">
                                            <EditIcon size="1.2x"/>
                                        </button>
                                        <button aria-label="Delete user"
                                                class="p-1 focus:outline-none focus:shadow-outline text-red-500 hover:text-red-600"
                                                @click="planBeingDeleted = true, toDelete= child">
                                            <Trash2Icon size="1.2x"/>
                                        </button>
                                        <inertia-link :href="route('children.history',child.id)"  class="bg-trendy-pink-200 px-2 py-1 rounded" v-if="child.visits.length > 0">
                                            Historial
                                        </inertia-link>
                                    </div>
                                </td>
                                </tr>
                                <!-- More rows... -->
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                     <Pagination  :links="children.links" @next="refresh" />
                    </div>
            </div>
        </div>
        <jet-confirmation-modal :show="planBeingDeleted" @close="planBeingDeleted = null">
            <template #title>
                Borrar Paciente
            </template>

            <template #content>
                ¿Estás seguro que deseas borrar el paciente <span class="font-bold"> {{toDelete.name}}</span>? 
                Si borras este paciente se borrán las visitas y demás datos relacionados al registro.
            </template>

            <template #footer>
                <jet-secondary-button @click.native="planBeingDeleted = null, toDelete = {}">
                    Cancelar
                </jet-secondary-button>

                <jet-danger-button class="ml-2 b-red-orange-500" @click.native="deleteChild" >
                    Confirmar
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
        <jet-dialog-modal :show="planBeingAdd" @close="planBeingAdd = null">
            <template #title>
                Primero seleccione un padre/madre
            </template>

            <template #content>
                <SelectParentBefore  />
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="planBeingAdd = null">
                    Cancelar
                </jet-secondary-button>

                <jet-button class="ml-2 bg-trendy-pink-400" @click.native="addChild()" >
                    Confirmar
                </jet-button>
            </template>
        </jet-dialog-modal>
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
import SelectParentBefore from './SelectParentBefore'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetInputError from '@/Jetstream/InputError'
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
        SelectParentBefore,
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
        test: false,
        children: {},
        filter:''
        
    }),
    computed: {
        ...mapState(['editingOrCreatingChild','parentForNewChild'])
    },
    mounted(){
        NProgress.start();
        axios.get('children/all')
        .then( data =>{
            this.children = data.data
            NProgress.done()
        })
    },
    methods:{
        ...mapActions({
            toggleNewOrEditChildModal: "toggleNewOrEditChildModal"
        }),
        editChild(child){
            if(child.plan == null){
                child.plan = {
                    id: 0,
                    name: 'Sin Seguro',
                    ars: 'Ningunda'
                }
            }
            this.toNewOrEdit = child
            this.title = 'Editar paciente'
            this.toggleNewOrEditChildModal()
        },
        addChild()
        {
            this.planBeingAdd = false;
            this.title = 'Nuevo paciente'
            this.toNewOrEdit = {
                id: null,
                dad_or_mom: this.parentForNewChild
            }

            this.toggleNewOrEditChildModal()
        },
        deleteChild(child){
            NProgress.start()
            this.$inertia.delete(`/children/${this.toDelete.id}`)
            .then( (data) =>{
                this.planBeingDeleted = null
                NProgress.done()
            })
        },
        refresh(data){
            this.children = data
        },
        search(){
            if(this.filter.length > 2 || this.filter == ''){
                NProgress.start()
                axios.get(`/children/all?name=${this.filter}`)
                .then(data => {
                    this.children = data.data
                    NProgress.done()
                })
            }
        }
    }
}
</script>
