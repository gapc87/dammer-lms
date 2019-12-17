<template>
    <form class="form-inline" @submit.prevent="evaluate" v-if="student_score === null">
        <div class="d-block mx-auto">
            <input type="text" size="3" class="form-control" id="evaluate" v-model="score" placeholder="Nota">

            <button type="submit" class="btn btn-primary">Evaluar</button>
        </div>
    </form>
    <span v-else>
        {{student_score}}
        <a href="#!" @click="remove()">
            <font-awesome-icon icon="edit" size="lg" />
        </a>
    </span>
</template>

<script>
    export default {
        name: "EvaluateTask",

        data() {
            return {
                score: null
            }
        },

        props: [
            'array_key', 'student_id', 'task_id', 'student_score'
        ],

        methods: {
            evaluate() {

                const score = {
                    'score': this.score
                };

                api.call('post', '/api/modules/'
                    + this.student_id
                    + '/' + this.$route.params.module
                    + '/' + this.task_id
                    + '/evaluate', score)
                    .then((response) => {
                        if (this.score !== null) {
                            this.$emit("update-score", [this.array_key, this.score]);
                        }
                    })
                    .catch((error) => console.log(error));
            },

            remove() {
                this.$emit('remove');
            }
        }
    }
</script>

<style scoped>

</style>
