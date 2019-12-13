<template>
    <div v-if="hasRole('admin')">

        <h1 class="d-inline-block">Usuarios del sistema</h1>

        <button type="button" class="btn btn-primary ml-2 mb-2" data-toggle="modal" data-target="#create">
            <font-awesome-icon icon="plus-circle" size="lg" />
            Agregar usuario
        </button>

        <table class="table table-striped" id="users">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Email</th>
                <th scope="col">Rol</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
                <user-row v-for="(user, index) in users"
                    v-on:destroy="destroyModal($event)"
                    :key="user.id"
                    :index="index+1"
                    :id="user.id"
                    :name="user.name"
                    :email="user.email"
                    :role="user.role.name"
                />
            </tbody>
        </table>

        <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="modal-create" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-create">Nuevo usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" v-model="create.name" aria-describedby="emailHelp" placeholder="Nombre">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" v-model="create.email" aria-describedby="emailHelp" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" v-model="create.password" aria-describedby="emailHelp" placeholder="Contraseña">
                            </div>
                            <div class="form-group">
                                <label for="role">Rol</label>
                                <select class="form-control" id="role" v-model="create.role">
                                    <option value="1">Administrador</option>
                                    <option value="2">Profesor</option>
                                    <option value="3">Alumno</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="addUser">Crear</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="destroy" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Eliminar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Realmente desea eliminar este usuario? <br /> {{name}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="destroy">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-danger" v-else>
        No tienes permisos para acceder a esta sección
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import UserRow from "./assets/UserRow";

    export default {
        name: "adminUsers",

        components: {
            UserRow
        },

        created() {
            this.fetchUsers();
        },

        data() {
            return {
                id: null,
                name: '',
                create: {
                    name: '',
                    email: '',
                    password: '',
                    role: ''
                }
            }
        },

        methods: {
            ...mapActions('adminUsers', {usersStore: 'users'}),

            fetchUsers() {
                this.$store.commit('SET_LOADING', true);

                api.call('get', '/api/admin/users').then((response) =>
                {
                    this['usersStore'](response.data);
                    this.$store.commit('SET_ERROR', null)
                }).catch(error => {
                    this.$store.commit('SET_ERROR', error.data.message);
                }).finally(() => this.$store.commit('SET_LOADING', false));
            },

            addUser() {
                let formData = new FormData();
                formData.append('name', this.create.name);
                formData.append('email', this.create.email);
                formData.append('password', this.create.password);
                formData.append('role', this.create.role);

                api.call('post', '/api/admin/users', formData)
                    .then((response) => {
                        this.fetchUsers();
                        $('#create').modal('hide');
                        this.create.role = null;
                        this.create.password = '';
                        this.create.email = '';
                        this.create.name = '';
                    })
                    .catch((error) => console.log(error))
                    .finally();
            },

            destroyModal(data) {
                this.id = data.id;
                this.name = data.name;
                $('#destroy').modal('show');
            },

            destroy() {
                api.call('delete', '/api/admin/users/' + this.id)
                    .then((response) => {
                        this.fetchUsers();
                        $('#destroy').modal('hide');

                    })
                    .catch((error) => console.log(error))
                    .finally();
            }
        },

        computed: {
            ...mapGetters('user', {user: 'getUser', hasRole: 'hasRole'}),
            ...mapGetters('adminUsers', {users: 'getUsers'})
        }
    }
</script>

<style scoped>

</style>
