<template>
    <tr :id="destroyId">
        <th scope="row">{{index}}</th>
        <td>{{edited.name}}</td>
        <td>{{edited.email}}</td>
        <td>{{role}}</td>
        <td class="text-center">
            <a data-toggle="modal" :href="'#' + customId">
                <font-awesome-icon icon="user-edit" size="lg" />
            </a>
            <a href="#!" @click="$emit('destroy', edited)" class="text-danger">
                <font-awesome-icon icon="trash-alt" size="lg" />
            </a>
        </td>

        <div class="modal fade" :id="customId" tabindex="-1" role="dialog" :aria-labelledby="modalId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="modalId">{{name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label :for="customId + '-name'">Nombre</label>
                                <input type="text" class="form-control" :id="customId + '-name'" v-model="edited.name" aria-describedby="emailHelp" placeholder="Nombre">
                            </div>
                            <div class="form-group">
                                <label :for="customId + '-email'">Email</label>
                                <input type="email" class="form-control" :id="customId + '-email'" v-model="edited.email" aria-describedby="emailHelp" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label :for="customId + '-password'">Contraseña</label>
                                <input type="password" class="form-control" :id="customId + '-password'" v-model="edited.password" aria-describedby="emailHelp" placeholder="Contraseña">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="updateUser">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </tr>
</template>

<script>
    export default {
        name: "UserRow",

        props: {
            index: Number,
            id: Number,
            name: String,
            email: String,
            role: String
        },

        mounted() {
            this.setData();
        },

        data() {
            return {
                edited: {
                    id: null,
                    name: '',
                    email: '',
                    password: ''
                }
            }
        },

        methods: {
            setData() {
                this.edited.id = this.id;
                this.edited.name = this.name;
                this.edited.email = this.email;
            },

            updateUser() {
                let formData = new FormData();
                formData.append('name', this.edited.name);
                formData.append('email', this.edited.email);
                if (!!this.edited.password.length) {
                    formData.append('password', this.edited.password);
                }

                api.call('post', '/api/admin/users/' + this.id, formData)
                    .then((response) => this.hideModal())
                    .catch((error) => console.log(error))
                    .finally();
            },

            hideModal() {
                $('#' + this.customId).modal('hide');
                this.edited.password = '';
            },

            destroy() {
                api.call('delete', '/api/admin/users/' + this.id)
                    .then((response) => $('#' + this.destroyId).modal('hide'))
                    .catch((error) => console.log(error))
                    .finally();
            }
        },

        computed: {
            customId() {
                return 'edit-' + this.index;
            },

            modalId() {
                return 'modal-' + this.index;
            },

            destroyId() {
                return 'destroy-' + this.index;
            }
        }
    }
</script>

<style scoped>

</style>
