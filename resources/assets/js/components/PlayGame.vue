<template>

    <section class="hero">
        <div class="hero-body">
            <div class="container">

                <div class="columns has-text-centered">
                    <div class="column">
                        <button class="button is-large is-success" v-on:click="start">COMENZAR</button>
                    </div>
                </div>

                <div class="columns is-mobile">
                    <div class="column has-text-right is-half">
                        <button class="button is-large is-primary"
                                v-on:click="finish('success')">
                            ACERTASTE
                        </button>
                    </div>
                    <div class="column has-text-left is-half">
                        <button class="button is-large is-danger"
                            v-on:click="finish('fail')">
                            FALLASTE
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

</template>

<script type="text/babel">

    export default {

        mounted() {
            this.word = JSON.parse(this.passedWord);
        },

        props: ['player', 'passedWord', 'team','startUrl', 'finishUrl', 'gameId'],

        data() {
            return {
                word: Object
            }
        },

        methods: {
            start() {


                let postData = {
                    'player': this.player,
                    'team': this.team
                };


                this.$http.post(this.startUrl.replace('XXX', this.gameId), postData).then((response) => {

                }, (response) => {

                }).bind(this);
            },

            finish(status) {
                let postData = {
                    'player': this.player,
                    'team': this.team,
                    'status': status
                };


                this.$http.post(this.finishUrl.replace('XXX', this.gameId), postData).then((response) => {
                    window.location = '/';
                }, (response) => {

                }).bind(this);
            }
        }

    }
</script>