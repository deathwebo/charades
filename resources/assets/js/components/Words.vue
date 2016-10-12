<template>
    <div class="container">
       <div class="columns">
           <div class="column">
               <div class="columns">
                   <div class="column">
                       <p class="control has-icon has-icon-right">

                           <input v-model="newWord"
                              class="input"
                              v-bind:class="{'is-danger': error}"
                              @keyup.enter="addNewWord"
                              type="text" placeholder="Nueva palabra" />

                           <i class="fa fa-warning" v-if="error"></i>
                           <span class="help is-danger" v-if="error">{{ error }}</span>

                       </p>
                   </div>
                   <div class="column">
                       <button class="button is-primary"
                           v-on:click="addNewWord"
                       >Agregar</button>
                   </div>
               </div>
           </div>
           <div class="column content">
                <ul>
                    <li v-for="word in words">
                        {{ word }}
                    </li>
                </ul>
           </div>
       </div>
   </div>
</template>

<script type="text/babel">
    export default {

        mounted() {
            // GET /someUrl
            this.$http.get('words').then((response) => {
                // success callback
                this.words = response.body.words;
            }, (response) => {
                // error callback
            }).bind(this);
        },

        data() {
            return {
                newWord: "",
                error: "",
                words: []
            }
        },

        methods: {
            addNewWord() {
                this.error = '';

                // POST /someUrl
                this.$http.post('word', {name: this.newWord}).then((response) => {

                    if(!response.body.response) {
                        this.error = response.body.reason;
                        return this.error;
                    }

                    this.words.push(this.newWord);
                    this.newWord = "";
                }, (response) => {
                    this.newWord = "";
                }).bind(this);
            }
        }

    }
</script>
