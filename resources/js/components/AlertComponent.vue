<template>
    <div :class="style" role="alert">
        {{ mensaje  }}
    </div>
</template>

<script>
    export default {
        computed:{
            style(){
                return 'alert alert-'+this.tipo
            }
        },
        props:['tipo','mensaje']
    }
</script>
