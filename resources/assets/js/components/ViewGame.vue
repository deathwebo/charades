<template>

    <div class="columns">
        <div class="column">
            <h2 class="subtitle">Jugando equipo #{{ game.current_time }}</h2>
        </div>
        <div class="column">
            <h2 class="subtitle">{{ game.team1.last_user }} esta jugando</h2>
        </div>
    </div>

</template>

<script type="text/babel">

    export default {
        mounted() {
            console.log(this.passedGame);
        },

        props: ['passedGame'],

        data() {
            return {
                'game': {
                    team1: {

                    },
                    team2: {

                    }
                }
            }
        }
    }

</script>