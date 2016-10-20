<template>
<div class="container">

    <h1 class="title is-1 has-text-centered">00:00:00</h1>

    <div class="columns">
        <div class="column">
            <h2 class="subtitle">Jugando equipo #{{ game.current_team }}</h2>
        </div>
        <div class="column">
            <h2 class="subtitle">{{ game.team1.current_player }} esta jugando</h2>
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
            <h2 class="subtitle">Equipo 2, puntos: {{ game.team1.score }}</h2>
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

    export default {
        mounted() {

            this.$set(this, 'game',this.passedGame);

        },

        props: ['passedGame'],

        data() {
            return {
                'game': {
                    team1: Object,
                    team2: Object
                }
            }
        }
    }

</script>