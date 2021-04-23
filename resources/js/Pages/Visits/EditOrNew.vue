<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{titleForVisit}}
            </h2>
        </template>
        <div class="py-2" v-if="visit !=null">
            <div class="flex justify-center">
                <div v-if="$page.dgii.remaining === 0 && showSequenceAlert && !editing" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-20 max-w-4xl" role="alert">
                    <strong class="font-bold">¡Alerta!</strong>
                    <span class="block sm:inline">No podrás facturar esta visita. Debido a que la secuencia de facturas actual se ha completado. Una vez configurada la nueva secuencia podrás facturar normalmente</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" @click="showSequenceAlert = false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                  </span>
                </div>
            </div>
            <div class="flex justify-end mr-15 mt-4" >
                <jet-button @click.native="facturar" v-if="!editing" class="bg-red-orange-400 hover:bg-red-orange-400 " :class="{'invisible' : !visit.invoiced && visit.vaccines.length == 0}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path></svg>
                    {{ textFacturarBtn }}
                </jet-button>
                <jet-button @click.native="updateVisit" v-if="editing" class="bg-red-orange-400 hover:bg-red-orange-400 " :class="{'invisible' :  visit.vaccines.length == 0}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path></svg>
                    Guardar y continuar
                </jet-button>
            </div>
            <div class="flex mt-8 mx-5 justify-center">
                <div class="w-2/5">
                    <div id="childInfo" class="bg-white shadow-lg rounded-md mx-10 px-6 py-6">
                    <div class="flex justify-center">
                        <img class="h-15 w-15 shadow-md rounded-full" :src="visit.child.gender == 'Niño' ?'/Images/boy.svg':'/Images/girl.svg'" alt="">
                    </div>
                    <div class="flex justify-center">
                        <span class="font-bold"> {{visit.child.name}}</span>
                    </div>
                     <div class="flex justify-center -my-1">
                        <span>{{visit.child.age}}</span>
                    </div>
                    <div class="mt-4">
                        <div class="mx-3">
                            Padre: <span class="font-bold"> {{ visit.child.dad_or_mom.name }}</span>
                        </div>
                        <div class="mx-3">
                           Cantidad Visitas anteriores: <span class="font-bold">{{visit.child.visitsCount -1}}</span>
                        </div>
                        <div class="mx-3">
                            Fecha visita anterior: <span class="font-bold"> {{visit.child.lastVisit.visit_date | formatShortDate}}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow-lg rounded-md mx-10 px-6 py-6 mt-6">
                        <div class="w-2/4">
                            <jet-label :value="'Fecha visita actual:'"></jet-label>
                            <datetime
                                type="date"
                                v-model="visit.visit_date"
                                zone="America/Santo_Domingo"
                                value-zone="America/Santo_Domingo"
                                :max-datetime="limitDate"
                                input-class="border w-full bg-white rounded  py-2 px-2 outline-none"
                            ></datetime>
                        </div>
                        <div class="mt-2">
                            Facturado: <span class="font-bold" :class="visit.invoiced ? 'text-green-600' : 'text-red-600'"> {{visit.invoiced == true ? "Sí (en edición)" : "No" }}</span>
                        </div>
                        <div>
                            Cantidad de vacunas: <span class="font-bold"> {{visit.vaccines.length}}</span>
                        </div>
                </div>
                </div>

                <div id="vaccines" class="w-3/5 bg-white shadow-lg rounded-md px-6 py-6 mr-10">
                    <SelectVaccine v-if="allowEditing" :added="vaccinesPushed" class="mb-4" @pushed="addVaccine" @showOptions="showOptions" :showVaccinesList="showVaccinesList"  v-click-outside="closeVaccinesSelect"/>
                    <span class="font-bold mt-10">Colocadas:</span>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                            <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Precio
                            </th>
                            <th v-if="!visit.invoiced" scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <span class="">Acciones</span>
                            </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="vaccine in visit.vaccines" :key="vaccine.id">
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900   ">
                                            {{ vaccine.name}}
                                        </div>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        RD{{ vaccine.price | currency }}
                                    </div>
                                </td>
                                <td v-if="allowEditing" class="px-6 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex" >
                                        <button aria-label="Quitar vacuna"
                                                class="p-1 focus:outline-none focus:shadow-outline text-red-500 hover:text-red-600"
                                                @click="removeVaccine(vaccine)">
                                            <Trash2Icon size="1.2x"/>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <span class="font-bold">Total</span>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <span class="font-bold text-green-700">RD{{total |currency }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <jet-dialog-modal :show="visit == null" >
                    <template #title>
                        Primero seleccione un paciente (niño/a)
                    </template>

                    <template #content>
                        <SelectChildBefore  />
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </template>

                    <template #footer>
                        <inertia-link :href="route('visits.index')" >
                            <jet-secondary-button @click.native="planBeingDeleted = null, toDelete = {}">
                                Cancelar
                            </jet-secondary-button>
                        </inertia-link>
                        <jet-button class="ml-2 bg-trendy-pink-400 hover:bg-trendy-pink-800" @click.native="saveVisit" :disabled="!childForNewVisit" :class="{ 'opacity-25': !childForNewVisit }">
                            Confirmar
                        </jet-button>
                    </template>
        </jet-dialog-modal>
        <jet-dialog-modal :show="showEditConfirmation" >
                    <template #title>
                        Confirmar edición de visita
                    </template>

                    <template #content>
                       <span>Esta visita ya ha sido facturada. Favor confirmar si deseas editar la visita. Si agregas o remueves vacunas también se modificará la factura.</span>
                    </template>

                    <template #footer>
                        <inertia-link :href="route('visits.index')" >
                            <jet-secondary-button @click.native="confirmEdit(false)">
                                Cancelar
                            </jet-secondary-button>
                        </inertia-link>
                        <jet-button class="ml-2 bg-red-orange-400 hover:bg-red-orange-400" @click.native="confirmEdit(true)" >
                            Confirmar
                        </jet-button>
                    </template>
        </jet-dialog-modal>
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
import axios from 'axios'
import SelectChildBefore from './SelectChildBefore'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetInputError from '@/Jetstream/InputError'
import JetButton from '@/Jetstream/Button'
import SelectVaccine from '../Vaccines/SelectVaccine'
import { Trash2Icon } from "vue-feather-icons";
import NProgress from 'nprogress'
import { Datetime } from 'vue-datetime'
import moment from 'moment'
import JetLabel from '@/Jetstream/Label'

export default {
    props: ['visit','title'],
    components: {
        AppLayout,
        Input,
        EditOrNew,
        JetConfirmationModal,
        JetSecondaryButton,
        JetDangerButton,
        SelectChildBefore,
        JetDialogModal,
        JetButton,
        SelectVaccine,
        Trash2Icon,
        Datetime,
        JetLabel
    },
    data:() =>({
       parent: {},
       vaccinesPushed: [],
       showVaccinesList: false,
       showEditConfirmation: false,
       editing: false,
       limitDate: moment().format('YYYY-MM-DD').toString(),
       showSequenceAlert: true
    }),
    computed:{
        ...mapState(['childForNewVisit','titleForVisit']),
        total(){
            return this.visit.vaccines.reduce(function(a, b){
               return a + b.price;
            }, 0);
        },
        textFacturarBtn(){
            return this.$page.dgii.remaining > 0 ? 'Finalizar y Facturar' : 'Guardar y Facturar Luego'
        },
        allowEditing(){
            if(this.visit.invoiced && this.editing){
                return true;
            }
            else if(!this.visit.invoiced && !this.editing){
                return true;
            }
            else if(this.visit.invoiced && this.editing){
                return true;
            }
            return false;
        }
    },
    updated() {
        // whenever data changes and the component re-renders, this is called.
        this.$nextTick(() => this.scrollToEnd());
    },
    methods:{
        ...mapActions({
            setTitleForVisit : "setTitleForVisit"
        }),
        closeVaccinesSelect(){
            this.showVaccinesList = false
        },
        showOptions(){
            this.showVaccinesList = true
        },
        facturar(){
            if(this.$page.dgii.remaining == 0){
                this.$inertia.get('/visits')
            }

            const data = { visit_id : this.visit.id }
            NProgress.start()
            this.$inertia.post('/invoices', data)
                .then(data => {
                    NProgress.done()
            })
        },
        addVaccine(vaccine){
            axios.post(`/visits/${this.visit.id}/vaccine/${vaccine}`)
            .then(data =>{
                this.refreshVaccines()
            })
        },
        removeVaccine(vaccine){
            axios.delete(`/visits/${this.visit.id}/vaccine/${vaccine.id}`)
            .then( data =>{

                this.refreshVaccines()
            })
        },
        refreshVaccines(){
            NProgress.start()
            axios.get(`/visits/${this.visit.id}/vaccines`)
                .then( data =>{
                    this.visit.vaccines = data.data;
                    this.vaccinesPushed = this.visit.vaccines;
                    this.showVaccinesList = true;
                    NProgress.done();
                })
        },
        confirmEdit(edit){
            if(edit){
                this.showEditConfirmation = false
                this.editing = true
            }
            else{
                 this.$inertia.get('/visits')
            }
        },
        saveVisit(){
            NProgress.start()
            this.$inertia.post('/visits',{'child_id' : this.childForNewVisit.id})
            .then(data => {
                this.setTitleForVisit('Nueva visita')
                NProgress.done()
            })

        },
        scrollToEnd: function () {
            // scroll to the start of the last message
            this.$el.scrollTop = this.$el.lastElementChild.offsetTop;
        },
        updateVisit(){
            NProgress.start()
            const data = {
                visit_date: this.visit.visit_date
            }
            this.$inertia.patch(`/visits/${this.visit.id}`, data)
            .then(data => {
                NProgress.done();
            })
        },
        checkInvoiced(){
            if(this.visit){
                axios.get(`/visits/${this.visit.id}/get`)
                .then(data => {
                    if(data.data.visit.invoiced){
                        this.showEditConfirmation = true

                    }
                })
            }
        }
    },
    created(){
        this.checkInvoiced()
    },
    mounted(){
        this.setTitleForVisit('Nueva visita')
        if(this.visit != null){
            this.setTitleForVisit('Editar visita')
            this.vaccinesPushed = this.visit.vaccines;
        }
        this.checkInvoiced()
    }
}
</script>
