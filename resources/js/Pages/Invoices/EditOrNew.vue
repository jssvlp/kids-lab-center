<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Factura
            </h2>
        </template>
        <div class="py-2" >
                <div class="max-w-2xl mx-auto sm:px-2">
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="flex">
                                <div class="py-6 mt-5 align-middle shadow-md rounded-md bg-white inline-block min-w-full sm:px-6 lg:px-8">
                                    <div class="flex justify-start">
                                        <div class="ml-3">
                                            <jet-label :value="'Número de factura'" class="text-trendy-pink-400"></jet-label>
                                            <jet-input type="text"  placeholder="No. Facturas"
                                                        ref="name"
                                                        class="uppercase w-full"
                                                        v-model="invoice.invoice_number"

                                                />
                                        </div>
                                        <div class="ml-2">
                                                    <jet-label :value="'Editar fecha factura:'" class="text-trendy-pink-400"></jet-label>
                                                    <datetime
                                                        type="date"
                                                        v-model="invoice.invoice_date"
                                                        zone="America/Santo_Domingo"
                                                        value-zone="America/Santo_Domingo"
                                                        input-class="border w-full bg-white rounded  py-2 px-2 outline-none"
                                                        @close="updateInvoice"
                                                        :min-datetime="maxDatetime"

                                                    ></datetime>
                                        </div>
                                        <div class="flex">
                                            <div class="m-auto">
                                                <inertia-link :href="route('visits.newOrEdit',this.invoice.visit.id)">
                                                    <jet-button  class="text-white bg-red-orange-400 hover:bg-red-orange-400 mt-5 ml-3" @click.native="applyDicount" >
                                                        Editar visita
                                                    </jet-button>
                                                </inertia-link>
                                            </div>
                                        </div>
                                    </div>
                                   <div class="flex justify-between">
                                       <div>
                                            <div class="">

                                            </div>
                                        <div class="mx-3 mt-3">
                                            <jet-label :value="'Padre/Madre'" class="text-trendy-pink-400"></jet-label>
                                            <span class="font-bold"> {{invoice.visit.child.dad_or_mom.name}}</span>
                                        </div>
                                        <div class="mx-3 mt-3">
                                            <jet-label :value="'Paciente'" class="text-trendy-pink-400"></jet-label>
                                            <span class="font-bold"> {{invoice.visit.child.name}}</span>
                                        </div>

                                        <div class="mx-4 mt-3">
                                            <jet-label :value="'Fecha visita'" class="text-trendy-pink-400"></jet-label>
                                             <span class="font-bold">{{invoice.visit.visit_date | formatLargeDate}}</span>
                                        </div>
                                        <div class="flex mt-3 mx-3">
                                            <div>
                                                <jet-label :value="'Seguro salud'" class="text-trendy-pink-400"></jet-label>
                                                <div v-if="invoice.visit.child.plan">
                                                    Plan <span class="font-bold">{{invoice.visit.child.plan.name}}</span> de <span class="font-bold">{{invoice.visit.child.plan.insurance.name}}</span>
                                                    <div v-if="invoice.visit.child.health_insurance_id">
                                                        <jet-label :value="'Numero/id seguro'" class="text-trendy-pink-400 mt-3"></jet-label>
                                                        <span class="font-bold">{{invoice.visit.child.health_insurance_id}}</span>
                                                    </div>
                                                </div>
                                                <div v-else>Sin seguro</div>
                                            </div>
                                        </div>
                                       </div>
                                       <div class="flex">
                                            <div class="m-auto">
                                                <img src="/Images/klc-logo.png" style="width:100%; max-width:250px;">
                                            </div>
                                       </div>
                                   </div>
                                </div>
                            </div>


                            <div class="py-6 align-middle pb-10 shadow-md rounded-md bg-white mt-3 inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="flex justify-end" >
                                    <div class="flex cursor-pointer hover:-translate-y-1 hover:scale-110" @click="openPayModal">
                                        <div class="flex items-center justify-center flex-shrink-0 h-8 w-8 rounded-xl bg-green-100 text-green-500">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    <div class="ml-2 mt-1">Cobrar</div>
                                    </div>
                                </div>
                                <div class="flex justify-center" v-if="invoice.payment_status == 'Pago'">
                                    <span class="font-extrabold text-red-600">FACTURA PAGADA</span>
                                </div>
                                Vacunas facturadas:
                                <div class="shadow mt-2 overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                    <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio
                                    </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="vaccine in invoice.vaccines" :key="vaccine.id">
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
                                            <jet-input type="text"  placeholder="precio"
                                                    ref="name"
                                                    v-model="vaccine.pivot.price"
                                            />
                                        </div>
                                    </td>

                                    </tr>
                                    <tr>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="font-bold">Total</span>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex">
                                                <span class="font-bold text-green-700 mt-1 ml-1">RD{{total | currency }}</span>
                                                <div v-if="!discountVisible" class="flex cursor-pointer ml-2" @click="modalDiscountVisible = true">
                                                    <div class="flex items-center justify-center flex-shrink-0 h-8 w-8 rounded-xl bg-red-orange-100 text-red-orange-400">
                                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path></svg>
                                                    </div>
                                                    <div class="ml-2 mt-1 text-red-orange-400">Aplicar Cobertura ARS</div>
                                                </div>
                                                <div class="flex mt-1 ml-1" v-if="discountVisible">
                                                    <span class="text-red-orange-600">- {{discount}}% descuento cobertura ARS </span>
                                                    <button aria-label="Edit user"
                                                        class="p-1 ml-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600"
                                                        @click="modalDiscountVisible = true">
                                                        <EditIcon size="1.2x"/>
                                                    </button>
                                                    <button aria-label="remove discount"
                                                        class="p-1 focus:outline-none focus:shadow-outline text-red-orange-500 hover:text-red-orange-600"
                                                        @click="removeDiscount">
                                                        <Trash2Icon size="1.2x"/>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- More rows... -->
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <jet-dialog-modal :maxWidth="'md'" :show="modalDiscountVisible" @close="modalDiscountVisible = false">
            <template #title>
                Aplicar cobertura ARS
            </template>
            <template #content>
                <jet-label :value="'Digite un porcentaje'"></jet-label>
                <jet-input type="number"  placeholder="0%"
                        ref="name"
                        v-model="discount"
                        class="w-full"
                />
                <jet-input-error v-if="!$v.discount.required && $v.discount.$error" :message="'Debes digitar un porcentaje'" class="mt-2" />
                <jet-input-error v-if="!$v.discount.minValue && $v.discount.$error" :message="'El porcentaje no puede ser menor a 1'" class="mt-2" />
                <jet-input-error v-if="!$v.discount.maxValue && $v.discount.$error" :message="'El porcentaje no puede ser superior a 100'" class="mt-2" />
            </template>

            <template #footer>
                <jet-secondary-button @click.native="modalDiscountVisible = false">
                    Cancelar
                </jet-secondary-button>

                <jet-button class="ml-2 text-white bg-trendy-pink-500" @click.native="applyDicount" >
                    Aplicar
                </jet-button>
            </template>
        </jet-dialog-modal>
        <jet-dialog-modal :maxWidth="'xl'" :show="paymentModalVisible" @close="paymentModalVisible = false">
            <template #title>
                <span class="font-extrabold px-4">Confirmación de cobro</span>
            </template>
            <template class="" #content>
                <div class="px-4">
                    <div class="flex justify-between">
                        <span>Vacunas:</span>
                        <span>RD{{total | currency}}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Cobertura ARS:</span>
                        <span>{{ discount > 0 ? '-':''}}{{discount == '' ? 0 : discount}}% (RD{{ discounted | currency }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total a cobrar (diferencia):</span>
                        <span>RD{{total - discounted | currency}}</span>
                    </div>
                    <div class="mt-4">
                        <div>
                            <span class="font-bold">Método de pago</span>
                        </div>
                        <input v-model="form.paymentMethod" type="radio" id="efectivo" value="Efectivo">
                        <label for="efectivo">Efectivo</label><br>
                        <input v-model="form.paymentMethod" type="radio" id="tarjeta" value="Tarjeta" @click="form.authorization = ''">
                        <label for="tarjeta">Tarjeta</label><br>
                        <div  v-if="form.paymentMethod =='Tarjeta'">
                            <jet-label :value="'Número de autorización'"></jet-label>
                            <jet-input type="text"  placeholder=""
                                    ref="name"
                                    v-model="form.authorization"
                                    class="w-full"
                                    :class="{ 'border-red-500': $v.form.authorization.$error }"

                            />
                            <jet-input-error v-if="!$v.form.authorization.required && $v.form.authorization.$error" :message="'Debes digitar el numero de autorización'" class="mt-2" />
                            <jet-input-error v-if="!$v.form.authorization.minLength && $v.form.authorization.$error" :message="'Mínimo 5 caracteres'" class="mt-2" />
                            <br>
                        </div>
                    </div>
                </div>

            </template>

            <template #footer>
                <jet-secondary-button @click.native="paymentModalVisible = false">
                    Cancelar
                </jet-secondary-button>

                <jet-button class="ml-2 text-white bg-trendy-pink-500" @click.native="collectMoney" >
                    Cobrar
                </jet-button>
            </template>
        </jet-dialog-modal>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetInput from '@/Jetstream/Input'
import { mapState, mapActions} from 'vuex'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetButton from '@/Jetstream/Button'
import JetLabel from '@/Jetstream/Label'
import JetDialogModal from '@/Jetstream/DialogModal'
import axios from 'axios'
import {  Trash2Icon,EditIcon } from "vue-feather-icons";
import { required, minLength,minValue,maxValue } from 'vuelidate/lib/validators'
import JetInputError from '@/Jetstream/InputError'
import NProgress from 'nprogress'
import { Datetime } from 'vue-datetime'

export default {
    props: ['invoice'],
    components: {
        AppLayout,
        JetInput,
        JetButton,
        JetLabel,
        JetDialogModal,
        JetSecondaryButton,
        Trash2Icon,
        EditIcon,
        JetInputError,
        Datetime
    },
    data:() =>({
        parent: {},
        discount: '0',
        discountVisible: false,
        modalDiscountVisible: false,
        paymentModalVisible: false,
        form:{
            paymentMethod: 'Efectivo',
            authorization:'',
        },
        invoiceNumberOld: '',
        maxDatetime: '',
        allowEditInvoiceNumber: false
    }),
    validations:{
       form:{
            authorization:{
                required,
                minLength: minLength(5),
            }
       },
       discount:{
           minValue: minValue(1),
           maxValue: maxValue(100),
           required
       }
    },
    computed:{
        total(){
            return this.calculateTotal()
        },
        discounted(){
            const total = this.calculateTotal();
            return  Math.trunc(total * (this.discount /100)*100)/100
        }
    },
    methods:{
        openPayModal(){
             this.paymentModalVisible = true
        },
        updateInvoice(){
            if(this.invoice.invoice_number && this.invoice.invoice_date){
                if(parseInt(this.invoice.invoice_number.length) < 12 ){
                    alert('¡Aquí como que faltan numeritos, revisa de nuevo!')
                    return
                }
                if(parseInt(this.invoice.invoice_number.length) > 12){
                    alert('¡Sobran numertiso por aquí, revisa de nuevo!')
                    return
                }
                const data = {
                    'invoice_number' : this.invoice.invoice_number,
                    'invoice_date' : this.invoice.invoice_date
                }

                axios.patch(`/invoices/${this.invoice.id}`, data)
                .then(data => {
                    console.log(data.data);
                })
            }

        },
        async collectMoney(){
            if(this.form.paymentMethod == 'Tarjeta'){
                this.$v.form.$touch();
                if(this.$v.form.$error) return
            }
            const data = {
                vaccines: this.invoice.vaccines,
                discount: this.discount,
                payment_method: this.form.paymentMethod,
                authorization: this.form.authorization
            }

            await this.$inertia.post(`/invoices/pay/${this.invoice.id}`,data)
            .then( data =>{

            })

        },
        calculateTotal(){
            return this.invoice.vaccines.reduce(function(a, b){
               return (parseInt(a) + parseInt(b.pivot.price)) /1;
            }, 0);
        },
        applyDicount(){
            this.$v.discount.$touch();
            if(this.$v.discount.$error) return
            this.discountVisible = true
            this.modalDiscountVisible = false
        },
        removeDiscount(){
            this.discount = 0
            this.discountVisible = false
            this.modalDiscountVisible = false
        },

    },
    mounted(){
       this.invoiceNumberOld = this.invoice.invoice_number;
       this.maxDatetime = this.invoice.visit.visit_date;
       this.discount = this.invoice.discount

       if(this.discount != '0'){
           this.discountVisible = true;
       }


    }
}
</script>
