<template>
<div class="container">

    <h1 class="title is-1 has-text-centered">{{ remainingTime }} s</h1>

    <div class="columns">
        <div class="column">
            <h2 class="subtitle">Jugando equipo #{{ game.current_team }}</h2>
        </div>
        <div class="column">
            <h2 class="subtitle">{{ currentPlayer }} esta jugando</h2>
        </div>
    </div>

    <div class="columns">
        <div class="column">
            <h2 class="subtitle">Equipo 1, puntos: {{ game.team1.score }}</h2>
            <div class="columns">
                <div class="column">
                   <h3>Jugadores</h3>
                    <ul>
                        <li v-for="player in game.team1.players">
                            {{ player }}
                        </li>
                    </ul>
                </div>
                <div class="column">
                    <h3>Participaron/activos</h3>
                    <ul>
                        <li v-for="player in game.team1.round_players">
                            {{ player }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="column">
            <h2 class="subtitle">Equipo 2, puntos: {{ game.team2.score }}</h2>
            <div class="columns">
                <div class="column">
                    <h3>Jugadores</h3>
                    <ul>
                        <li v-for="player in game.team2.players">
                            {{ player }}
                        </li>
                    </ul>
                </div>
                <div class="column">
                    <h3>Participaron/activos</h3>
                    <ul>
                        <li v-for="player in game.team2.round_players">
                            {{ player }}
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
                tId: ""
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

                if(this.tId) {
                    clearInterval(this.tId);
                }

                this.$set(this, 'game',message.data.game);
            }
        }
    }

</script>