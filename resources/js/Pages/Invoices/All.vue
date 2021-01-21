<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Facturas
            </h2>
        </template>
        <div class="py-2">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
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
                                    Vacunas
                                </th>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col" class="relative px-6 py-2">
                                    <span class="sr-only">Acciones</span>
                                </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="invoice in invoices" :key="invoice.id">
                                <td class="px-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                    <div class="ml-4">
                                        <inertia-link :href="route('invoices.detail',invoice.id)" class="text-sm font-medium text-gray-900 font-bold">
                                            {{invoice.visit.child.name}}
                                        </inertia-link>
                                    </div>
                                    </div>
                                </td>
                                <td class="px-6 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{invoice.invoice_date}}</div>
                                    
                                </td>
                                <td class="px-6  whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{invoice.visit.vaccines.length}}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="font-bold text-green-600">RD${{total(invoice.visit.vaccines)}}</span>
                                </td>
                                <td class="px-6 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex">
                                        <inertia-link :href="route('invoices.detail',invoice.id)"  v-if="invoice.payment_status != 'Pago'" aria-label="Edit user"
                                                class="p-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600">
                                            <EditIcon size="1.2x"/>
                                        </inertia-link>
                                        <inertia-link v-else :href="route('invoices.detail',invoice.id)"  aria-label="Ver factura"
                                                class="p-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600"
                                                @click="invoiceDetail(invoice.id)">
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
                    <Pagination :pages="1" :init="1" :end="1"/>
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

export default {
    props:['invoices'],
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
        title: '',
        planBeingDeleted: null,
        planBeingAdd: null,
        newChild: null,
        title: '',
        test: false
    }),
    computed: {
        ...mapState(['editingOrCreatingChild','parentForNewChild']),
        
    },

    methods:{
        ...mapActions({
            toggleNewOrEditChildModal: "toggleNewOrEditChildModal"
        }),
        invoiceDetail(id){
            console.log('abriendo detail')
        },
        total(vaccines){
            return vaccines.reduce(function(a, b){
               return a + b.price;
            }, 0); 
        },
        print(invoice){
            console.log('printing invoice')
        },
        editChild(child){
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
             this.$inertia.delete(`/children/${this.toDelete.id}`)
            .then( (data) =>{
                this.planBeingDeleted = null
            })
        },
    }
}
</script>
