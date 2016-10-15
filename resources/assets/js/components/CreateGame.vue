<template>

    <form v-on:submit.prevent>


        <div class="columns">

            <div class="column">
                <div class="control">
                    <label for="turn_time" class="label">Tiempo por turno en segundos</label>
                    <input type="number" class="input" id="turn_time" name="turn_time"
                           v-model="turnTime"
                           min="30" max="300" step="5">
                </div>
            </div>

            <div class="column">
                <div class="control">
                    <label for="" class="label">Miembros del equipo #1</label>
                    <div class="columns">
                        <div class="column is-three-quarters">
                            <input type="text" class="input" id="team_1" name="team_1"
                               v-model="newTeamMember1"
                               @keyup.enter="addMemberToTeam(1)"
                            >
                        </div>
                        <div class="column">
                            <button class="button is-primary">Agregar</button>
                        </div>
                    </div>
                </div>

                <div class="team-members">
                   <ul>
                      <li v-for="(member, index) in teamMembers1" >
                          <button
                              v-on:click="teamMembers1.splice(index, 1)"
                              class="button">{{ member }}</button>
                      </li>
                   </ul>
                </div>

            </div>

            <div class="column">
                <div class="control">
                    <label for="" class="label">Miembre del equipo #2</label>
                    <div class="columns">
                        <div class="column is-three-quarters">
                            <input type="text" class="input" id="team_2" name="team_2"
                               v-model="newTeamMember2"
                               @keyup.enter="addMemberToTeam(2)"
                            >
                        </div>
                        <div class="column">
                            <button
                                v-on:click="teamMembers1.splice(index, 1)"
                                class="button is-primary">Agregar</button>
                        </div>
                    </div>
                </div>

                <div class="team-members">
                    <ul>
                        <li v-for="member in teamMembers2" >
                            <button class="button">{{ member }}</button>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <button
            v-on:click="createGame"
            class="button is-success is-large">INICIAR</button>

    </form>

</template>

<script type="text/babel">
    export default {
        mounted() {

        },

        data() {
            return {
                turnTime: "",
                newTeamMember1: "",
                newTeamMember2: "",
                teamMembers1: [],
                teamMembers2: [],
            }
        },

        methods: {
            addMemberToTeam(teamNumber) {
                event.preventDefault();

                if(teamNumber == 1) {
                    this.teamMembers1.push(this.newTeamMember1);
                    this.newTeamMember1 = "";
                }

                if(teamNumber == 2) {
                    this.teamMembers2.push(this.newTeamMember2);
                    this.newTeamMember2 = "";
                }
            },

            createGame() {
                let postData = {
                    turnTime: this.turnTime,
                    teamMembers1: this.teamMembers1,
                    teamMembers2: this.teamMembers2
                }


                this.$http.post('game', postData).then((response) => {



                }, (response) => {

                }).bind(this);
            }

        }

    }
</script>