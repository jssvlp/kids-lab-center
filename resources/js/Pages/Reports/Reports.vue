<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reportes
            </h2>
        </template>
        <div class="py-2">
                <div class="max-w-5xl mx-auto sm:px-2">
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-10 lg:-mx-12">
                            <div class="py-6 mt-5 align-middle shadow-md rounded-md bg-white inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="mx-2 font-bold">
                                    Filtro por rango de fechas
                                </div>
                                <div class="flex justify-between">
                                    <div class="w-full mx-2">
                                     <jet-label :value="'Desde'"></jet-label>
                                    <datetime
                                        type="date"
                                        v-model="from"
                                        zone="America/Santo_Domingo"
                                        value-zone="America/Santo_Domingo"
                                        class="theme-blue border w-full bg-white rounded  py-2 px-2 outline-none "
                                        :max-datetime="maxDateFrom"
                                    ></datetime>
                                </div>
                                <div class="w-full">
                                    <jet-label :value="'Hasta'"></jet-label>
                                    <datetime
                                        type="date"
                                        v-model="to"
                                        zone="America/Santo_Domingo"
                                        value-zone="America/Santo_Domingo"
                                        input-class="border w-full bg-white rounded  py-2 px-2 outline-none"
                                        class="theme-orange"
                                        :max-datetime="maxDatetime"
                                        :min-datetime="minDateTo"
                                    ></datetime>
                                </div>

                                <div class="flex mt-5 ">
                                   <jet-button @click.native="filterData" :class="{'opacity-50' : from == '' && to == ''}" class="ml-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                        <span class="ml-1">Filtrar</span>
                                    </jet-button>
                                    <!-- <button  @click="print" class="ml-2 text-gray-500 hover:text-cool-gray-700 focus:outline-none focus:text-indigo-600" >
                                        <PrinterIcon size="1.5x"/>
                                    </button> -->
                                </div>
                            </div>
                            </div>
                            <div class="flex justify-center py-6 mt-5 align-middle shadow-md rounded-md bg-white inline-block min-w-full sm:px-10 lg:px-12">
                                 <apexchart width="320" type="donut" :options="optionsDonut" :series="seriesDonut"></apexchart>
                                 <apexchart width="320" type="line" :options="optionsLine" :series="seriesLine"></apexchart>
                                 <div class="ml-4">
                                     <p> Facturas emitidas: <span class="font-bold"> {{filter.invoices.length}} </span></p>
                                     <p> Total facturado: <span class="font-bold">RD{{ totalInvoiced | currency}} </span></p>
                                 </div>
                            </div>
                            <div class="py-2 align-middle pb-10 shadow-md rounded-md bg-white mt-3  min-w-full sm:px-10 lg:px-8">
                                <div class="flex justify-between">
                                    <span class="font-bold mt-4">Pagos recibidos en el periodo</span>
                                    <jet-button class="ml-3">
                                        <img width="20px" src="/Images/excel.svg" class="mr-2">
                                        <export-excel
                                            :data="filter.invoicesForExcel" :name="excelName">

                                            Exportar excel
                                        </export-excel>
                                        <span class="ml-1"></span>
                                    </jet-button>
                                </div>
                                <div class="shadow mt-2 overflow-hidden border-b border-gray-200 sm:rounded-lg mt-2">
                                <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                    <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. Factura
                                    </th>
                                    <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Metodo de pago
                                    </th>
                                    <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha del pago
                                    </th>
                                    <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. Autorización
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
                                    </tr>
                                </thead>
                                <tbody  class="bg-white divide-y divide-gray-200">
                                    <tr v-for="invoice in filter.invoices" :key="invoice.id">
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                        <div class="ml-4">
                                            <inertia-link :href="route('invoices.edit',invoice.id)" class="text-sm font-medium text-gray-900 font-bold">
                                                {{ invoice.invoice_number}}
                                            </inertia-link>
                                        </div>
                                        </div>
                                    </td>
                                    <td class="px-6 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{invoice.payment_method}}
                                        </div>
                                    </td>
                                    <td class="px-6 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{invoice.invoice_date | formatShortDate}}
                                        </div>
                                    </td>
                                    <td class="px-6 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{invoice.authorization_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            RD{{total(invoice)| currency}}
                                        </div>
                                    </td>
                                    <td class="px-6 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            RD{{coberage(invoice)| currency}}
                                        </div>
                                    </td>
                                    <td class="px-6 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            RD{{total(invoice) - coberage(invoice)| currency}}
                                        </div>
                                    </td>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total:</td>
                                        <td class="font-bold">RD{{ totalInvoiced | currency}}</td>
                                        <td class="font-bold">RD{{ totalCoberaged | currency}}</td>
                                        <td class="font-bold">RD{{ totalDiff | currency}}</td>
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
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetConfirmationModal from '@/Jetstream/ConfirmationModal';
import moment from 'moment';
import JetButton from '@/Jetstream/Button'
import axios from 'axios'
import { Datetime } from 'vue-datetime'
import { PrinterIcon } from "vue-feather-icons";
export default {
    props: ['invoices','paymentsByType','sumDailyPayments','invoicesForExcel'],
    components: {
        AppLayout,
        JetInput,
        JetLabel,
        JetConfirmationModal,
        JetButton,
        Datetime,
        PrinterIcon
    },
    data:() =>({

        filter:{
            invoices: [],
            paymentsByType:[],
            sumDailyPayments:[],
            invoicesForExcel:[],
        },
        toExcel: [],
        from: moment().add(-1,'months').format('YYYY-MM-DD').toString(),
        to: moment().format('YYYY-MM-DD').toString(),
        optionsDonut: {
            labels: [],
            title: {
                text: 'Pagos según método',
                align: 'left',
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                fontSize:  '14px',
                fontWeight:  'bold',
                fontFamily:  undefined,
                color:  '#263238'
                },
            },
            colors: ['#58c765','#f54c4f'],
            dropShadow: {
                enabled: true,
                top: 0,
                left: 0,
                blur: 3,
                opacity: 0.5
            }
        },
        seriesDonut: [],
        seriesLine: [
             {
                type: 'line',
                data: []
            }
        ],
        optionsLine: {
            xaxis: {
                categories: []
            },
            title: {
                text: 'Pagos últimos 30 días',
                align: 'left',
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                fontSize:  '14px',
                fontWeight:  'bold',
                fontFamily:  undefined,
                color:  '#263238'
                },
            },
            colors: ['#58c765','#f54c4f'],
            dropShadow: {
                enabled: true,
                top: 0,
                left: 0,
                blur: 3,
                opacity: 0.5
            }
        },
        maxDatetime: moment().format('YYYY-MM-DD').toString()

    }),
    computed:{
        excelName(){
            let from = this.from.toLocaleString().slice(0, 10)
            let to = this.to.toLocaleString().slice(0, 10)

            return `Facturas desde ${from} hasta ${to}`
        },
        minDateTo(){
            var fromDate = moment(this.from).format('YYYY-MM-DD');
            console.log(fromDate)
            return moment(fromDate).add(1,'days').format('YYYY-MM-DD').toString()
        },
        maxDateFrom(){
            return moment().format('YYYY-MM-DD').toString()
        },
        totalInvoiced(){
            let total = 0;
             this.filter.invoices.forEach(invoice => {
                 let current = this.total(invoice)
                 total += current;
             });

            return total;
        },
        totalCoberaged(){
             let coberaged = 0;
             this.filter.invoices.forEach(invoice => {
                 let currentCoberaged = this.coberage(invoice)
                 coberaged += currentCoberaged;
             });

            return coberaged;
        },
        totalDiff(){
            let totalMount = 0;
            let totalCoberaged = 0;
            let total = 0;
            this.filter.invoices.forEach(invoice => {

                let mount = this.total(invoice)
                let coberage = this.coberage(invoice)

                let current = mount - coberage
                total += current


            });


            return total;
        }
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

        filterData(){
            axios.post('reports/filter',{from: this.from, to: this.to})
            .then( data => {
                this.filter.invoices = data.data.invoices;
                this.filter.paymentsByType = data.data.paymentsByType
                this.filter.sumDailyPayments = data.data.sumDailyPayments
                this.filter.invoicesForExcel = data.data.invoicesForExcel
                this.updateCharts();
            })


        },
        print(){
            window.print()
        },
        updateCharts(){
            let byType = this.filter.paymentsByType.map( a => a.pagos);
            let byTypeLabels  = this.filter.paymentsByType.map( a => a.paymentMethod);

            this.seriesDonut = byType;
            this.optionsDonut.labels = byTypeLabels;

            let newPayments = this.filter.sumDailyPayments.map(a => a.mount);

            this.seriesLine = [{
                data: newPayments
            }]

            let dates = this.filter.sumDailyPayments.map(a => a.invoiceDate);
            dates = dates.map( d => moment(d).format('DD/MM/YYYY'));

            this.optionsLine.xaxis.categories = dates
        },
        getExcelData(){
            axios.post('reports/filter?excel=true',{from: this.from, to: this.to})
                .then( data => {
                    this.excelData = data.invoices
                })
        }

    },
    mounted(){

    },
    created(){
        this.filter.paymentsByType = this.paymentsByType;
        this.filter.invoices = this.invoices;
        this.filter.sumDailyPayments = this.sumDailyPayments;
        this.filter.invoicesForExcel = this.invoicesForExcel
        this.updateCharts();
    }
}
</script>
<style  scoped>
    .theme-blue .vdatetime-popup__header,
    .theme-blue .vdatetime-calendar__month__day--selected > span > span,
    .theme-blue .vdatetime-calendar__month__day--selected:hover > span > span {
    background: #252f3f;
    }

.theme-blue .vdatetime-year-picker__item--selected,
.theme-blue .vdatetime-time-picker__item--selected,
.theme-blue .vdatetime-popup__actions__button {
  color: #252f3f;
}
</style>
