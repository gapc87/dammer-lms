<template>
    <div class="container-fluid">

        <nav class="navbar navbar-expand fixed-top navbar-dark navbar-background">

            <a class="navbar-brand" href="/">
                <img src="/img/logo.png" width="40" class="d-inline-block align-top" alt="Dammer">
                Dammer
            </a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto d-flex flex-nowrap">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notificaciones">
                            <span class="badge badge-success">1</span>
                            <font-awesome-icon icon="bell" size="lg" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifications">
                            <div class="dropdown-header">
                                Notificaciones
                            </div>
                            <!-- <p class="text-muted text-center mt-3 px-1">No hay notificaciones que mostrar</p> -->
                            <a class="dropdown-item text-truncate" href="#">
                                <font-awesome-icon class="text-success" icon="check-circle" size="lg" />
                                Jose Luís Navarro calificó tu tarea 'Algoritmo de la panadería'
                            </a>
                            <div class="dropdown-footer position-relative">
                                <font-awesome-icon icon="list" />
                                <a href="#" class="stretched-link">
                                    Ver todas las notificaciones
                                </a>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown pl-2">
                        <a
                                class="nav-link"
                                href="#"
                                id="messages"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                data-toggle-second="tooltip"
                                data-placement="bottom"
                                title="Mensajes Privados"
                                data-reference="parent"
                        >
                            <span class="badge badge-success">10</span>
                            <font-awesome-icon icon="envelope" size="lg" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messages">
                            <div class="dropdown-header d-flex align-items-center">
                                Mensajes
                                <button type="button" class="btn btn-primary btn-sm ml-auto">Nuevo mensaje</button>
                            </div>
                            <!--
                            <p class="text-muted text-center mt-3 px-1">No hay mensajes nuevos</p> -->

                            <a class="dropdown-item" href="#">
                                <div class="media">
                                    <img src="/img/avatar.jpg" class="align-self-center rounded-circle mr-3" alt="" width="45" />
                                    <div class="media-body text-truncate">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-0 text-truncate">Isabel Martínez, Juan Pablo Pérez</h6>
                                            <span>04/03/2019</span>
                                        </div>
                                        <div class="text-truncate">
                                            Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin.
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-footer position-relative">
                                <a href="#" class="stretched-link">Ver todos los mensajes</a>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown pl-2">
                        <a
                            href="#"
                            role="button"
                            id="profile"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            <img src="/img/avatar.jpg" class="rounded-circle" alt="" width="40" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profile">
                            <h6 class="dropdown-header">Cuenta</h6>
                            <a class="dropdown-item" href="#">Mensajes</a>
                            <a class="dropdown-item" href="#">Tareas</a>
                            <a class="dropdown-item" href="#">Comentarios</a>
                            <h6 class="dropdown-header">Ajustes</h6>
                            <a class="dropdown-item" href="#">Perfil</a>
                            <a class="dropdown-item" href="#">Configuración</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#!" @click.prevent="logout">Cerrar sesión</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <aside class="aside" v-if="hasRole('admin')">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><a href="/admin/users">Administrar usuarios</a></li>
                <li class="list-group-item"><a href="/admin/modules">Administrar módulos</a></li>
            </ul>
        </aside>

        <div class="main">

            <div class="content" :class="hasRole('admin') ? 'with-aside' : ''">
                <router-view></router-view>
            </div>

        </div>

    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "Index",

        computed: {
            ...mapGetters('user', {user: 'getUser', roleStore: 'role'}),
        },

        methods: {
            ...mapActions('user', {logoutStore: 'logout'}),

            logout() {
                this.$store.commit('SET_LOADING', true);
                return this['logoutStore']()
                    .then(() => this.$router.push('/login'))
                    .finally(() => this.$store.commit('SET_LOADING', false) );
            },

            hasRole(role) {
                return this['roleStore'] === role
            }
        }
    }
</script>

<style lang="scss">

    .navbar-background {
        background-color: #a3bcd5;
    }

    .main {
        padding-top: 56px;

        nav {
            position: fixed;
            left: 13rem;
            right: 0;
            z-index: 1000;

            ol {
                border-radius: 0;
            }
        }

        .content {
            margin-top: 2rem;

            &.with-aside {
                margin-left: 14rem;
            }
        }
    }

    ul {
        li {
            > a {
                span {
                    position: absolute;
                    top: 0;
                    right: 0;
                }
            }

            .dropdown-menu:not([aria-labelledby=profile]) {
                width: 350px;

                .dropdown-header {
                    background-color: #7795b4;
                    border-radius: 0.25rem 0.25rem 0 0;
                    color: white;
                    font-size: 1.2em;
                    margin-top: -0.5rem;
                }

                & a {
                    padding: 1rem .5rem;
                }

                .dropdown-footer {
                    background-color: #7795b4;
                    border-radius: 0 0 0.25rem 0.25rem;
                    color: white;
                    margin-bottom: -0.5rem;
                    padding: .5rem;
                    text-align: center;

                    & a {
                        color: white;
                    }
                }
            }
        }
    }

    .aside {
        background-color: #32383e;
        height: calc(100vh);
        margin-top: 56px;
        position: fixed;
        padding: .75rem;
        left: -1rem;
        width: 14rem;

        ul {
            padding-left: .63rem;

            li {
                background-color: transparent;

                a {
                    color: white;
                }
            }
        }
    }
</style>
