<template>
    <div class="container">
      <h1>Witaj {{ name}}!</h1>
            <div id="nav">
                    <ul>
                        
                        <li v-if="this.$store.state.isLoggedIn">
                            <router-link :to="{ name: 'dashboard' }" class="nav-link" > Wiadomości</router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn" >
                            <router-link :to="{ name: 'edit' }" class="nav-link">Edytuj swoje dane</router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)" class="right">
                            <router-link :to="{ name: 'adduser' }" class="nav-link">Dodaj członka</router-link>
                        </li>
                         <li v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized && this.$store.state.is_leadership" class="right">
                            <router-link :to="{ name: 'authorize' }" class="nav-link">Zatwierdzaj</router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)" class="right">
                            <router-link :to="{ name: 'editmessages' }" class="nav-link">Edytuj wiadomości</router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && (this.$store.state.is_leadership || this.$store.state.is_management)" class="right">
                            <router-link :to="{ name: 'message' }" class="nav-link">Nowa wiadomość</router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && this.$store.state.is_management" class="right">
                            <router-link :to="{ name: 'divisions' }" class="nav-link">Zarządzaj oddziałami</router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && this.$store.state.is_management" class="right">
                            <router-link :to="{ name: 'newdivision' }" class="nav-link">Nowy oddział</router-link>
                        </li>
                    </ul>
                   
            </div>
      
    </div>
</template>

<script> 

    export default {
        methods: {

        },
        computed: {
    name() {
      return this.$store.state.name;
    },
    }
    }

</script>

<style scoped>
   
</style>