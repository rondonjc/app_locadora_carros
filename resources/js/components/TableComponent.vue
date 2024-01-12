<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col"  v-for="titulo in titulos" class="text-uppercase">{{titulo}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj in datos" :key="obj.id">
                    <td v-for="valor,chave in columnas(obj)" :key="chave">
                         {{ valor }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</template>

<script>
    export default {
        methods:{
            columnas(obj){

                for (let clave in obj) {
                    if (!this.titulos.includes(clave)) {
                            delete obj[clave]
                    }
                }

                return obj;
            }
        },
        props:['datos','titulos'],
        mounted() {
        }
    }
</script>
