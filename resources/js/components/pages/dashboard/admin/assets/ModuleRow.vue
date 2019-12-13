<template>
    <tr>
        <th scope="row">{{index}}</th>
        <td>{{name}}</td>
        <td>{{teacher.name}}</td>
        <td>{{students}}</td>
        <td class="text-center">
            <a data-toggle="modal" href="#!" @click="$emit('edit-module', module)">
                <font-awesome-icon icon="edit" size="lg" />
            </a>
            <a href="#!" @click="$emit('destroy-module', module)" class="text-danger">
                <font-awesome-icon icon="trash-alt" size="lg" />
            </a>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "ModuleRow",

        props: {
            index: Number,
            id: Number,
            name: String,
            teacher: Object,
            students: Number
        },

        computed: {
            customId() {
                return 'edit-' + this.id;
            },

            module() {
                return {
                    id: this.id,
                    name: this.name,
                    teacher: this.teacher,
                    students: this.students
                }
            }
        }
    }
</script>

<style scoped>

</style>
