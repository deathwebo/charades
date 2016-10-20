<template>
    <div class="container">
       <div class="columns">
           <div class="column is-three-quarters">
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

                   <div class="column control is-grouped">
                       <p class="control">
                           <label for="category" class="label">Categoría</label>
                       </p>
                       <p class="control">
                           <span class="select">
                               <select name="category" id="category" v-model="selectedCategory">
                                   <option v-for="category in categories"
                                       v-bind:value="category.id">{{ category.name }}</option>
                               </select>
                       </p>

                       <p v-if="catError">
                           <i class="fa fa-warning" ></i>
                           <span class="help is-danger">{{ catError }}</span>
                           </span>
                       </p>

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

            this.selectedCategory = this.categories[0].id;

            this.$http.get('words').then((response) => {
                // success callback
                this.words = response.body.words;
            }, (response) => {
                // error callback
            }).bind(this);
        },

        props: ['categories'],

        data() {
            return {
                newWord: "",
                error: "",
                catError: "",
                words: [],
                selectedCategory: {}
            }
        },

        methods: {
            addNewWord() {

                if(!this.selectedCategory) {
                    this.catError = "Selecciona una opción";
                    return;
                }


                this.catError = '';
                this.error = '';

                // POST /someUrl
                this.$http.post('word', {name: this.newWord, category: this.selectedCategory}).then((response) => {

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
