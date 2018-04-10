var app = new Vue({

    el: "#root",
    data: {
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
        expedientes: [],
        paises: [],
        provincias: [],
        newExpediente: {idUrgente: 0, idTipoExpediente: 0, fecha: "", numero: "", idTitular: 0, idDireccion: 0, idProyectista: 0, idCalificacion: 0, idIAE: 0, descripcion: ""},
        login : {email: "", contraseña:""},
        pais : {id:0},
        //clickedUser: {},
        clickedExpediente: {},
        clickedRegistrarse: {},

    },
    mounted: function () {
        console.log("Expedientes");
        this.getAllExpedientes();
        this.getAllPaises();
        
        
    },
    methods: {
        //Registrarse//////////
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
        getProvincias: function () {
            var formData = app.toFormData(app.clickedRegistrarse);
            axios.get("http://localhost/expedientes/php/api.php?action=mostrarProvincias")
                    .then(function (response) {
                        console.log(response);
                        app.clickedRegistrarse = {}
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.provincias = response.data.provincias;
                        }
                    });
        },
        //Login///////////////
        comprobarLogin: function () {
            var formData = app.toFormData(app.login);
            axios.post("http://localhost/expedientes/php/api.php?action=comprobarLogin", formData)
                    .then(function (response) {
                        console.log(response);
                        app.login = {email:"",contraseña:""};
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
                        app.clickedExpediente = {};
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
                        app.clickedExpediente = {};
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
                        app.clickedUser = {};
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
                        app.clickedUser = {};
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
