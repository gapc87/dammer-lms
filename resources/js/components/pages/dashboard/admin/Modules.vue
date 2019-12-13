<template>
    <div v-if="hasRole('admin')">

        <h1 class="d-inline-block">Gestión de módulos</h1>

        <button type="button" class="btn btn-primary ml-2 mb-2" data-toggle="modal" data-target="#createModule">
            <font-awesome-icon icon="plus-circle" size="lg" />
            Agregar módulo
        </button>

        <table class="table table-striped" v-if="!!modules.length">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Módulo</th>
                <th scope="col">Profesor/a</th>
                <th scope="col">Alumnos</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
                <module-row v-for="(module, index) in modules"
                    v-on:edit-module="editModule"
                    v-on:destroy-module="destroyModule"
                    :key="module.id"
                    :index="index+1"
                    :id="module.id"
                    :name="module.name"
                    :teacher="module.teacher"
                    :students="module.students_count"
                />
            </tbody>
        </table>

        <create-module-modal v-on:update-modules="fetchModules" />
        <edit-module-modal
            v-on:edit-module="fetchModules"
            :id="edit_module.id"
            :name="edit_module.name"
            :teacher="edit_module.teacher"
            :teachers="teachers"
            :students="students"
        />
        <delete-module-modal
            v-on:removed="fetchModules"
            :id="edit_module.id"
            :name="edit_module.name"
        />
    </div>

</template>

<script>
    import {mapGetters} from "vuex";
    import ModuleRow from "./assets/ModuleRow";
    import CreateModuleModal from "./assets/CreateModuleModal";
    import EditModuleModal from "./assets/EditModuleModal";
    import DeleteModuleModal from "./assets/DeleteModuleModal";

    export default {
        name: "Modules",

        components: {DeleteModuleModal, EditModuleModal, CreateModuleModal, ModuleRow},

        created() {
            this.fetchModules();
            this.fetchTeachers();
            this.fetchStudents();
        },

        data() {
            return {
                modules: [],
                teachers: [],
                students: [],
                edit_module: {}
            }
        },

        methods: {
            fetchModules() {
                this.$store.commit('SET_LOADING', true);

                api.call('get', '/api/admin/modules').then((response) => {
                    this.modules = response.data.data;
                    this.$store.commit('SET_ERROR', null)
                }).catch(error => {
                    this.$store.commit('SET_ERROR', error.data.message);
                }).finally(() => this.$store.commit('SET_LOADING', false));
            },

            fetchTeachers() {
                this.$store.commit('SET_LOADING', true);

                api.call('get', '/api/admin/teachers').then((response) => {
                    this.teachers = response.data.data;
                    this.$store.commit('SET_ERROR', null)
                }).catch(error => {
                    this.$store.commit('SET_ERROR', error.data.message);
                }).finally(() => this.$store.commit('SET_LOADING', false));
            },

            fetchStudents() {
                this.$store.commit('SET_LOADING', true);

                api.call('get', '/api/admin/students').then((response) => {
                    this.students = response.data.data;
                    this.$store.commit('SET_ERROR', null)
                }).catch(error => {
                    this.$store.commit('SET_ERROR', error.data.message);
                }).finally(() => this.$store.commit('SET_LOADING', false));
            },

            editModule(module) {
                this.edit_module = module;
                $('#editModule').modal('show');
            },

            destroyModule(module) {
                this.edit_module = module;
                $('#destroyModule').modal('show');
            }
        },

        computed: {
            ...mapGetters('user', {user: 'getUser', hasRole: 'hasRole'}),
        }
    }
</script>

<style scoped>

</style>
