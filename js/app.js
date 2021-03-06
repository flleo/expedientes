

var app = new Vue({

    el: "#root",
    created: function () {
        console.log("Expedientes");
        this.getAllExpedientes();
        /*this.getAllPaises();
         this.getAllTipoUsuarios();
         this.getAllCalificaciones();
         */
    },
    data: {
        showingCalificar: false,
        showingTecnico: false,
        showingAdministrador: false,
        showingAdministrativo: false,
        showingNoLogueado: true,
        showingUsuario: false,
        showingPersona: false,
        showingDireccion: false,
        showingRegistrarse: false,
        showingLogin: false,
        showingNuevoExpediente: false,
        showingEditarExpediente: false,
        showingDeleteExpediente: false,
        showingExpedientes: true,
        errorMessage: "",
        successMessage: "",
        selectedPais: "España",
        selectedProvincia: "Santa Cruz de Tenerife",
        calificaciones: [],
        direcciones: [],
        estados: [],
        expedientes: [],
        IAEs: [],
        localidades: [],
        municipios: [],
        paises: [],
        provincias: [],
        proyectistas: [],
        tipoExpedientes: [],
        tipoUsuarios: [],
        titulares: [],
        urgentes: [],
        IAE: {},
        pais: {id: 0, pais: "España"},
        persona: {id: 0, dni: "", nombre: "", idDireccion: 0, email: "", telefono: ""},
        provincia: {id: 0, idPais: 0, provincia: ""},
        municipio: {id: 0, idProvincia: 0, municipio: ""},
        localidad: {id: 0, idProvincia: 0, localidad: ""},
        direccion: {id: 0, idPais: 0, idProvincia: 0, idMunicipio: 0, idLocalidad: 0, codPostal: "", direccion: ""},
        login: {usuario: "", contraseña: ""},
        tipoUsuario: {id: 0, tipo: ""},
        newExpediente: {idUrgente: 0, idTipoExpediente: 0, fecha: "", numero: "", idTitular: 0, idDireccion: 0, idProyectista: 0, idCalificacion: 0, idIAE: 0, descripcion: ""},
        newUsuario: {},
        newCalificacion: {id: 0, idExpediente: "", idTecnico: 0, idCalificaciones: 0},
        estado: {},
        expediente: {},
        tipoExpediente: {id: 0, tipo: ""},
        usuario: {id: 0, idPersona: 0, idTipoUsuario: 0, usuario: "", contraseña: ""},
        usuarioLogueado: {usuario: ""},
        urgente: {},
        calificacion: {},
        clickedRegistrarse: {},
    },
    /* watch: {
     paises: function (newVal) {
     this.getProvincias(newVal);
     }
     },
     */
    methods: {

        //Calificacion////////

        calificar: function () {
            console.log(app.newCalificacion.id, app.newCalificacion.idCalificaciones, app.newCalificacion.idExpediente, app.newCalificacion.idTecnico);
            var formData = app.toFormData(app.newCalificacion);
            axios.post("http://localhost/expedientes/php/api.php?action=grabarCalificacion", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.newCalificacion.id = response.data.id;
                            app.expediente.idCalificacion = app.newCalificacion.id;
                            app.expediente.idEstado = 1;
                            app.updateExpediente();
                            app.successMessage = response.data.message;
                            app.newCalificacion = {id: 0, idExpediente: "", idTecnico: 0, idCalificaciones: 0};
                        }
                    });
        },
        getAllCalificaciones: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarCalificaciones")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.calificaciones = response.data.calificaciones;
                        }
                    });
        },
        setNewCalificacion: function (e) {
            app.newCalificacion.id = e.idCalificacion;
            app.newCalificacion.idExpediente = e.numero;
            app.newCalificacion.idTecnico = app.usuario.id;
        },
        setNewCalificacionIdCalificaciones: function (c) {
            app.newCalificacion.idCalificaciones = c.id;
        },

        //Expedientes//////////////////////////////////////////////////////////////

        deleteExpediente: function () {

            var formData = app.toFormData(app.expediente);
            axios.post("http://localhost/expedientes/php/api.php?action=deleteExpediente", formData)
                    .then(function (response) {
                        console.log(response);
                        //app.expediente = {};
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.successMessage = response.data.message;
                            app.getAllExpedientes();
                        }
                    });
        },
        getAllExpedientes: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarExpedientes")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.expedientes = response.data.expedientes;
                        }
                    });
        },
        getExpedientesTecnico: function () {
            //console.log(app.usuario.id,app.usuario.idPersona,app.usuario.idTipoUsuario, app.usuario.usuario,app.usuario.contraseña,"$$$$$");
            var dataFormat = app.toFormData(app.usuario);
            axios.post("http://localhost/expedientes/php/api.php?action=mostrarExpedientesTecnico", dataFormat)
                    .then(function (response) {

                        console.log(response);
                        if (response.data.error) {

                            app.errorMessage = response.data.message;
                        } else {
                            app.expedientes = response.data.expedientes;
                        }
                    });
        },
        generaNumeroExpediente: function () {
            /*  var formData = app.toFormData(app.expediente);
             axios.post("http://localhost/expedientes/php/api.php?action=generaNumeroExpediente", formData)
             .then(function (response) {
             console.log(response);
             if (response.data.error) {
             app.errorMessage = response.data.message;
             } else {
             app.successMessage = response.data.message;
             app.getAllExpedientes();
             }
             });*/
        },
        selectExpediente: function (expediente) {
            app.expediente = expediente;
        },

        updateExpediente: function () {
            console.log(app.expediente.id, app.expediente.idUrgente, app.expediente.idTipoExpediente, app.expediente.fecha, app.expediente.numero, app.expediente.idTitular, app.expediente.idDireccion, app.expediente.idProyectista, app.expediente.idCalificacion, app.expediente.idIAE, app.expediente.idEstado, app.expediente.descripcion);
            var formData = app.toFormData(app.expediente);
            /* axios.post("http://localhost/expedientes/php/api.php?action=updateExpediente", formData)
             .then(function (response) {
             console.log(response);
             //app.expediente = {};
             if (response.data.error) {
             app.errorMessage = response.data.message;
             } else {
             app.successMessage = response.data.message;
             app.getAllExpedientes();
             }
             });*/
        },
        //Login//////////////////////////////////////
        comprobarLogin: function () {
            var formData = app.toFormData(app.login);
            axios.post("http://localhost/expedientes/php/api.php?action=comprobarLogin", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.usuarioLogueado.usuario = app.login.usuario;
                            app.login = {};
                            app.usuario = response.data.usuario;
                            app.successMessage = response.data.message;
                            app.showingNoLogueado = false;
                            app.sesionTipoUsuario();
                        }
                    });
        },
        //NewExpedinte///////////////////////////////////////////////////////////////////////////
        //
        //Cargar combo expediente/////////////////
        cargarCombosExpediente() {
            app.getAllUrgente();
            app.getAllTipoExpediente();
            app.getAllTitulares();
            app.getAllProyectistas();
            app.getAllDirecciones();
            app.getAllIAEs();
            app.getAllEstados();
        },

        creaExpediente: function () {
            console.log(app.newExpediente.id, app.newExpediente.idUrgente, app.newExpediente.idTipoExpediente, app.newExpediente.fecha, app.newExpediente.idTitular, app.newExpediente.idDireccion, app.newExpediente.idProyectista, app.newExpediente.idCalificacion, app.newExpediente.idIAE, app.newExpediente.idEstado, app.newExpediente.direccion);
            /*   var formData = app.toFormData(app.newExpediente);
             axios.post("http://localhost/expedientes/php/api.php?action=creaExpediente", formData)
             .then(function (response) {
             console.log(response);
             app.newExpediente = {idUrgente: 0, idTipoExpediente: 0, fecha: "", numero: "", idTitular: 0, idDireccion: 0, idProyectista: 0, idCalificacion: 0, idIAE: 0, descripcion: ""};
             if (response.data.error) {
             app.errorMessage = response.data.message;
             } else {
             app.expediente.id = response.data.id;
             app.generaNumeroExpediente();
             app.successMessage = response.data.message;
             app.getAllExpedientes();
             }
             });*/
        },
        //Direccion
        getAllDirecciones: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarDirecciones")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.direcciones = response.data.direcciones;
                        }
                    });
        },
        setNewExpedienteIdDireccion(d) {
            app.newExpediente.idDireccion = d.id;
        },
        //Estado
        getAllEstados: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarEstados")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.estados = response.data.estados;
                        }
                    });
        },
        setNewExpedienteIdEstado(d) {
            app.newExpediente.idEstado = d.id;
        },

        //IAE
        getAllIAEs: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarIAEs")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.IAEs = response.data.IAEs;
                        }
                    });
        },
        setNewExpedienteIdIAE(d) {
            app.newExpediente.idIAE = d.id;
        },

        //TipoExpediente////////////
        getAllTipoExpediente: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarTipoExpedientes")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.tipoExpedientes = response.data.tipoExpedientes;
                        }
                    });
        },
        setNewExpedienteIdTipoExpediente(t) {
            app.newExpediente.idTipoExpediente = t.id;
            console.log(app.newExpediente.idTipoExpediente);
        },
        //Tipo Usuario/////
        getAllTipoUsuarios: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarTipoUsuarios")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.tipoUsuarios = response.data.tipoUsuarios;
                        }
                    });
        },

        setIdTipoUsuario(id) {
            app.newUsuario.idTipoUsuario = id;
        },
        //Titulares/////////////////////////////////
        getAllTitulares: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarTitulares")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.titulares = response.data.titulares;
                        }
                    });
        },

        setNewExpedienteIdTitular(t) {
            app.newExpediente.idTitular = t.id;
        },
        //Urgente//////////
        getAllUrgente: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarUrgentes")
                    .then(function (response) {
                        console.log(response);
                        app.urgentes = response.data.urgentes;
                    });
        },
        reseteaNewExpediente(){
          app.newExpediente = {};  
        },
        setNewExpedienteIdUrgente: function (u) {
            /* app.newExpediente.idUrgente = u.id;
             console.log(app.newExpediente.idUrgente);*/
            console.log(app.newExpediente.idUrgente);
        },
        //Fin newExpediente///////////////////////

        //Persona///////////
        añadirPersona: function () {
            app.persona.idDireccion = app.direccion.id;
            console.log(app.persona.email, app.persona.dni, app.persona.idDireccion, app.persona.nombre, app.persona.telefono);
            var formData = app.toFormData(app.persona);
            axios.post("http://localhost/expedientes/php/api.php?action=añadirPersona", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                            app.showingPersona = true;
                        } else {
                            app.successMessage = response.data.message;
                            app.persona.id = response.data.id;
                            app.showingUsuario = true;
                        }
                    });
        },
        //Proyectista///////////////
        getAllProyectistas: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarProyectistas")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.proyectistas = response.data.proyectistas;
                        }
                    });
        },
        //Registrarse//////////
        //Direccion/////
        registrarse: function () {
            var formData = app.toFormData(app.clickedRegistrarse);
            axios.post("http://localhost/expedientes/php/api.php?action=registrarse", formData)
                    .then(function (response) {
                        console.log(response);
                        app.clickedRegistrarse = {};
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.successMessage = response.data.message;
                            app.getAllExpedientes();
                        }
                    });
        },
        getAllPaises: function () {
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarPaises")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.paises = response.data.paises;
                        }
                    });
        },
        getProvincias: function (pais) {
            app.direccion.idPais = pais.id;
            var formData = app.toFormData(pais);
            axios.post("http://localhost/expedientes/php/api.php?action=mostrarProvincias", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.provincias = response.data.provincias;
                        }
                    });
        },
        getMunicipios: function (p) {
            var formData = app.toFormData(p);
            axios.post("http://localhost/expedientes/php/api.php?action=mostrarMunicipios", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.municipios = response.data.municipios;
                        }
                    });
        },
        getLocalidades: function (p) {
            var formData = app.toFormData(p);
            axios.post("http://localhost/expedientes/php/api.php?action=mostrarLocalidades", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.localidades = response.data.localidades;
                        }
                    });
        },
        addProvincia: function () {
            app.provincia.idPais = app.direccion.idPais;
            var formData = app.toFormData(app.provincia);
            axios.post("http://localhost/expedientes/php/api.php?action=añadirProvincia", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                            console.log("No se ha insertado provincia");
                        } else {
                            app.successMessage = response.data.message;
                            app.direccion.idProvincia = response.data.id;
                            app.añadirDireccion();
                        }
                    });
        },
        addMunicipio: function () {
            app.municipio.idProvincia = app.direccion.idProvincia;
            var formData = app.toFormData(app.municipio);
            axios.post("http://localhost/expedientes/php/api.php?action=añadirMunicipio", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.successMessage = response.data.message;
                            app.direccion.idMunicipio = response.data.id;
                            app.añadirDireccion();
                        }
                    });
        },
        addLocalidad: function () {
            app.localidad.idProvincia = app.direccion.idProvincia;
            var formData = app.toFormData(app.localidad);
            axios.post("http://localhost/expedientes/php/api.php?action=añadirLocalidad", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.successMessage = response.data.message;
                            app.direccion.idLocalidad = response.data.id;
                            app.añadirDireccion();
                        }
                    });
        },
        setProvincia: function (p) {
            app.direccion.idProvincia = p.id;
        },
        setMunicipio: function (m) {
            app.direccion.idMunicipio = m.id;
            // console.log(app.direccion.idMunicipio);
        },
        setLocalidad: function (l) {
            app.direccion.idLocalidad = l.id;
        },
        añadirDireccion: function () {
            if (app.direccion.idProvincia === 0)
                app.addProvincia();
            else if (app.direccion.idMunicipio === 0)
                app.addMunicipio();
            else if (app.direccion.idLocalidad === 0)
                app.addLocalidad();
            else {
                console.log(app.direccion.idPais, app.direccion.idProvincia, app.direccion.idMunicipio, app.direccion.idLocalidad, app.direccion.codPostal, app.direccion.direccion);
                var formData = app.toFormData(app.direccion);
                axios.post("http://localhost/expedientes/php/api.php?action=añadirDireccion", formData)
                        .then(function (response) {
                            console.log(response);
                            if (response.data.error) {
                                app.errorMessage = response.data.message;
                            } else {
                                app.successMessage = response.data.message;
                                app.direccion.id = response.data.insert_id;
                                app.showingPersona = true;
                            }
                        });
            }

        },
        //Sesion/////////////////
        sesionTipoUsuario: function () {
            console.log("chacho", app.usuario.idTipoUsuario, app.usuarioLogueado.usuario);
            if (app.usuario.idTipoUsuario == 1) {
                app.showingAdministrador = true;
                app.showingAdministrativo = false;
                app.showingTecnico = false;
                app.cargarCombosExpediente();

            }
            if (app.usuario.idTipoUsuario == 2) {  /*Tine que se ==*/
                app.showingAdministrativo = true;
                app.showingTecnico = false;
                app.showingAdministrador = false;
                app.cargarCombosExpediente();

            }
            if (app.usuario.idTipoUsuario == 3) {
                app.showingTecnico = true;
                app.showingAdministrador = false;
                app.showingAdministrativo = false;
                app.getExpedientesTecnico();
            }

        },

        //Usuarios////////////////////////////////////////////////

        getAllUsers: function () {
            axios.get("http://localhost/vueCRUD/api.php?action=read")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.users = response.data.users;
                        }
                    });
        },
        saveUser: function () {

            var formData = app.toFormData(app.newUser);
            axios.post("http://localhost/vueCRUD/api.php?action=create", formData)
                    .then(function (response) {
                        console.log(response);
                        app.newUser = {username: "", email: "", mobile: ""};
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.successMessage = response.data.message;
                            app.getAllUsers();
                        }
                    });
        },
        updateUser: function () {

            var formData = app.toFormData(app.clickedUser);
            axios.post("http://localhost/vueCRUD/api.php?action=update", formData)
                    .then(function (response) {
                        console.log(response);
                        //app.clickedUser = {};
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.successMessage = response.data.message;
                            app.getAllUsers();
                        }
                    });
        },
        deleteUser: function () {

            var formData = app.toFormData(app.clickedUser);
            axios.post("http://localhost/vueCRUD/api.php?action=delete", formData)
                    .then(function (response) {
                        console.log(response);
                        //app.clickedUser = {};
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.successMessage = response.data.message;
                            app.getAllUsers();
                        }
                    });
        },
        selectUser(user) {
            app.clickedUser = user;
        },
        //Usuario/////////////
        grabarUsuario: function () {
            app.newUsuario.idPersona = app.persona.id;
            console.log(app.newUsuario.idPersona, app.newUsuario.idTipoUsuario, app.newUsuario.usuario, app.newUsuario.contraseña);
            var formData = app.toFormData(app.newUsuario);
            axios.post("http://localhost/expedientes/php/api.php?action=grabarUsuario", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.newUsuario = {};
                            app.successMessage = response.data.message;
                            app.usuario = response.data.usuario;
                            app.sesionTipoUsuario();
                            app.showingUsuario = false;
                        }
                    });
        },

        //Funcion que pasa un objeto vue a objeto $_post['']
        toFormData: function (obj) {
            var form_data = new FormData();
            for (var key in obj) {
                form_data.append(key, obj[key]);
            }
            return form_data;
        },
        clearMessage: function () {
            app.errorMessage = "";
            app.successMessage = "";
        },
    }
});
