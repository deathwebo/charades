<style lang="sass">
    .cards-container {
        display:flex;
        flex-wrap: wrap;
        flex: 1;
    }
    .card {
        flex: 1 0 20%;
        box-sizing: border-box;
        border-color: #e0ddd5;
        border-width: medium;
        border-style: solid;
        color: #171e42;
        padding: 10px;
        max-width: 100%;
        min-width: 20%;
        font-weight: bold; 
        text-transform: uppercase;
        display: flex;
        flex-direction: column;
        .word-wrapper {
            flex: 3;
            display: flex;
            align-items: center;
            text-align: center;
            .word {
                flex: 1;
            }
        }
        .word-tag {
            flex:1;
        }
    }
    .red {
        background-color: #ff2502;
        color: white;
    }
    .blue {
        background-color: #3273dc;
        color: white;
    }
    .bystander {
        background-color: lightyellow;
    }
    .assassin {
        background-color: #272727;
        color: white;
    }
</style>

<template>
<section class="hero is-fullheight">
    <div class="container cards-container">
        <div class="card"
            v-bind:class="item.card"
            v-for="(item, index) in words"
        >
            <div class="word-tag columns">
                <div class="column">
                    <span class="tag">{{ index }}</span>
                </div>
                <div class="column">
                    <button 
                        class="show-card button"
                        v-bind:class="{'is-disabled': !item.facedown}"
                        v-on:click="revealCard(index, item)"
                        >
                            {{ item.facedown ? 'mostrar' : 'mostrado' }}
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
            this.words = JSON.parse(this.passedWords);
        },

        props: ['passedWords'],

        data() {
            return {
                words: []
            }
        },

        methods: {
            revealCard(cardIndex, card) {
                card.facedown = false;
            }
        }

    }
</script>