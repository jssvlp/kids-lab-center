<template>
    <div>
        <div class="flex justify-center min-h-screen bg-gray-100 text-gray-800">
            <div class="p-4 w-full">
                <div class="grid grid-cols-12 gap-4">
                    <inertia-link :href="route('parents.index')" class="col-span-12 sm:col-span-6 md:col-span-3">
                        <div class="flex flex-row bg-white shadow-sm rounded p-4">
                        <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-blue-100 text-blue-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                        </div>
                        <div class="flex flex-col flex-grow ml-4">
                            <div class="text-sm text-gray-500">Padres/Madres</div>
                            <div class="font-bold text-lg">{{data.parents}}</div>
                        </div>
                        </div>
                    </inertia-link>
                    <inertia-link :href="route('children.index')" class="col-span-12 sm:col-span-6 md:col-span-3">
                        <div class="flex flex-row bg-white shadow-sm rounded p-4">
                        <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-green-100 text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>                    </div>
                        <div class="flex flex-col flex-grow ml-4">
                            <div class="text-sm text-gray-500">Pacientes (niños/as)</div>
                            <div class="font-bold text-lg">{{data.children}}</div>
                        </div>
                        </div>
                    </inertia-link>
                    <inertia-link :href="route('insurances.index')" class="col-span-12 sm:col-span-6 md:col-span-3">
                        <div class="flex flex-row bg-white shadow-sm rounded p-4">
                        <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-orange-100 text-orange-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="flex flex-col flex-grow ml-4">
                            <div class="text-sm text-gray-500">Aseguradoras</div>
                            <div class="font-bold text-lg">{{data.insurances}}</div>
                        </div>
                        </div>
                    </inertia-link>
                    <inertia-link :href="route('visits.index')" class="col-span-12 sm:col-span-6 md:col-span-3">
                        <div class="flex flex-row bg-white shadow-sm rounded p-4">
                            <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-red-100 text-red-500">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>                    
                            </div>
                            <div class="flex flex-col flex-grow ml-4">
                                <div class="text-sm text-gray-500">Visitas</div>
                                <div class="font-bold text-lg">{{data.visits}}</div>
                            </div>
                        </div>
                    </inertia-link>
                    </div>
                </div>
        </div>
    </div>
</template>

<script>
    import JetApplicationLogo from '@/Jetstream/ApplicationLogo'

    export default {
        props:['data'],
        components: {
            JetApplicationLogo,
        },
    }
</script>
