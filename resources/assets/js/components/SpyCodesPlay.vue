<template>
<section class="hero is-fullheight">
    <div class="spycodes_menu">
        <a class="button is-success is-small" :href="resetUrl">
            <i class="fa fa-refresh" aria-hidden="true"></i>
        </a>
        <a class="button is-primary is-small" :href="homeUrl">
            <i class="fa fa-home" aria-hidden="true"></i>
        </a>
        <button class="button is-info is-small" v-on:click="toggleTimer">
            <i class="fa fa-hourglass-half" aria-hidden="true"></i>
        </button>
    </div>

    <div class="container cards-container">
        <div class="card"
            v-bind:class="item.card"
            v-for="(item, index) in words"
        >
            <div class="word-tag columns is-mobile">
                <div class="column is-narrow is-hidden-mobile">
                    <span class="tag is-small">{{ index }}</span>
                </div>
                <div class="column has-text-right">
                    <button 
                        class="show-card button is-small"
                        v-bind:class="{'is-disabled': !item.facedown}"
                        v-on:click="revealCard(index, item)"
                        >
                        <i class="fa"
                            v-bind:class="{ 'fa-unlock': !item.facedown, 'fa-lock': item.facedown }"
                        ></i>
                    </button>
                </div>
            </div>
            <div class="word-wrapper">
                <span class="word">{{ item.word }}</span>
            </div>
        </div>
    </div>
</section>
</template>

<script type="text/babel">
    export default {
        mounted() {
            let socket = io(`http://${window.location.hostname}:3000`);
            
            this.words = JSON.parse(this.passedWords);

            socket.on("charades:App\\Events\\SpyCodesWordRevealed", this.handleWordRevealed);

            socket.on("charades:App\\Events\\SpyCodesResetGame", this.handleGameReset);
        },

        props: ['passedWords', 'revealWordUrl', 'homeUrl', 'resetUrl', 'timerUrl', 'gameId'],

        data() {
            return {
                words: []
            }
        },

        methods: {
            revealCard(cardIndex, card) {

                let url = this.revealWordUrl.replace('__XXX__', cardIndex);

                this.$http.post(url).then((response) => {
                    if(response.body.word) {
                        card.facedown = false;
                    }
                }).bind(this);
            },

            toggleTimer() {

                let choice = window.confirm("¿Seguro que deseas resetear las palabras?");

                if (!choice) {
                    return;
                }

                let url = this.timerUrl;

                this.$http.post(url).then((response) => {
                    console.log(response.body);
                }).bind(this);
            },

            handleWordRevealed: function(message) {

                if(message.data.wordKey === undefined && message.data.id != this.gameId) {
                    return;
                }

                let word = this.words[message.data.wordKey];
                word.facedown = false;
            },

            handleGameReset: function(message) {
                if(message.data.words === undefined && message.data.id != this.gameId) {
                    return;
                }

                this.words = message.data.words;
            },

        }

    }
</script>