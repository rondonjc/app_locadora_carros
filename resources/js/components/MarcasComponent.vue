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
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" v-model="buscar.id">
                                </input-container>
                            </div>
                            <div class="col mb-3">
                                <input-container titulo="Nombre de la Marca" id="inputNombre" idHelp ="idNombre" texto-ayuda="Opcional. Informe el nombre de la marca.">
                                    <input type="text" class="form-control" id="inputNombre" aria-describedby="idNombre" v-model="buscar.nombre">
                                </input-container>
                            </div>
                        </div>
                    </template>
                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm float-end" @click="buscarLista()">Buscar</button>
                    </template>
                </card-component>
                <!-- Card Relacion de marca-->
                <card-component titulo="Relacion de Marcas">
                    <template v-slot:contenido>
                        <table-component :datos="marcas" :titulos="['id','imagen','nombre']" ></table-component>
                    </template>
                    <template v-slot:footer>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li class="page-item" v-for="l,key in marcas.datos.links" @click="paginacion(l)">
                                        <a :class="l.active?'page-link active':'page-link'"  v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar -->
    <modal-component id="modalMarca" titulo="Adicionar Marca">
        <template v-slot:alert v-if="mostarAlert">
            <alert-component :tipo="alertTipo"  :mensaje="alertMensaje" ></alert-component>
        </template>
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
            </div>
        </template>
        <template v-slot:footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" @click="guardarMarca()">Guardar</button>
        </template>
    </modal-component>

    <!-- Modal Visualizar -->
    <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca">
        <template v-slot:alert v-if="mostarAlert">
            <alert-component :tipo="alertTipo"  :mensaje="alertMensaje" ></alert-component>
        </template>
        <template v-slot:body>
            <div class="row">
                <div class="mb-3">
                    <input-container titulo="Nombre de la Marca" id="inputNombreVisualizar" idHelp ="nombreHelpVisualizar" texto-ayuda="Informe el nombre de la marca.">
                        <input type="text" class="form-control" id="inputNombreVisualizar" v-model="visualizar.id" aria-describedby="nombreHelpVisualizar">
                    </input-container>
                </div>
                <div class="mb-3">

                    <input-container titulo="Imagen de la Marca" id="inputImagenVisualizar" idHelp ="idImagenVisualizar" texto-ayuda="Seleccione una imagen en formato png">
                        <input type="file" class="form-control" id="inputImagenVisualizar" aria-describedby="idImagenVisualizar" placeholder="Seleccione un archivo" @change="carregarImagen($event)">
                    </input-container>
                </div>
            </div>
        </template>
        <template v-slot:footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </template>
    </modal-component>
</template>

<script>
import axios from 'axios';

    export default {
        computed:{
            token(){
                let token = document.cookie.split(';').find(indixe=>{
                    return indixe.startsWith('token=')
                });
                token = token.split('=')[1];
                return token;
            }
        },
        data(){
            return {
                urlBase: 'http://127.0.0.1:8000/api/v1/marca',
                urlPaginacion: '',
                urlFiltro: '',
                inputNombreAdd:'',
                inputImagenAdd:[],
                visualizar:{
                    id:'',
                    nombre:''
                },
                mostarAlert:false,
                alertMensaje: '',
                alertTipo:'',
                marcas:{
                    titulos:{
                        id:{titulo:'ID',tipo:'text'},
                        nombre:{titulo:'Nombre',tipo:'text'},
                        imagen:{titulo:'Imagen',tipo:'imagen'},
                        created_at:{titulo:'Fecha de Creación',tipo:'data'},
                    },
                    datos:{ data:[]},
                    visualizar:true,
                    editar:true,
                    eliminar:true

                },
                buscar:{
                    id:'',
                    nombre:''
                }
            }
        },
        methods:{
            buscarLista(){
                let filtro = '';
                for(let key in this.buscar){
                    if(this.buscar[key]!=''){
                        if(filtro!='')
                            filtro+=',';

                        filtro+=key+':like:%'+this.buscar[key]+'%';
                    }

                }

                if(filtro!=""){
                    this.urlPaginacion = 'page=1'
                    this.urlFiltro = '&filtro='+filtro;
                }else{
                    this.urlFiltro = '';
                }
                this.cargarLista()
            },
            cargarLista(){
                let config = {
                    headers:{
                        'Accept':'application/json',
                        'Authorization':this.token
                    }
                }

                let url = this.urlBase + '?' +this.urlPaginacion + this.urlFiltro

                axios.get(url,config)
                .then(response=>{
                    this.marcas.datos = response.data

                })
                .catch(error=>{
                    console.log(error.data)
                })
            },
            carregarImagen(e){
                this.inputImagenAdd = e.target.files
            },
            guardarMarca(){
                let formData = new FormData();
                formData.append('nombre',this.inputNombreAdd);
                formData.append('imagen',this.inputImagenAdd[0]);

                let config = {
                    headers:{
                        'Content-Type':'multipart/form-data',
                        'Accept':'application/json',
                        'Authorization':this.token
                    }
                }

                axios.post(this.urlBase,formData,config)
                .then(response=>{
                    this.alertMensaje = "Marca creada correctamente";
                    this.mostarAlert = true;
                    this.alertTipo = 'success';
                })
                .catch(error=>{
                    this.mostarAlert = true;
                    this.alertTipo = 'danger';
                    this.alertMensaje = error.response.data.message;
                })
            },
            paginacion(link){
                if(link.url){
                    this.urlPaginacion = link.url.split('?')[1];
                    this.cargarLista();
                }
            }
        },
        mounted(){
            this.cargarLista();
        }

    }
</script>
