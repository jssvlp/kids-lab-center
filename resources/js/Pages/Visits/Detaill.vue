<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles visita 
            </h2>
            
        </template>
        <div class="py-2">
                <div class="max-w-2xl mx-auto sm:px-2">
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-6 mt-5 align-middle shadow-md rounded-md bg-white inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="flex justify-center">
                                    <img class="h-15 w-15 shadow-md rounded-full" :src="visit.child.gender == 'Niño' ?'/Images/boy.svg':'/Images/girl.svg'" alt="">
                                </div>
                                <div class="flex mt-3">
                                    <div class="mx-3">
                                       Nombre: <span class="font-bold"> {{visit.child.name}}</span> 
                                    </div>
                                    <div class="mx-3 ml-12">
                                       Fecha de nacimiento: <span class="font-bold"> {{visit.child.birth_date}}</span> 
                                    </div>
                                    <div></div>
                                </div>
                                <div class="flex mt-2">
                                    <div class="mx-3">
                                       Padre: <span class="font-bold"> {{ visit.child.dad_or_mom.name }}</span> 
                                    </div>
                                </div>
                            </div>
                            <div class="py-6 mt-3 align-middle shadow-md rounded-md bg-white inline-block min-w-full sm:px-6 lg:px-8">
                                
                                <div class="flex justify-between">
                                    <div class="mx-3">
                                       Fecha visita: <span class="font-bold"> {{visit.visit_date}}</span> 
                                    </div>
                                    <div class="mr-3">
                                       Facturado: <span class="font-bold" :class="visit.invoiced ? 'text-green-600' : 'text-red-600'"> {{visit.invoiced == true ? "Si" : "No"}}</span> 
                                    </div>
                                    <div>
                                    </div>
                                    <div></div>
                                </div>
                            </div>

                            <div class="py-6 align-middle pb-10 shadow-md rounded-md bg-white mt-3 inline-block min-w-full sm:px-6 lg:px-8">
                                Vacunas colocadas:
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
                                            {{vaccine.price}}
                                        </div>
                                    </td>
                                    
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="font-bold">Total</span>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="font-bold text-green-700">RD$ {{total}}</span>
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
export default {
    props: ['visit'],
    components: {
        AppLayout,
        Input,
        EditOrNew,
        JetConfirmationModal,
        JetSecondaryButton,
        JetDangerButton
    },
    data:() =>({
    }),
    computed:{
        total(){
            return this.visit.vaccines.reduce(function(a, b){
               console.log(a)
               return a + b.price;
            }, 0);
        }
    },
    methods:{
       
    },
    mounted(){
        
    }
}
</script>
