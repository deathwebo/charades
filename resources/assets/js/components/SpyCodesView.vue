<template>
<section class="hero is-fullheight">
    
    <div class="timer-container" v-if="remainingTime">

        <progress class="progress is-success" :value="remainingTime" max="100">{{remainingTime}}%</progress>

        <span class="timer tag is-success">
            {{ remainingTime }} s
        </span>
    </div>


    <div class="container cards-container">
        <div class="card"
            v-bind:class="!item.facedown ? item.card : ''"
            v-for="(item, index) in words"
        >
            <div class="word-tag columns is-mobile">
                <div class="column is-narrow is-hidden-mobile">
                    <span class="tag is-small">{{ index }}</span>
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

            socket.on("charades:App\\Events\\SpyCodesTimerToggle", this.timerToggle);
        },

        props: ['passedWords', 'gameId'],

        data() {
            return {
                words: [],
                tId: "",
                remainingTime: 0
            }
        },

        methods: {
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

            timerToggle: function(message) {

                if(message.data.id != this.gameId) {
                    return;
                }

                if(this.tId) {
                    clearInterval(this.tId);
                }

                if(this.remainingTime > 0) {

                    this.remainingTime = 0;

                    return;
                }

                let time = 90;

                this.remainingTime = time;

                this.tId = setInterval(() => {

                    time--;

                    this.remainingTime = time;

                    if(time == 0) {
                        clearInterval(this.tId);
                    }

                }, 1000);
            }
        },

    }
</script>