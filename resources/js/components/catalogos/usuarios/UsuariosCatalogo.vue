<template>

    <div>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">
                        <h3>Administracion de Usuarios</h3>
                    </div>
                    <div style="text-align:right;" class="col-md-2 my-auto">
                        <button @click="MostrarModal()" class="btn btn-primary">Nuevo <i class="fas fa-plus-circle"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">

                        <table class="table">
                            <thead class="thead">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr :key="index" v-for="(usuario,index) of Usuarios" >

                                    <td>{{ usuario.nombre }}</td>
                                    <td>{{ usuario.apellido }}</td>
                                    <td>{{ usuario.username }}</td>
                                    <td>{{ usuario.email }}</td>
                                    <td>
                                        <button @click="Buscar(usuario.id)" class="btn btn-primary"><i class="fas fa-pencil-alt "></i></button>
                                        <button @click="Eliminar(usuario.id)" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </td>

                                </tr>
                                
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <modal-template
            size="modal-lg"
            IdModal="modal_usuario"
            titulo="Nuevo"
            :esnuevo=EsNuevo
        >
                
            <div slot="formulario">
                
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Nombre</label>
                    <div class="col-sm-10">
                        <input v-model="nombre"  type="text" class="form-control" id="inputEmail3" placeholder="Ingrese los nombres">
                        <span class="errores" v-if="Errores.nombre"> {{ Errores.nombre[0] }} </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Apellido</label>
                    <div class="col-sm-10">
                        <input v-model="apellido" type="text" class="form-control" id="inputEmail3" placeholder="Ingrese los apellidos">
                        <span class="errores" v-if="Errores.apellido"> {{ Errores.apellido[0] }} </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input v-model="email" type="email" class="form-control" id="inputEmail3" placeholder="Ingrese el correo electronico">
                        <span class="errores" v-if="Errores.email"> {{ Errores.email[0] }} </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input v-model="username" type="text" class="form-control" id="inputEmail3" placeholder="Ingrese el nombre de usuario">
                        <span class="errores"  v-if="Errores.username">{{ Errores.username[0] }}</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input v-model="password" type="password" class="form-control" id="inputEmail3" placeholder="Ingrese la contraseña">
                        <span class="errores" v-if="Errores.password">{{ Errores.password[0] }}</span>
                    </div>
                </div>

            </div>

            <div slot="acciones">
                <button v-if="EsNuevo" @click="Guardar()" class="btn btn-primary">Guardar <i class="fas fa-save"></i></button>
                <button v-else @click="Modificar()" class="btn btn-success">Guardar <i class="fas fa-save"></i></button>
            </div>

        </modal-template>

    </div>
    
</template>

<style>



</style>


<script>
export default {
    
    data(){
        return{

            //variable para almacenar los usuarios consultados
            Usuarios : [],

            //Esteas variable son para los modelos de los formularios
            nombre : "",
            apellido : "",
            username : "",
            email : "",
            password : "",
            id : null,

            //variable para verficiar si el regitro es nuevo o no
            EsNuevo : true,

            //variable array para guardar los errores de los datos
            Errores : []

        }
    },

    methods:{

        MostrarModal(){

            this.EsNuevo = true;

            this.LimpiarModelo();

            this.Errores = [];

            $("#modal_usuario").modal()

        },

        Guardar(){

            let Json = {
                nombre : this.nombre,
                apellido : this.apellido,
                email : this.email,
                password : this.password,
                username : this.username
            };

            axios.post(
                'api/usuario',
                {"Json" : JSON.stringify(Json)}
            ).then( (Respuesta) => {

                console.log(Respuesta.data.usuario);

                this.LimpiarModelo();
                
                this.EsNuevo = true;

                this.Listar();

                this.Errores = [];

                $("#modal_usuario").modal('hide');

                swal(
                    'Bien',
                    'Usuario Agregado!',
                    'success'
                );

            }).catch( (error) => {

                if(error.response.status == 500){

                    this.Errores = error.response.data.errors;

                }

            });

        },

        Modificar(){

            let Json = {
                id : this.id,
                nombre : this.nombre,
                apellido : this.apellido,
                email : this.email,
                password : this.password,
                username : this.username
            };

            axios.post(
                'api/usuario/update',
                {"json" : JSON.stringify(Json)}
            ).then( (Respuesta) => {

                this.Usuarios = Respuesta.data;

                this.id = null;

                this.Listar();

                $("#modal_usuario").modal('hide');

                swal(
                    'Bien',
                    'Usuario Actualizado!',
                    'success'
                );

            }).catch( (error) => {

                if(error.response.status == 500){

                    this.Errores = error.response.data.errors;

                }

            });


        },

        Eliminar(id){

            let json = {
                id : id
            }

            axios.post(
                'api/usuario/delete',
                {'json' : JSON.stringify(json)}
            ).then( (Response) => {

                this.Listar();

                swal(
                    'Bien',
                    'Usuario Eliminado!',
                    'success'
                );

            });

        },

        Listar(){

            axios.get(
                'api/usuario'
            ).then( (Respuesta) => {

                this.Usuarios = Respuesta.data;

            });

        },

        Buscar(id){

            axios.get(
                'api/usuario/' + id 
            ).then( (Respuesta) => {

                this.id = Respuesta.data.id;

                this.nombre = Respuesta.data.nombre;
                this.apellido = Respuesta.data.apellido;
                this.email = Respuesta.data.email;
                this.password = Respuesta.data.password;
                this.username = Respuesta.data.username;

                this.EsNuevo = false;

                $("#modal_usuario").modal();

            });

        },

        LimpiarModelo(){

            this.username = "";
            this.password = "";
            this.email = "";
            this.nombre = "";
            this.apellido = "";

        }

    },

    created(){

        this.Listar();

    }

    

}
</script>

