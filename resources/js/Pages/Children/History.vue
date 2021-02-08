<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Historial
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
                <div class="py-12">
                    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                            <div class="py-6 px-10  align-middle">
                                        <div class="flex justify-center mb-5">
                                            <img src="/Images/klc-logo.png" style="width:100%; max-width:250px;">
                                        </div>
                                        <div class="flex justify-center mb-10">
                                            <div class="text-xl font-bold">Historial de Visitas y Vacunas</div>
                                        </div>
                                        <div class="flex  justify-between">
                                            <div class="">
                                            Nombre: <span class="font-bold"> {{child.name}}</span> 
                                            </div>
                                            <div class="">
                                            Fecha de nacimiento: <span class="font-bold"> {{child.birth_date | formatShortDate}}</span> 
                                            </div>
                                            <div></div>
                                        </div>
                                        <div class="flex  justify-between">
                                            <div class="">
                                            Padre: <span class="font-bold"> {{ child.dad_or_mom.name }}</span> 
                                            </div>
                                            <div class="">
                                            Plan: <span class="font-bold"> {{ child.plan.name }} de {{child.plan.insurance.name}}</span> 
                                            </div>
                                        </div>

                                        <div class="flex justify-center my-4">
                                            <div class="text-lg">Visitas</div>
                                        </div>

                                        <div id="visits">
                                            <div v-for="(visit,index) in visits" :key="visit.id" :class="{'html2pdf__page-break' : index % 2 != 0}" class="mb-5 shadow-md rounded-md px-6 py-4 bg-gray-100">
                                                <div class="flex justify-end capitalize">
                                                <span class="bg-trendy-pink-500 text-white p-1 rounded-md"> {{toOrdinal(child.visits.length - index)}} Visita</span>
                                                </div>
                                                <div class="mx-10 mb-5">
                                                    <div>Fecha: {{visit.visit_date | formatShortDate}}</div>
                                                    <div>Edad: {{child.age}}</div>
                                                    <div class="font-bold mt-5">Vacunas colocadas</div>
                                                    <ul id="vaccines" class="ml-5 list-disc">
                                                        <li v-for="vaccine in visit.vaccines" :key="vaccine.id">
                                                            {{vaccine.name}}
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>  
                                </div>
                        </div>
                    </div>
                </div>
            </section>
        </vue-html2pdf>
        
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
const ordinales = require('ordinales-js');
import VueHtml2pdf from 'vue-html2pdf'
export default {
    props: ['child'],
    components: {
        AppLayout,
        VueHtml2pdf
    },
    data: () =>({
        visits: []
    }),
    computed:{
        filename(){
            return 'Historial ' + this.child.name
        }
    },
    methods:{
        toOrdinal(number){
            return ordinales.toOrdinal(number, 'f')
        },
        generateReport () {
            this.$refs.html2Pdf.generatePdf()
        },
        hasBreakPoint(index){
            
            if( index > 0 && index % 2 == 0){
                console.log(index)
                return true
            }
            return false
        }
    },
    mounted(){
        this.visits = this.child.visits.reverse();
        this.generateReport();
    }
}
</script>
<style  scoped>
    .purple{
        background-color: #795380;
    }
</style>