<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Facturas
            </h2>
        </template>
        <div class="py-2 overflow-auto">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="my-2 flex justify-end">
                     <div class="flex">
                        <span class="text-sm border border-2 rounded-l px-4 py-2 bg-gray-300">Buscar:</span>
                        <input name="field_name" class="border border-2 rounded-r px-2"
                                v-model="filter"
                                @keyup="search"
                                type="text" placeholder="Paciente o Factura" />
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-10 lg:-mx-12">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                <th @click="orderBy('invoice_number')"  scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    No. Factura
                                </th>
                                <th  scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Paciente
                                </th>
                                <th @click="orderBy('invoice_date')" scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Fecha
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vacunas
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Monto
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cobertura ARS
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Diferencia
                                </th>
                                <th @click="orderBy('payment_status')" scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Estatus del pago
                                </th>
                                <th scope="col" class="relative px-6 py-2">
                                    <span class="sr-only">Acciones</span>
                                </th>
                                </tr>
                            </thead>
                            <tbody v-if="invoices.data" class="bg-white divide-y divide-gray-200">
                                <tr v-for="invoice in invoices.data" :key="invoice.id">
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{invoice.invoice_number}}</div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                    <div class="">
                                        <inertia-link :href="route('invoices.detail',invoice.id)" class="text-sm font-medium text-gray-900 font-bold">
                                            {{invoice.visit.child.name}}
                                        </inertia-link>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{invoice.invoice_date | formatShortDate}}</div>

                                </td>
                                <td class="px-6  whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{invoice.vaccines.length}}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="font-bold text-green-600">RD{{total(invoice) | currency }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="font-bold text-green-600">RD{{coberage(invoice) | currency }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="font-bold text-green-600">RD{{total(invoice) - coberage(invoice) | currency }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="font-bold" :class="{'text-red-600' : invoice.payment_status == 'Pendiente', 'text-green-600' : invoice.payment_status == 'Pago'}">{{invoice.payment_status}}</span>
                                </td>
                                <td class="px-6 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex">
                                        <inertia-link :href="route('invoices.edit',invoice.id)"  aria-label="Edit user"
                                                class="p-1 focus:outline-none focus:shadow-outline text-trendy-pink-400 hover:text-trendy-pink-600"
                                                preserve-scroll>
                                            <EditIcon size="1.2x"/>
                                        </inertia-link>
                                        <inertia-link :href="route('invoices.detail',invoice.id)"  aria-label="Ver factura"
                                                class="p-1 focus:outline-none focus:shadow-outline text-trendy-pink-400 hover:text-trendy-pink-600"
                                                preserve-scroll>
                                            <EyeIcon size="1.2x"/>
                                        </inertia-link>
                                        <a v-if="invoice.payment_status == 'Pago'" :href="route('invoices.print',invoice.id)"  target= '_blank' class="mt-1" >
                                           <PrinterIcon size="1.2x"/>
                                        </a>

                                    </div>
                                </td>
                                </tr>
                                <!-- More rows... -->
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                        <Pagination  :links="invoices.links" @next="refresh" />
                    </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Pagination from '@/Components/Pagination'
import { mapState, mapActions} from 'vuex'
import { EditIcon, Trash2Icon, PrinterIcon, EyeIcon } from "vue-feather-icons";
import JetConfirmationModal from '@/Jetstream/ConfirmationModal';
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetButton from '@/Jetstream/Button'
import JetDangerButton from '@/Jetstream/DangerButton'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetInputError from '@/Jetstream/InputError'
import NProgress from 'nprogress'

export default {
    components: {
        AppLayout,
        Pagination,
        EditIcon,
        Trash2Icon,
        PrinterIcon,
        EyeIcon,
        JetConfirmationModal,
        JetSecondaryButton,
        JetButton,
        JetDangerButton,
        JetDialogModal
    },
    data: () =>({
        toNewOrEdit: null,
        toDelete: {},
        planBeingDeleted: null,
        planBeingAdd: null,
        newChild: null,
        title: '',
        test: false,
        invoices: {},
        filter: '',
        orderAsc: true
    }),

    mounted(){
        NProgress.start();
        axios.get('invoices/all/paginated')
        .then( data =>{
            this.invoices = data.data
            NProgress.done();
            window.scroll(100,100)
        })
    },
    methods:{
        total(invoice){
            const discount = invoice.discount /100;
            let subTotal =  invoice.vaccines.reduce(function(a, b){
                return a + b.pivot.price;
            }, 0);

            return subTotal;

        },
        coberage(invoice){
            const discount = invoice.discount /100;
            let subTotal =  invoice.vaccines.reduce(function(a, b){
                return a + b.pivot.price;
            }, 0);

            return  subTotal * discount
        },
        refresh(data){
            this.invoices = data
        },
        search(){
            if(this.filter.length >= 2 || this.filter == ''){
                NProgress.start()
                axios.get(`/invoices/all/paginated?filter=${this.filter}`)
                .then(data => {
                    this.invoices = data.data
                    NProgress.done()
                })
            }
        },
        orderBy(column){
            this.orderAsc = !this.orderAsc;
            let ascOrDesc = this.orderAsc ? 'asc' : 'desc';
            NProgress.start()
            axios.get(`/invoices/all/paginated?orderby=${column}&descOrAsc=${ascOrDesc}`)
                .then(data => {
                    this.invoices = data.data
                    NProgress.done()
                })
        }
    }
}
</script>
