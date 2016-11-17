<template>
<section class="hero is-fullheight">
    <div class="container cards-container">
        <div class="card"
            v-bind:class="!item.facedown ? item.card : ''"
            v-for="(item, index) in words"
        >
            <div class="word-tag">
                <span class="tag">{{ index }}</span>
            </div>
            <transition name="bounce">
                <div class="word-wrapper" v-if="!item.facedown">
                    <span class="word">{{ item.word }}</span>
                </div>
            </transition>
        </div>
    </div>
</section>
</template>

<script type="text/babel">
    let socket = io('http://localhost:3000');

    export default {
        mounted() {
            this.words = JSON.parse(this.passedWords);

            socket.on("charades:App\\Events\\SpyCodesWordRevealed", this.handleWordRevealed);

            socket.on("charades:App\\Events\\SpyCodesResetGame", this.handleGameReset);
        },

        props: ['passedWords'],

        data() {
            return {
                words: []
            }
        },

        methods: {
            handleWordRevealed: function(message) {
                if(message.data.wordKey === undefined) {
                    return;
                }

                let word = this.words[message.data.wordKey];
                word.facedown = false;
            },

            handleGameReset: function(message) {
                if(message.data.words === undefined) {
                    return;
                }

                this.words = message.data.words;
            }
        },

    }
</script>