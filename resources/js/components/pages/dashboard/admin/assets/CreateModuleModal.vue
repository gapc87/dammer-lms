<template>
    <div class="modal fade" id="createModule" tabindex="-1" role="dialog" aria-labelledby="createModuleTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModuleTitle">Nuevo módulo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form>
                        <div class="form-group">
                            <label for="module">Nombre de módulo</label>
                            <input type="text" v-model="module_name" class="form-control" id="module" placeholder="Módulo">
                        </div>

                        <div class="form-group">
                            <label for="teacher">Profesor/a</label>
                            <select class="form-control" id="teacher" v-model="teacher_select">
                                <option v-for="teacher in teachers" :value="teacher.id">{{teacher.name}}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="students">Estudiantes</label>
                            <select multiple class="form-control" id="students" size="15" v-model="students_select">
                                <option v-for="student in students" :value="student.id" >{{student.name}}</option>
                            </select>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
                    <button type="button" class="btn btn-primary" v-on:click="addModule">Crear</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CreateModuleModal",

        data() {
            return {
                teachers: [],
                students: [],
                module_name: '',
                teacher_select: '',
                students_select: []
            }
        },

        created() {
            this.fetchTeachers();
            this.fetchStudents();
        },

        methods: {
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

            addModule() {
                let formData = new FormData();
                formData.append('name', this.module_name);
                formData.append('teacher_id', this.teacher_select);
                formData.append('students', this.students_select);

                api.call('post', '/api/admin/modules', formData)
                    .then((response) => {
                        this.$emit('update-modules');
                        this.closeModal();
                    })
                    .catch((error) => console.log(error))
                    .finally();
            },

            closeModal() {
                $('#createModule').modal('hide');
                $("option:selected").prop("selected", false);
                this.module_name = '';
                this.teacher_select = '';
                this.students_select = []
            }
        },
    }
</script>

<style scoped>

</style>
