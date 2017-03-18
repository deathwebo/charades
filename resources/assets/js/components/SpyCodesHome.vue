<template>
<div>

    <div class="columns">
        <div class="column is-three-quarters">
            <h1 class="title">
                <span>SPYCODES: Listado de juegos</span>
            </h1>
        </div>
        <div class="column">
            <a class="button is-primary is-large"
            v-on:click="modal(true, $event)"
            >Crear Juego</a>
        </div>
    </div>

    <hr />

    <div class="columns">
        <div class="column is-one-quarter control">
            <input type="text" placeholder="Buscar juego" class="input" v-model="search">
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>¿Tiene password?</th>
                <th>Accesos</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="game in filteredGames">
                <td>{{ game.id }}</td>
                <td>{{ game.name }}</td>
                <td>
                    <span class="tag is-success" v-if="game.hasPassword == '1'">Si</span>
                    <span class="tag" v-if="game.hasPassword != '1'">No</span>
                </td>
                <td>
                    <a :href="viewUrl(game)" class="button">
                        Tablero
                        <span class="icon">
                            <i class="fa fa-gamepad"></i>
                        </span>
                    </a>
                    <a :href="gameUrl(game)" class="button" v-on:click="accessGame(game,$event)">
                        Capitanes
                        <span class="icon">
                            <i class="fa fa-user-secret"></i>
                        </span>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="modal" v-bind:class="{ 'is-active': modalShown }">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="box">
                <form>
                    <label class="label">Nombre</label>
                    <p class="control">
                        <input type="text" id="game_name" name="name" class="input" v-model="newGame.name">
                    </p>
                    <label class="label">Usar password?</label>
                    <p class="control">
                        <label class="radio">
                            <input type="radio" name="hasPassword" value="0" 
                            v-model="newGame.hasPassword">
                            No
                        </label>
                        <label class="radio">
                            <input type="radio" name="hasPassword" value="1" 
                            v-model="newGame.hasPassword">
                            Si
                        </label>
                    </p>
                    <label class="label" v-if="newGame.hasPassword == '1'">Password</label>
                    <p class="control" v-if="newGame.hasPassword == '1'">
                        <input type="password" name="password" id="game_password" class="input" v-model="newGame.password">
                    </p>

                    <div class="control is-grouped">
                        <p class="control">
                            <button class="button is-primary" v-on:click="createGame">Crear</button>
                        </p>
                        <p class="control">
                            <button class="button is-link" v-on:click="modal(false, $event)">Cancelar</button>
                        </p> 
                    </div>
                </form>
            </div>
        </div>
        <button class="modal-close" v-on:click="modal(false, $event)"></button>
    </div>

    <div class="modal" v-bind:class="{ 'is-active': showPasswordPrompt }">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="box">
                <form>
                    <input type="hidden" name="id" v-model="validation.id">
                    <label class="label">Password</label>
                    <p class="control">
                        <input type="password" name="password" class="input" v-model="validation.password">
                    </p>

                    <div class="notification is-danger" v-if="validation.badPassword">
                    Error en el password
                    </div>

                    <div class="control is-grouped">
                        <p class="control">
                            <button class="button is-primary" v-on:click="validateGamePassword">Acceder</button>
                        </p>
                        <p class="control">
                            <button class="button is-link" v-on:click="passwordPrompt(false, $event)">Cancelar</button>
                        </p> 
                    </div>
                </form>
            </div>
        </div>
        <button class="modal-close" v-on:click="passwordPrompt(false, $event)"></button>
    </div>

</div>
</template>

<script>
export default {
    mounted() {
        this.games = JSON.parse(this.passedGames);
    },

    props: ['passedGames', 'createGameUrl', 'gameViewUrl', 'gamePlayUrl', 'validatePasswordUrl'],

    data() {
        return {
            'games': [],
            'modalShown': false,
            'showPasswordPrompt': false,
            'search': '',
            'newGame': {
                'name': '',
                'hasPassword': 0,
                'password': ''
            },
            'validation': {
                'id': '',
                'password': '',
                'badPassword': false
            }
        }
    },

    computed: {
        filteredGames() {
            return this.findGames(this.games, this.search)
        }
    },

    methods: {

        findGames(games, value) {
            return games.filter(function (game) {
                return game['name'].includes(value)
            });
        },

        modal(state, event) {
            this.modalShown = state;
            event.preventDefault();
        },

        passwordPrompt(state, event) {
            this.showPasswordPrompt = state;
            event.preventDefault();
        },

        createGame(e) {
            e.preventDefault();

            if(this.newGame.name == '') {
                return;
            }

            this.$http.post(this.createGameUrl, this.newGame)
            .then((response) => {

                let url = this.gameViewUrl.replace('__XXX__', response.body.gameId);

                window.location.href = url;
            }, (response) => {
                console.log('error guardando el juego');
            }).bind(this);
        },

        viewUrl(game) {
            return this.gameViewUrl.replace('__XXX__', game.id);
        },

        gameUrl(game) {
            return this.gamePlayUrl.replace('__XXX__', game.id);
        },

        accessGame(game, event) {
            if(game.hasPassword != 1) {
                return;
            }
            event.preventDefault();
            this.validation.id = game.id;
            this.showPasswordPrompt = true;
        },

        validateGamePassword(event) {
            event.preventDefault();

            this.$http.post(this.validatePasswordUrl, this.validation)
            .then((response) => {
                let url = this.gamePlayUrl.replace('__XXX__', this.validation.id);

                window.location.href = url;
            }, (response) => {
                console.log(response);
                this.validation.badPassword = true;
            }).bind(this);
        }
    },

}
</script>