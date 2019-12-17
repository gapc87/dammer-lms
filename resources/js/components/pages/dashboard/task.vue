<template>

    <div v-if="!loading && !!task.task">

        <div class="card mb-3">
            <div class="card-header">
                {{task.task.title}}
            </div>
            <div class="card-body">
                <p class="card-text">{{task.task.statement}}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Tarea
            </div>
            <div class="card-body" v-if="user.id === task.teacher.id">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Alumno</th>
                        <th scope="col" width="140px">Tarea entregada</th>
                        <th scope="col" width="180px">Nota</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(student, key) in task.students">
                        <th scope="row">{{key+1}}</th>
                        <td>{{student.name}}</td>
                        <td v-if="!!student.evaluated_task">
                            <button
                                type="button"
                                class="btn btn-primary d-block mx-auto"
                                @click="download(student.id, student.evaluated_task.uuid, student.evaluated_task.upload)"
                            >
                                Descargar
                            </button>
                        </td>
                        <td v-else>
                            Sin entregar
                        </td>
                        <td v-if="!!student.evaluated_task">
                            <evaluate-task
                                @remove="student.evaluated_task.score = null"
                                @update-score="setScore"
                                :array_key="key"
                                :student_id="student.id"
                                :task_id="task.task.id"
                                :student_score="student.evaluated_task.score"
                            />
                        </td>
                        <td v-else>
                            Tarea sin evaluar
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>


            <div class="card-body" v-else>

                <form method="post" accept-charset="UTF-8" enctype="multipart/form-data" @submit.prevent="submitFile" v-if="!task.task.evaluated_task">
                    <div class="form-group">
                        <label for="file">Selecciona tarea para subir</label>
                        <input type="file" name="filename" class="form-control-file" id="file" ref="file" v-on:change="handleFileUpload()">
                    </div>
                    <button class="btn btn-primary" type="submit">Subir</button>
                </form>

                <button v-else
                    type="button"
                    class="btn btn-primary mb-3 d-block mx-auto"
                    @click="download(task.task.evaluated_task.student_id, task.task.evaluated_task.uuid, task.task.evaluated_task.upload)"
                >
                    Descargar
                </button>

                <div class="card bg-light mb-3 mx-auto" style="max-width: 12rem;" v-if="!!task.task.evaluated_task">
                    <div class="card-header text-center">Nota</div>
                    <div class="card-body" v-if="!task.task.evaluated_task.score">
                        <p class="card-text text-center">Tarea por evaluar</p>
                    </div>
                    <div class="card-body" v-else>
                        <p class="card-text display-4 text-center">{{task.task.evaluated_task.score}}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import EvaluateTask from "./forms/EvaluateTask";
    const FileDownload = require('js-file-download');

    export default {
        name: "task",

        components: {
            EvaluateTask
        },

        data() {
            return {
                file: '',
                score: null
            };
        },

        mounted() {
            this.fetchTask();
        },

        methods: {
            ...mapActions('module', {taskStore: 'task', setScore: 'setScore'}),

            fetchTask() {
                this.$store.commit('SET_LOADING', true);

                api.call('get', '/api/modules/' + this.$route.params.module + /tasks/ + this.$route.params.task).then((response) => {
                        this['taskStore'](response.data);
                        this.$store.commit('SET_ERROR', null);
                    })
                    .catch((error) => this.$store.commit('SET_ERROR', error.data.message))
                    .finally(() => this.$store.commit('SET_LOADING', false));
            },

            handleFileUpload() {
                this.file = this.$refs.file.files[0];
            },

            submitFile() {

                this.$store.commit('SET_LOADING', true);

                let formData = new FormData();
                formData.append('file', this.file);

                axios.post( '/api/modules/' + this.$route.params.module + /tasks/ + this.$route.params.task + '/upload',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                .then(() => {
                    this.fetchTask();
                    this.$store.commit('SET_ERROR', null)
                })
                .catch((error) => this.$store.commit('SET_ERROR', error.data.message))
                .finally(() => this.$store.commit('SET_LOADING', false));
            },

            download(student_id, uuid, filename) {
                const file = filename.split('/').pop();

                console.log(student_id, uuid, filename, this.task.task.id);

                axios.post('/api/modules/'
                    + student_id
                    + '/' + this.$route.params.module
                    + '/' + this.task.task.id
                    + '/' + uuid
                    + '/download', uuid, {
                    responseType: 'arraybuffer'
                }).then((response) => {
                    FileDownload(response.data, file);
                })
                    .catch((e) => console.log('FAILURE!!', e));
            }
        },

        computed: {
            ...mapGetters({loading: 'getLoading'}),
            ...mapGetters('user', {user: 'getUser', hasRole: 'hasRole'}),
            ...mapGetters('module', {task: 'getTask'})
        }
    }
</script>

<style scoped>

</style>
