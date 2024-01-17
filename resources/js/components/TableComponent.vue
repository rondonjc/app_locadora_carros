<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col"  v-for="titulo in datos.titulos" :key="titulo">{{titulo.titulo}}</th>
                    <th v-if="datos.visualizar||datos.editar||datos.eliminar" ></th>
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
                    <td  v-if="datos.visualizar||datos.editar||datos.eliminar">
                        <button v-if="datos.visualizar" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalMarcaVisualizar" >Visualizar</button>
                        <button v-if="datos.editar" class="btn btn-outline-primary btn-sm" >Editar</button>
                        <button v-if="datos.eliminar" class="btn btn-outline-danger btn-sm" >Eliminar</button>
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
