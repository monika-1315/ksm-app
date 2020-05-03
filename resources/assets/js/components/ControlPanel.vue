<template>
  <div class="container">
    <h2>Panel sterowania</h2>
    <hr>
    <h4>Twoje konto</h4>
    <router-link :to="{ name: 'edit' }" class="" v-if="this.$store.state.isLoggedIn">
      <button class="btn-light btn-large">Edytuj swoje dane</button>
    </router-link>
<hr>
   
   <h4>Ogłoszenia</h4>
    <router-link
      :to="{ name: 'editmessages' }"
      class=""
      v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
    >
      <button class="btn-light btn-large left-btn">Edytuj swoje ogłoszenia</button>
    </router-link>

    <router-link
      :to="{ name: 'message' }"
      class=""
      v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && (this.$store.state.is_leadership || this.$store.state.is_management)"
    >
      <button class="btn-light btn-large right-btn">Nowa wiadomość</button>
    </router-link>

<hr>
    <h4 v-if="this.$store.state.is_leadership || this.$store.state.is_management"> Zarządzaj członkami:</h4>
    <router-link
      :to="{ name: 'adduser' }"
      class=""
      v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
    >
      <button class="btn-light btn-large left-btn">Dodaj członka</button>
    </router-link>

    <router-link
      :to="{ name: 'authorize' }"
      class=""
      v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized && this.$store.state.is_leadership"
    >
      <button class="btn-light btn-large right-btn">Zatwierdzaj członków oddziału</button>
    </router-link>

<hr>
   <h4 v-if="this.$store.state.is_management"> Zarządzaj oddziałami:</h4>
    <router-link
      :to="{ name: 'divisions' }"
      class=""
      v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && this.$store.state.is_management"
    >
      <button class="btn-light btn-large left-btn">Zarządzaj oddziałami</button>
    </router-link>

    <router-link
      :to="{ name: 'newdivision' }"
      class=""
      v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && this.$store.state.is_management"
    >
      <button class="btn-light btn-large right-btn">Nowy oddział</button>
    </router-link>

  <br><br>
    <router-link :to="{ name: 'dashboard' }" class="" v-if="this.$store.state.isLoggedIn">
      <button class="btn btn-primary right">Wróć do tablicy ogłoszeń</button>
    </router-link>
  </div>
</template>

<script>
export default {
  methods: {},
  computed: {
    name() {
      return this.$store.state.name;
    }
  }
};
</script>

<style scoped>
.btn-light{
    width: 23em;
}

button{
    display: inline-block;
    margin: 1em;
    font-weight: 500;
}
h4{
    text-indent: 1em;
}

</style>