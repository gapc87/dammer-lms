<template>
    <form class="form-inline" @submit.prevent="evaluate" v-if="final_score === null">
        <div class="d-block mx-auto">
            <label class="sr-only" for="evaluate">Name</label>
            <input type="text" size="3" class="form-control" id="evaluate" v-model="score" placeholder="Nota">

            <button type="submit" class="btn btn-primary">Evaluar</button>
        </div>
    </form>
    <span v-else>
        {{final_score}}
    </span>
</template>

<script>
    export default {
        name: "EvaluateTask",

        data() {
            return {
                score: null,
                final_score: null
            }
        },

        props: {
            student_id: Number,
            task_id: Number
        },

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
                    .then((response) => this.setScore(response.data.data.score))
                    .catch((error) => console.log(error))
                    .finally();
            },

            setScore(score) {
                this.final_score = score;
            }
        }
    }
</script>

<style scoped>

</style>
