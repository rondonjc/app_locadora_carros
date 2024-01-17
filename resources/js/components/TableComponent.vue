<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col"  v-for="titulo in datos.titulos" :key="titulo">{{titulo.titulo}}</th>
                    <th v-if="datos.visualizar.visible||datos.editar.visible||datos.eliminar.visible" ></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj in datosFiltrador" :key="obj.id">
                    <td v-for="titulo, chave in datos.titulos" :key="titulo" >
                        <span v-if="titulo.tipo=='imagen'">
                            <img :src="'/storage/'+obj[chave]"  width="50" height="50">
                        </span>
                        <span v-else-if="titulo.tipo=='data'">
                            {{ formatearFecha(obj[chave]) }}
                        </span>
                        <span v-else>
                            {{ obj[chave] }}
                        </span>
                    </td>
                    <td  v-if="datos.visualizar.visible||datos.editar.visible||datos.eliminar.visible">
                        <button v-if="datos.visualizar.visible" class="btn btn-outline-primary btn-sm" :data-bs-toggle="datos.visualizar.dataToggle" :data-bs-target="datos.visualizar.dataTarget" @click="setStore(obj)" >Visualizar</button>
                        <button v-if="datos.editar.visible" class="btn btn-outline-primary btn-sm" :data-bs-toggle="datos.editar.dataToggle" :data-bs-target="datos.editar.dataTarget" @click="setStore(obj)" >Editar</button>
                        <button v-if="datos.eliminar.visible" class="btn btn-outline-danger btn-sm" :data-bs-toggle="datos.eliminar.dataToggle" :data-bs-target="datos.eliminar.dataTarget" @click="setStore(obj)" >Eliminar</button>
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
            },
            formatearFecha(fechaISO){
                var fecha = new Date(fechaISO);

                // Obtener componentes de la fecha
                var dia = fecha.getDate();
                var mes = fecha.getMonth() + 1; // Los meses en JavaScript van de 0 a 11
                var año = fecha.getFullYear(); // Obtener los últimos dos dígitos del año

                // Añadir ceros iniciales si es necesario
                dia = dia < 10 ? '0' + dia : dia;
                mes = mes < 10 ? '0' + mes : mes;
                año = año < 10 ? '0' + año : año;

                // Construir la cadena de fecha formateada
                var fechaFormateada = dia + '/' + mes + '/' + año;

                return fechaFormateada
            },
            setStore(obj){
                this.$store.state.item = obj;
            }

        },
        props:['datos','titulos'],
        mounted() {
        },
        computed:{
            datosFiltrador(){
                let campos = Object.keys(this.datos.titulos)
                let columnas = this.datos.datos.data.map((obj,key)=>{
                    for (let clave in obj) {
                        if (!campos.includes(clave)) {
                                delete obj[clave]
                        }
                    }
                    return obj;
                })
                return columnas;
            }
        }
    }
</script>
