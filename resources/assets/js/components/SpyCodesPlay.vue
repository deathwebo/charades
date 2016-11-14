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

        props: ['passedWords', 'revealWordUrl'],

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
            }
        }

    }
</script>