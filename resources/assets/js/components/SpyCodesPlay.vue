<template>
<section class="hero is-fullheight">
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