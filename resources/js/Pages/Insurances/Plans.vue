<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Planes - {{insurance.name}}
            </h2>
        </template>
        <Modal :show="toggleModal">
            <template #title>
                    Borrar plan
            </template>
            <div class="p-5">
                <button @click="toggleModal = !toggleModal" class="bg-white text-red-800 font-bold rounded border-b-2 border-green-500 hover:border-green-600 hover:bg-green-500 hover:text-white shadow-lg py-2 px-6 inline-flex items-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                    <span class="mr-2">Cerrar</span>
                </button>
            </div>
        </Modal>
        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="-my-1 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="min-h-screen w-full flex  justify-center">
                                <ul class="w-full max-w-md mt-10">
                                    <div v-if="plans.length == 0" class="flex justify-center mb-3 "> 
                                        <h2>No existen planes agregados</h2>
                                    </div>
                                    <form action="/users" method="POST" @submit.prevent="createPlan" class="flex flex-wrap -mx-2 mb-5">
                                        
                                        <div class="w-3/4">
                                            <Input :value="form.name" v-model="form.name" id="name" class="w-full" :class="this.edit != '' ? 'border-yellow-200' : ''"/>    
                                            <div class="mt-2"> 
                                                <span class="text-yellow-300" v-if="this.edit != ''">Editando</span> <span class="bg-red-600  text-white rounded cursor-pointer p-1" v-if="this.edit != ''" @click="cancelEdition">Cancelar</span> 

                                            </div>
                                        </div>
                                        <div class="w-1/4">
                                            <button type="submit" :disabled="form.name.length < 1" class="ml-3 bg-white text-gray-800 font-bold rounded border-b-2 border-green-500 hover:border-green-600 hover:bg-green-500 hover:text-white shadow-lg py-2 px-6 inline-flex items-center">
                                            <span class="mr-2">{{ this.edit == '' ? 'Agregar' : 'Guardar' }}</span>
                                            </button>
                                        </div>
                                    </form>
                                    <li v-for="plan in plans"
                                        :key="plan.id"
                                        class="p-4 mb-3 flex justify-between items-center bg-white shadow rounded-lg cursor-hand transition duration-500 ease-in-out  hover:bg-white-600 transform hover:-translate-y-1 hover:scale-110">
                                        {{plan.name}}
                                        <div class="flex auto">
                                            <button aria-label="Edit user"
                                                    class="p-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600"
                                                    @click="onEdit(plan)">
                                                <EditIcon/>
                                            </button>
                                            <button aria-label="Delete user"
                                                    class="p-1 focus:outline-none focus:shadow-outline text-red-500 hover:text-red-600"
                                                    @click="planBeingDeleted = true, toDelete = plan">
                                                <Trash2Icon/>
                                            </button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <jet-confirmation-modal :show="planBeingDeleted" @close="planBeingDeleted = null">
            <template #title>
                Borrar plan
            </template>

            <template #content>
                ¿Estás seguro que deseas borrar el plan <span class="font-bold">{{toDelete.name}}</span>?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="planBeingDeleted = null, toDelete = {}">
                    Cancelar
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deletePlan" >
                    Confirmar
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Modal from '@/Jetstream/Modal'
import Input from '@/Jetstream/Input'
import { EditIcon, Trash2Icon } from "vue-feather-icons";
import JetConfirmationModal from '@/Jetstream/ConfirmationModal';
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetDangerButton from '@/Jetstream/DangerButton'
export default {
    props:['plans','insurance'],
    components:{
        AppLayout,
        Modal,
        Input,
        EditIcon,
        Trash2Icon,
        JetConfirmationModal,
        JetSecondaryButton,
        JetDangerButton
    },
    data: () => ({
        toggleModal: false,
        edit:'',
        form: {
            name: "",
            insurance_id: ''
        },
        planBeingDeleted: null,
        toDelete: {}
    }),
    mounted(){
        this.form.insurance_id = this.insurance.id
    },
    methods: {
        createPlan(){
            if(this.edit){
                this.$inertia.patch(`/plans/${this.edit}`, this.form)
                .then( (data) =>{
                    this.form.name = ''
                    this.edit = ''
                })
            }
            else{
                this.$inertia.post('/plans', this.form)
                .then( (data) =>{
                this.form.name = ''
                })
            }
            
        },
        onEdit(plan) {
            this.edit = plan.id;
            this.form.name = plan.name
        },
        deletePlan() {
            const data = {'insurance_id': this.insurance.id};
            const plan = this.toDelete;
            this.$inertia.delete(`/plans/${this.toDelete.id}`, data)
            .then( (data) =>{
                this.planBeingDeleted = null
            })
        },
        cancelEdition(){
            this.form.name = ''
            this.edit = ''
        }
    }
}
</script>

<style>

</style>