<template>
    <div v-if="module.name">
        <h1>{{module.name}}</h1>

        <div class="card mb-4">
            <div class="card-body">

                <div class="dropdown">
                    <a class="btn dropdown-toggle" href="#" role="button" id="dropdownTeacher" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="/img/avatar.jpg" class="align-self-center rounded-circle mr-3" alt="" width="40" />
                        {{ module.teacher.name }}
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownTeacher">
                        <a class="dropdown-item" href="#">Detalles</a>
                        <a class="dropdown-item" href="#">Enviar mensaje</a>
                    </div>
                </div>

            </div>
        </div>

        <div class="card mb-3" v-for="(evaluation, key) in evaluations">
            <div class="card-body">
                <div class="d-flex">
                    <h5 class="card-title">Evaluación {{key}}</h5>
                    <div class="ml-auto" v-if="hasRole('teacher') && user.id === module.teacher.id">
                        <div class="dropdown">
                            <a href="#" role="button" id="dropdownPlus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <font-awesome-icon class="text-success" icon="plus-circle" size="lg" />
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownPlus">
                                <a class="dropdown-item" data-toggle="modal" href="#uploadResource" @click="setEvaluation(key)">Nuevo recurso</a>
                                <a class="dropdown-item" data-toggle="modal" href="#uploadTask" @click="setEvaluation(key)">Nueva tarea</a>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-hover" v-if="evaluation.resources.length">
                    <thead>
                    <tr>
                        <th scope="col" width="50px">#</th>
                        <th scope="col">Recurso</th>
                        <th scope="col" width="112px">Descargar</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="(resource, key) in evaluation.resources">
                        <th scope="row">{{ key + 1 }}</th>
                        <td>{{resource.title}}</td>
                        <td>
                            <button type="button" class="btn btn-primary" @click="download(resource.uuid, resource.resource)">Descargar</button>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="card-body text-center" v-else>
                    <div class="alert alert-warning" role="alert">
                        Todavía no se a subido ningún recurso en la evaluación {{key}}
                    </div>
                </div>

                <hr />

                <table class="table table-hover" v-if="evaluation.tasks.length">
                    <thead>
                    <tr>
                        <th scope="col" width="50px">#</th>
                        <th scope="col">Tarea</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="(task, key) in evaluation.tasks">
                        <th scope="row">{{ key + 1 }}</th>
                        <td>
                            <a v-bind:href="$route.params.module + '/task/' + task.id">{{task.title}}</a>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="card-body text-center" v-else>
                    <div class="alert alert-warning" role="alert">
                        Todavía no se a subido ninguna tarea en la evaluación {{key}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="uploadResource" tabindex="-1" role="dialog" aria-labelledby="uploadResourceTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadResourceTitle">Nuevo recurso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="resourceTitle">Título</label>
                                <input class="form-control" type="text" id="resourceTitle" v-model="title">
                            </div>
                            <div class="form-group">
                                <label for="resourceFile">Recurso</label>
                                <input type="file" class="form-control-file" id="resourceFile" ref="file" v-on:change="handleFileUpload()">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="submitResourceFile">Publicar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="uploadTask" tabindex="-1" role="dialog" aria-labelledby="uploadTaskTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadTaskTitle">Nueva tarea</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="taskTitle">Título</label>
                                <input class="form-control" type="text" id="taskTitle" v-model="title">
                            </div>
                            <div class="form-group">
                                <label for="taskStatement">Enunciado</label>
                                <textarea class="form-control" id="taskStatement" rows="3" v-model="statement"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="taskStartAt">Fecha de inicio</label>
                                <input class="form-control" type="datetime-local" id="taskStartAt" name="start_at" v-model="start_at">
                            </div>
                            <div class="form-group">
                                <label for="taskEndAt">Fecha de fin</label>
                                <input class="form-control" type="datetime-local" id="taskEndAt" name="end_at" v-model="end_at">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="submitTask">Publicar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import {store} from '../../../app';
    const FileDownload = require('js-file-download');

    export default {
        name: "module",

        data() {
            return {
                title: '',
                file: '',
                evaluation: null,
                statement: '',
                start_at: null,
                end_at: null
            }
        },

        created() {
            this.fetchModules();
        },

        methods: {
            ...mapActions('module', {moduleStore: 'module'}),

            fetchModules() {
                this.$store.commit('SET_LOADING', true);

                api.call('get', '/api/modules/' + this.$route.params.module).then((response) =>
                {
                    this['moduleStore'](response.data)
                    this.$store.commit('SET_ERROR', null)
                }).catch(error => {
                    this.$store.commit('SET_ERROR', error.data.message);
                }).finally(() => this.$store.commit('SET_LOADING', false));
            },

            handleFileUpload() {
                this.file = this.$refs.file.files[0];
            },

            submitResourceFile() {
                let formData = new FormData();
                formData.append('title', this.title);
                formData.append('file', this.file);
                formData.append('evaluation', this.evaluation);
                formData.append('module_id', this.module.id);

                axios.post('/api/modules/' + this.$route.params.module + '/upload-resource',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(() => this.fetchModules())
                .catch(function () {
                    console.log('FAILURE!!');
                });

                this.clearForms();
            },

            submitTask() {
                let formData = new FormData();
                formData.append('title', this.title);
                formData.append('statement', this.statement);
                formData.append('evaluation', this.evaluation);
                formData.append('module_id', this.module.id);
                formData.append('start_at', this.start_at);
                formData.append('end_at', this.end_at);

                axios.post('/api/modules/' + this.$route.params.module + '/upload-task', formData
                ).then(() => this.fetchModules())
                .catch((e) => console.log('FAILURE!!', e))
                .finally(() => this.clearForms());
            },

            setEvaluation(e) {
                this.evaluation = e;
            },

            clearForms() {
                $('#uploadResource').modal('hide');
                $('#uploadTask').modal('hide');
                $('#resourceFile').val('');
                this.title = '';
                this.file = '';
                this.statement = '';
                this.evaluation = null;
                this.start_at = null;
                this.end_at = null;
            },

            download(uuid, filename) {
                const file = filename.split('/').pop();

                axios.post('/api/modules/' + this.$route.params.module + '/' + uuid + '/download', uuid, {
                    responseType: 'arraybuffer'
                }).then((response) => {
                    FileDownload(response.data, file);
                })
                .catch((e) => console.log('FAILURE!!', e));
            }
        },

        computed: {
            ...mapGetters('user', {user: 'getUser', hasRole: 'hasRole'}),
            ...mapGetters('module', {module: 'getModule', evaluations: 'getEvaluations'})
        }
    }
</script>

<style scoped>

</style>
