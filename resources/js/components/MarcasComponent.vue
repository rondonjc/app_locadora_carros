<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Card listaje de marca-->
                <card-component titulo="Buscar Marca">
                    <template v-slot:contenido>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container titulo="ID" id="inputId" idHelp ="idHelp" texto-ayuda="Opcional. Informe el ID de la marca.">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp">
                                </input-container>
                            </div>
                            <div class="col mb-3">
                                <input-container titulo="Nombre de la Marca" id="inputNombre" idHelp ="idNombre" texto-ayuda="Opcional. Informe el nombre de la marca.">
                                    <input type="text" class="form-control" id="inputNombre" aria-describedby="idNombre">
                                </input-container>
                            </div>
                        </div>
                    </template>
                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm float-end">Buscar</button>
                    </template>
                </card-component>
                <!-- Card Relacion de marca-->
                <card-component titulo="Relacion de Marcas">
                    <template v-slot:contenido>
                        <table-component></table-component>
                    </template>
                    <template v-slot:footer>
                        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>
    </div>
    <modal-component id="modalMarca" titulo="Adicionar Marca">
        <template v-slot:body>
            <div class="row">
                <div class="mb-3">
                    <input-container titulo="Nombre de la Marca" id="inputNombreAdd" idHelp ="nombreHelpAdd" texto-ayuda="Informe el nombre de la marca.">
                        <input type="text" class="form-control" id="inputNombreAdd" v-model="inputNombreAdd" aria-describedby="nombreHelpAdd">
                    </input-container>
                </div>
                <div class="mb-3">
                    <input-container titulo="Imagen de la Marca" id="inputImagenAdd" idHelp ="idImagenAdd" texto-ayuda="Seleccione una imagen en formato png">
                        <input type="file" class="form-control" id="inputImagenAdd" aria-describedby="idImagenAdd" placeholder="Seleccione un archivo" @change="carregarImagen($event)">
                    </input-container>
                </div>
                {{ inputNombreAdd }} - {{ inputImagenAdd }}
            </div>
        </template>
        <template v-slot:footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" @click="guardarMarca()">Guardar</button>
        </template>
    </modal-component>
</template>

<script>
    export default {

        data(){
            return {
                inputNombreAdd:'',
                inputImagenAdd:[]
            }
        },
        methods:{
            carregarImagen(e){
                this.inputImagenAdd = e.target.files
            },
            guardarMarca(){
                console.log(this.inputImagenAdd,this.inputImagenAdd )
            }
        }

    }
</script>
