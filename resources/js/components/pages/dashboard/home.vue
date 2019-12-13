<template>
    <div>
        <h1>Módulos disponibles</h1>

        <div class="card-columns">
            <div class="card" v-for="module in getModules">
                <div class="card-body">
                    <h5 class="card-title">{{ module.name }}</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <font-awesome-icon icon="chalkboard-teacher" size="lg" /> {{ module.teacher.name }}
                        </li>
                        <li class="list-group-item">Recursos: {{ module.resources }}</li>
                        <li class="list-group-item">Tareas: {{ module.tasks }}</li>
                    </ul>
                    <div class="card-body">
                        <a v-bind:href="'/module/' + module.id" class="card-link">Entrar</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "Home",

        mounted() {
            this.modules();
        },

        computed: {
            ...mapGetters(['getLoading', 'getError']),
            ...mapGetters('home', ['getModules'])
        },

        methods: {
            ...mapActions('home', {storeModules: 'modules'}),
            modules() {
                this.$store.commit('SET_LOADING', true);

                return this['storeModules']()
                    .then(() => {
                        this.$store.commit('SET_ERROR', null);
                    })
                    .catch(error => {
                        this.$store.commit('SET_ERROR', error.data.message);
                        this.$store.commit('SET_LOADING', false);
                    });
            }

        }
    }
</script>

<style scoped>

</style>