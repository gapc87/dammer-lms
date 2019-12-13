<template>
    <div class="modal fade" id="destroyModule" tabindex="-1" role="dialog" aria-labelledby="destroyCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="destroyCenterTitle">Eliminar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Realmente desea eliminar el módulo con nombre {{name}}?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" @click="destroyModule">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DeleteModuleModal",

        props: {
            id: Number,
            name: String,
        },

        methods: {
            destroyModule() {
                api.call('delete', '/api/admin/modules/' + this.id)
                    .then((response) => {
                        this.$emit('removed');
                        this.closeModal();
                    })
                    .catch((error) => console.log(error))
                    .finally();
            },

            closeModal() {
                $('#destroyModule').modal('hide');
            }
        }
    }
</script>

<style scoped>

</style>
