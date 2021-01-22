<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reportes
            </h2>
        </template>
            <div class="flex justify-center min-h-screen bg-gray-100 text-gray-800 mx-20">
                <div class="p-4 w-full">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-6 md:col-span-3">
                            <div class="flex flex-row bg-white shadow-sm rounded p-4 justify-center">
                                <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-blue-100 text-blue-500">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                                </div>
                                <div class="flex flex-col flex-grow ml-4">
                                    <div class="text-sm text-gray-500">Facturación</div>
                                    <div class="font-bold text-lg">1</div>
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


export default {
    props: ['users'],
    components: {
        AppLayout,

    },
}
</script>
