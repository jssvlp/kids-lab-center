<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden  sm:rounded-lg">
                    <Statistics :data="statistics"/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Statistics from '@/Components/Statistics'
    import axios from 'axios'

    export default {
        props:['statistics'],
        components: {
            AppLayout,
            Statistics,
        },
        mounted(){
            const headers = {
                'Token': 'Tyf1Rq6QZJnOxhS3lrNt'
            }
            axios.get('http://127.0.0.1:5000/',{
                    headers: headers
            }).then( r => {
                console.log(r.data)
            })
        }
    }
</script>
