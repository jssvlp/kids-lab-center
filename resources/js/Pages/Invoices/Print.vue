<template>
    <Detail :invoice="invoice"/>
</template>
<script>
import Detail from './Detail'
export default {
    props:['invoice'],
    components:{
        Detail
    },
    mounted(){
        setTimeout(function(){ window.print() }, 1);
    }
}
</script>