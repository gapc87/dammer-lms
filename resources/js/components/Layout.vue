<template>
    <router-view></router-view>
</template>

<script>
    import {mapActions} from "vuex";

    export default {
        name: "Layout",

        created() {
            this['setStateUserFromLocalStorage']();
        },

        methods: {
            ...mapActions('user', ['setStateUserFromLocalStorage'])
        }
    }
</script>

<style scoped>

</style>