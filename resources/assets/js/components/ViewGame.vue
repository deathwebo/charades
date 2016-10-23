<template>
<div class="container">

    <h1 class="title is-1 has-text-centered">
        {{ remainingTime }} s
        <span class="icon is-large">
            <i class="fa fa-clock-o"></i>
        </span>
    </h1>

    <div class="columns">
        <div class="column">
            <h2 class="title">Jugando equipo:</h2>
            <span class="tag is-large is-info">#{{ game.current_team }}</span>
        </div>
        <div class="column">
            <h2 class="title">Turno de: </h2> 
            <span class="tag is-large is-primary">{{ currentPlayer }}</span>
        </div>
        <div class="column" v-if="category">
            <h2 class="title">La categoría es:</h2>
            <span class="tag is-large">{{ category }}</span>
        </div>
    </div>

    <div class="columns">
        <div class="column">
            <h2 class="subtitle">Equipo 1, puntos: <span class="tag is-large is-warning">{{ game.team1.score }}</span></h2>
            <div class="columns">
                <div class="column">
                   <h3>Jugadores</h3>
                    <ul>
                        <li v-for="player in game.team1.players">
                            <span class="tag is-medium">{{ player }}</span>
                        </li>
                    </ul>
                </div>
                <div class="column">
                    <h3>Participaron/activos</h3>
                    <ul>
                        <li v-for="player in game.team1.round_players">
                            <span class="tag is-medium is-success">{{ player }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="column">
            <h2 class="subtitle">Equipo 2, puntos: <span class="tag is-large is-danger">{{ game.team2.score }}</span></h2>
            <div class="columns">
                <div class="column">
                    <h3>Jugadores</h3>
                    <ul>
                        <li v-for="player in game.team2.players">
                            <span class="tag is-medium">{{ player }}</span>
                        </li>
                    </ul>
                </div>
                <div class="column">
                    <h3>Participaron/activos</h3>
                    <ul>
                        <li v-for="player in game.team2.round_players">
                            <span class="tag is-medium is-success">{{ player }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
</template>

<script type="text/babel">
    let socket = io('http://localhost:3000');

    export default {
        mounted() {

            this.$set(this, 'game',this.passedGame);

            socket.on("charades:App\\Events\\PlayerStartedTurn", this.handlePlayerStartedTurnEvent);

            socket.on("charades:App\\Events\\PlayerFinishedTurn", this.handlePlayerFinishedTurnEvent);

        },

        props: ['passedGame'],

        data() {
            return {
                'game': {
                    team1: Object,
                    team2: Object
                },
                remainingTime: 0,
                tId: "",
                category: ""
            }
        },

        computed: {
            currentPlayer() {
                if(this.game.current_team == '1') {
                    return this.game.team1.current_player;
                }

                return this.game.team2.current_player;
            }
        },

        methods: {
            handlePlayerStartedTurnEvent(message) {

                this.category = message.data.word.category.name;

                if(this.tId) {
                    clearInterval(this.tId);
                }

                let time = this.game.turn_time;

                this.remainingTime = time;

                this.tId = setInterval(() => {

                    time--;

                    this.remainingTime = time;

                    if(time == 0) {
                        clearInterval(this.tId);
                    }

                }, 1000);
            },

            handlePlayerFinishedTurnEvent(message) {

                this.category = '';

                if(this.tId) {
                    clearInterval(this.tId);
                }

                this.$set(this, 'game',message.data.game);
            }
        }
    }

</script>