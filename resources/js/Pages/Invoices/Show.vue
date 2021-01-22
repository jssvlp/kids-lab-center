<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle factura
            </h2>
        </template>
        <div class="bg-white">
            <div class="flex justify-end w-3/4 mb-2">
                <a :href="route('invoices.print',invoice.id)"  target= '_blank' class="mt-1" >
                    <PrinterIcon size="1.5x"/>
                </a>
            </div>
            <Detail :invoice="invoice" class="mt-2"/>
        </div>
       
        
    </app-layout>
</template>

<script>
import { PrinterIcon } from "vue-feather-icons";
import Detail from './Detail'
import AppLayout from '@/Layouts/AppLayout'
export default {
    props: ['invoice'],
    components:{
        Detail,
        AppLayout,
        PrinterIcon
    },
    mounted(){
        window.scroll(100,100)
    }
}
</script>
