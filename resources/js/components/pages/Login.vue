<template>
    <div class="grid-container">

        <form class="form-signin" autocomplete="off" @submit.prevent="login" method="post">
            <img class="pb-3" src="img/logo.png" alt="Dammer" width="100%" height="100%">
            <div class="form-group">
                <input type="email" id="email" class="form-control" placeholder="Usuario" v-model="email" required="required" autofocus="autofocus">
            </div>

            <div class="form-group">
                <input type="password" id="password" class="form-control" placeholder="Contraseña" v-model="password" required="required">
            </div>

            <button type="submit" class="btn btn-primary btn-block mb-3">Login</button>

            <div class="alert alert-danger" role="alert" v-if="getError">
                <p>{{ getError }}</p>
            </div>

        </form>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "Login",

        data() {
            return {
                email: '',
                password: '',
            };
        },

        computed: {
            ...mapGetters(['getLoading', 'getError'])
        },

        methods: {
            ...mapActions('user', {storeLogin: 'login'}),
            login() {
                this.$store.commit('SET_LOADING', true);
                let data = {
                    email: this.email,
                    password: this.password
                };

                return this['storeLogin'](data)
                    .then(() => {
                        this.$store.commit('SET_ERROR', null);
                        this.$router.push('/');
                    })
                    .catch(error => {
                        this.$store.commit('SET_ERROR', error.data.message);
                        this.$store.commit('SET_LOADING', false);
                    });
            }

        }
    }
</script>

<style lang="scss">
    .grid-container {
        position: absolute;
        display: flex;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;

        .form-signin {
            background-color: white;
            box-shadow: 0 0 8px 0 rgba(0,0,0,0.2);
            margin: auto;
            max-width: 330px;
            max-height: max-content;
            padding: 15px;
            width: 100%;
        }
    }
</style>