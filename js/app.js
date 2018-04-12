var app = new Vue({

    el: "#root",
    data: {
        showingPersona: false,
        showingDireccion: false,
        showingRegistrarse: false,
        showingLogin: false,
        showingNuevoExpediente: false,
        showingEditarExpediente: false,
        showingDeleteExpediente: false,
        errorMessage: "",
        successMessage: "",
        usuario: "",
        /* users: [],
         newUser: {email: "", mobile: ""},*/
        selectedPais: "España",
        selectedProvincia: "Santa Cruz de Tenerife",
        expedientes: [],
        paises: [],
        provincias: [],
        municipios: [],
        localidades: [],
        persona: {id:0,dni:"",nombre:"",idDireccion:0,email:"",telefono:""},
        pais:{id:0,pais:"España"},
        provincia:{id:0,idPais:0,provincia:""},
        municipio:{id:0,idProvincia:0,municipio:""},
        localidad:{id:0,idMunicipio:0,localidad:""},
        direccion: {id:0,idPais:0,idProvincia:0,idMunicipio:0,idLocalidad:0,codPostal:"",direccion:""},
        newExpediente: {idUrgente: 0, idTipoExpediente: 0, fecha: "", numero: "", idTitular: 0, idDireccion: 0, idProyectista: 0, idCalificacion: 0, idIAE: 0, descripcion: ""},
        login: {email: "", contraseña: ""},
        //clickedUser: {},
        clickedExpediente: {},
        clickedRegistrarse: {},

    },
    created: function () {
        console.log("Expedientes");
        this.getAllExpedientes();
        this.getAllPaises();


    },
    /* watch: {
     paises: function (newVal) {
     this.getProvincias(newVal);
     }
     },
     */
    methods: {
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
            app.direccion.idProvincia = p.id;
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
         setMunicipio: function (m) {
            app.direccion.idMunicipio = m.id;
            console.log(app.direccion.idMunicipio);
        },
        setLocalidad: function (l) {
            app.direccion.idLocalidad = l.id;
        },
        añadirDireccion: function () {          
            var formData = app.toFormData(app.direccion);
           // console.log(app.direccion.idPais,app.direccion.id,app.direccion.codPostal,app.direccion.direccion);
            axios.post("http://localhost/expedientes/php/api.php?action=añadirDireccion", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.successMessage = response.data.message;
                            app.direccion.id = response.data.insert_id;
                            console.log(app.direccion.id);
                            app.showingPersona = true;
                            
                        }
                    });
                   
              //      app.showingPersona = true;
        },
        //Persona///////////
        añadirPersona: function () {
           
            app.persona.idDireccion = app.direccion.id;
             console.log(app.persona.email,app.persona.dni,app.persona.idDireccion,app.persona.nombre,app.persona.telefono);
            var formData = app.toFormData(app.persona);
            axios.post("http://localhost/expedientes/php/api.php?action=añadirPersona", formData)
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                            app.showingPersona = true;
                        } else {
                            app.successMessage = response.data.message;
                            
                            
                        }
                    });
        },
        //Login//////////////////////////////////////
        comprobarLogin: function () {
            app.login = {email: "popo@popo", contraseña: "popo"};
            var formData = app.toFormData(app.login);
            axios.post("http://localhost/expedientes/php/api.php?action=comprobarLogin", formData)
                    .then(function (response) {
                        console.log(response);
                        app.login = {email: "", contraseña: ""};
                        if (response.data.error) {
                            app.errorMessage = response.data.message;

                        } else {
                            app.successMessage = response.data.message;

                        }
                    });
        },
        //Expedientes///////////////
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
        creaExpediente: function () {

            var formData = app.toFormData(app.newExpediente);
            axios.post("http://localhost/expedientes/php/api.php?action=creaExpediente", formData)
                    .then(function (response) {
                        console.log(response);
                        app.newExpediente = {idUrgente: 0, idTipoExpediente: 0, fecha: "", numero: "", idTitular: 0, idDireccion: 0, idProyectista: 0, idCalificacion: 0, idIAE: 0, descripcion: ""};
                        if (response.data.error) {
                            app.errorMessage = response.data.message;

                        } else {
                            app.successMessage = response.data.message;
                            app.getAllExpedientes();
                        }
                    });
        },
        updateExpediente: function () {

            var formData = app.toFormData(app.clickedExpediente);
            axios.post("http://localhost/expedientes/php/api.php?action=updateExpediente", formData)
                    .then(function (response) {
                        console.log(response);
                        //app.clickedExpediente = {};
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.successMessage = response.data.message;
                            app.getAllExpedientes();
                        }
                    });
        },
        selectExpediente(expediente) {
            app.clickedExpediente = expediente;
        },
        deleteExpediente: function () {

            var formData = app.toFormData(app.clickedExpediente);
            axios.post("http://localhost/expedientes/php/api.php?action=deleteExpediente", formData)
                    .then(function (response) {
                        console.log(response);
                        //app.clickedExpediente = {};
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.successMessage = response.data.message;
                            app.getAllExpedientes();
                        }
                    });
        }

        ,
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
