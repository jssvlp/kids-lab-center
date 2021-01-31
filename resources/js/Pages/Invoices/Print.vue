<template>
    <Detail :invoice="invoice"/>
</template>
<script>
import Detail from './Detail'
import NProgress from 'nprogress'
export default {
    props:['invoice'],
    components:{
        Detail
    },
    mounted(){
        NProgress.start()
        setTimeout(function(){ 
            window.print()
            NProgress.done()
        }, 1);
    }
}
</script>