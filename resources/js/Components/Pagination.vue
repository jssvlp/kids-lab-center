<template>
    <ul class="flex justify-center mt-3">
        <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-500 rounded-lg">
            <a v-on:click.prevent="setPage(index - 1)" class="flex items-center font-bold" href="#">
                <span class="mx-1">Anterior</span>
            </a>
        </li>

        <li v-for="(index) in pages" :key="index" class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-gray-200 rounded-lg" :class="index == currentPage ?'hover:bg-gray-700' : 'bg-gray-200'">
            <a v-on:click.prevent="setPage(index)" class="font-bold"  href="#">{{index}}</a>
        </li>
        <li class="mx-1 px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-gray-200 rounded-lg">
            <a v-on:click.prevent="setPage(index + 1)" class="flex items-center font-bold" href="#">
                <span class="mx-1">Siguiente</span>
            </a>
        </li>
    </ul>
</template>

<script>
export default {
    props:["pages","init", "end"],
    data: () =>({
        currentPage : 1,
    }),

    methods: {
        next(){

        },
        previous(){

        },
        setPage(page){
            console.log(page)
            this.currentPage = page;
        }
    }

}
</script>

<style>

</style>