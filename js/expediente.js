var app_e = new Vue({

    el: "#root",
    data: {
        showingModal: false,
        showingeditModal: false,
        showingdeleteModal: false,
        errorMessage: "",
        successMessage: "",
        expedientes: [],
        newExpediente:{urgente: "",idTipoExpediente=0,fecha="",numero="",idTitular=0,idDireccion=0,idProyectista=0,idCalificacion=0,idIAE=0,idCalificado=0,descripcion=""},
        clickedExpediente: {},

    },
    mounted: function () {
        console.log("Expedientes");
        this.getAllEpedientes();
    },
    methods: {


        getAllExpedientes: function () {
            axios.get("http://localhost/expedientes/php/expediente.php?action=read")
                    .then(function (response) {
                        console.log(response);
                        if (response.data.error) {
                            app.errorMessage = response.data.message;
                        } else {
                            app.expedientes = response.data.expedientes;
                        }
                    });
        },
        user in usersuser in userssaveUser: function () {

            var formData = app.toFormData(app.newUser);
            axios.post("http://localhost/expedientes/api.php?action=create", formData)
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
            axios.post("http://localhost/expedientes/api.php?action=update", formData)
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
            axios.post("http://localhost/expedientes/api.php?action=delete", formData)
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
