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
            <div class="word-wrapper" v-if="!item.facedown">
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

    }
</script>