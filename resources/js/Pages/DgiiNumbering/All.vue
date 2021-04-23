<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Configuración Secuencias Facturas DGII
            </h2>
        </template>

        <div class="py-2">
            <div class="max-w-6xl mx-auto sm:px-2">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="mb-3 flex justify-between">
                                <button @click="showNewSequenceModal = true" class="bg-white text-gray-600 font-bold rounded border-b-2 border-trendy-pink-300 hover:border-trendy-pink-600 hover:bg-trendy-pink-400 hover:text-white shadow-lg py-2 px-6 inline-flex items-center">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span class="mr-2">Agregar</span>
                                </button>
                            </div>
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Inicio
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Finalización
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Usadas
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Restantes
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Configuración
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Completado
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Activa
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="">Acciones</span>
                                </th>
                                </tr>
                            </thead>
                            <tbody v-if="numerations.data" class="bg-white divide-y divide-gray-200">
                                <tr v-for="numeration in numerations.data" :key="numeration.id">
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900   ">
                                            {{numeration.init}}
                                        </div>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"> {{numeration.end}}</div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"> {{numeration.total}}</div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"> {{numeration.totalUsed}}</div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"> {{numeration.total - numeration.totalUsed}}</div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"> {{numeration.config_date | formatShortDate}}</div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                   <toggle :active="numeration.completed"></toggle>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <toggle :active="numeration.active"></toggle>
                                </td>
                                <td class="px-6 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex">
                                        <button v-if="!numeration.hasInvoices" aria-label="Edit user"
                                                class="p-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600"
                                                @click="edit">
                                            <EditIcon size="1.2x"/>
                                        </button>
                                        <button v-if="!numeration.hasInvoices" aria-label="Delete user"
                                                class="p-1 focus:outline-none focus:shadow-outline text-red-500 hover:text-red-600"
                                                @click="deleteConfig(numeration.id)">
                                            <Trash2Icon size="1.2x"/>
                                        </button>
                                    </div>
                                </td>
                                </tr>
                                <!-- More rows... -->
                            </tbody>
                            </table>
                            <jet-dialog-modal class="mt-10" :maxWidth="'md'" :show="showNewSequenceModal" @close="showNewSequenceModal = false">
                                <template #title>
                                    {{title}}
                                </template>
                                <template #content>
                                    <div class="flex">
                                        <div>
                                            <jet-label :value="'Inicio de secuencia'"></jet-label>
                                            <jet-input type="number"  placeholder=""
                                                    ref="name"
                                                    v-model="form.init"
                                                    class="w-full"
                                                    disabled
                                            />
                                        </div>
                                        <div class="ml-2">
                                            <jet-label :value="'Fin de secuencia'"></jet-label>
                                            <jet-input type="number"  placeholder=""
                                                    ref="name"
                                                    v-model="form.end"
                                                    class="w-full"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <jet-input-error  :message="errorMessage" class="mt-2" />
                                    </div>

                                </template>

                                <template #footer>
                                    <jet-secondary-button @click.native="showNewSequenceModal = false">
                                        Cancelar
                                    </jet-secondary-button>

                                    <jet-button class="ml-2 text-white bg-trendy-pink-500" @click.native="newConfig" >
                                        Guardar
                                    </jet-button>
                                </template>
                            </jet-dialog-modal>
                        </div>
                        </div>
                    </div>
                     <Pagination  :links="numerations.links" @next="refresh" />
                    </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Pagination from '@/Components/Pagination'
import Toggle from '@/Components/Toggle'
import NProgress from 'nprogress'
import { EditIcon, Trash2Icon } from "vue-feather-icons";
import JetDialogModal from '@/Jetstream/DialogModal'
import JetLabel from '@/Jetstream/Label'
import JetInput from '@/Jetstream/Input'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetButton from '@/Jetstream/Button'
import JetInputError from '@/Jetstream/InputError'
import { required, minLength,minValue,maxValue,between } from 'vuelidate/lib/validators'
export default {
    data: () => ({
        numerations: [],
        title: 'Nueva secuencia DGII',
        showNewSequenceModal: false,
        errorMessage: '',
        showError: false,
        form: {
            init: '',
            end: ''
        },
        lastNumeration: {}
    }),
    components: {
        AppLayout,
        Pagination,
        EditIcon,
        Trash2Icon,
        JetDialogModal,
        JetLabel,
        JetInput,
        JetButton,
        JetSecondaryButton,
        Toggle,
        JetInputError
    },
    methods:{
        refresh(data){
            this.numerations = data
        },
        async newConfig(){
            this.getErrors()
            if(!this.showError){
                const data = {
                    init : this.form.init,
                    end : this.form.end
                }
                NProgress.start();
                await this.$inertia.post(`/invoices/config`,data)
                    .then( data =>{
                        NProgress.done();
                        this.showNewSequenceModal = false
                        this.$inertia.get('/invoices/config')
                        this.form.init = ''
                        this.form.end = ''
                    })
            }

        },
        edit(id){

        },
        async deleteConfig(id){
            await this.$inertia.delete(`/invoices/config/${id}`)
            .then( data =>{
               NProgress.done();
                this.$inertia.get('/invoices/config')
            })
        },
        getNumerations(){
            NProgress.start();
            axios.get('config/paginated')
            .then( data =>{
                this.numerations = data.data
                NProgress.done();
                this.lastNumeration = this.getLastNumeration()
                this.form.init = this.lastNumeration.end + 1
            })
        },
        getLastNumeration(){
            return this.numerations.data[0]
        },
        getErrors(){
             if(this.form.init === '' || this.form.end === ''){
                this.errorMessage = 'Debes llenar todos los campos'
                this.showError = true
            }
            else if(this.form.init < this.lastNumeration.end){
                this.errorMessage =  'La secuencia actual no puede ser menor a la anterior.'
                this.showError = true
            }
            else if(this.form.init > this.form.end){
                this.errorMessage = 'El inicio de la secuencia no puede ser mayor al fin.'
                this.showError = true
            }
            else if(this.form.init > this.form.end){
                this.errorMessage = 'El inicio de la secuencia no puede ser mayor al fin.'
                this.showError = true
            }
            else if(this.form.init == this.lastNumeration.end){
                 this.errorMessage = 'El fin de esta secuencia ya ha sido insertada'
                 this.showError = true
            }
            else {
                this.errorMessage = ''
                this.showError = false
            }
        }
    },
    mounted(){
        this.getNumerations()

    },
    computed:{

    }
}
</script>
