<template>
    <div class="modal fade" id="editModule" tabindex="-1" role="dialog" aria-labelledby="editModuleTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModuleTitle">Nuevo módulo</h5>
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
                            <select class="form-control" id="teacher" v-model="selectedTeacher.id">
                                <option v-for="teacher in teachers" :value="teacher.id" :selected="selectedTeacher.name === teacher.name">{{teacher.name}}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="students">Estudiantes</label>
                            <select multiple class="form-control" id="students" size="15" v-model="students_select">
                                <option v-for="student in students" :value="student.id" :selected="selectedStudent(student.id)">{{student.name}}</option>
                            </select>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" v-on:click="updateModule">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "EditModuleModal",

        props: {
            id: Number,
            name: String,
            teacher: Object,
            teachers: Array,
            students: Array
        },

        watch: {
            id(new_value, last_value){
                this.fetchModule(new_value);
            },

            students_in_module(new_value, last_value) {
                this.changeSelectedStudents(new_value);
            },

            name(new_value, last_value) {
                this.module_name = new_value;
            }
        },

        data() {
            return {
                students_in_module: [],
                students_select: [],
                module_name: ''
            }
        },

        computed: {
            selectedTeacher() {
                if (!this.teacher) {
                    return {id: null, name: ''}
                }

                return this.teacher;
            },

            moduleId() {
                return this.id;
            }
        },

        methods: {
            selectedStudent(student_id) {
                return _.some(this.students_in_module, ['id', student_id]);
            },

            fetchModule(module_id) {
                this.$store.commit('SET_LOADING', true);

                api.call('get', '/api/admin/modules/' + module_id).then((response) => {
                    this.students_in_module = response.data.data.students;
                    this.$store.commit('SET_ERROR', null)
                }).catch(error => {
                    this.$store.commit('SET_ERROR', error.data.message);
                }).finally(() => this.$store.commit('SET_LOADING', false));
            },

            updateModule() {
                let formData = new FormData();
                formData.append('name', this.module_name);
                formData.append('teacher_id', this.selectedTeacher.id);
                formData.append('students', this.students_select);

                api.call('post', '/api/admin/modules/' + this.moduleId, formData)
                    .then((response) => {
                        this.$emit('edit-module');
                        this.closeModal();
                    })
                    .catch((error) => console.log(error))
                    .finally();
            },

            changeSelectedStudents(students) {
                let students_select = [];

                _.forEach(students, function(value) {
                    students_select.push(value.id);
                });

                this.students_select = students_select;
            },

            closeModal() {
                $('#editModule').modal('hide');
            }
        }
    }
</script>

<style scoped>

</style>
