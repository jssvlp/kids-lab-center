<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle factura
            </h2>
        </template>
       <vue-html2pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="false"
            :preview-modal="true"
            :paginate-elements-by-height="1400"
            :filename="filename"
            :pdf-quality="2"
            :manual-pagination="true"
            pdf-format="a4"
            pdf-orientation="portrait"
            pdf-content-width="800px"
    
           
            ref="html2Pdf"
        >
            <section slot="pdf-content">
                <Detail :invoice="invoice" class="mt-2"/>
            </section>
        </vue-html2pdf>
        
    </app-layout>
</template>

<script>
import { PrinterIcon } from "vue-feather-icons";
import Detail from './Detail'
import AppLayout from '@/Layouts/AppLayout'
import VueHtml2pdf from 'vue-html2pdf'
export default {
    props: ['invoice'],
    components:{
        Detail,
        AppLayout,
        PrinterIcon,
        VueHtml2pdf
    },
    mounted(){
        window.scroll(100,100)
        this.generateReport();
    },
    computed:{
        filename(){
            return 'Factura ' + this.invoice.visit.child.name
        }
    },
    methods:{
        generateReport () {
            this.$refs.html2Pdf.generatePdf()
        }
    }
}
</script>
