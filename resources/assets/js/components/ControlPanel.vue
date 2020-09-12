<template>
  <div class="container" style="text-align: center">
    <h2>Panel sterowania</h2>
    <hr />
     <h4>Wydarzenia:</h4>
    <router-link :to="{ name: 'events' }" class v-if="this.$store.state.isLoggedIn">
      <button class="btn-light btn-large" id="events">Kalendarium</button>
    </router-link>
    <router-link
      :to="{ name: 'newevent' }"
      v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
    >
      <button class="btn-light btn-large left-btn">Nowe wydarzenie</button>
    </router-link>
    <br>
    <h4>Twoje konto:</h4>
    <router-link :to="{ name: 'edit' }" class v-if="this.$store.state.isLoggedIn">
      <button class="btn-light btn-large" id="edit_data">Edytuj swoje dane</button>
    </router-link>
    <router-link
      :to="{ name: 'delegate' }"
      class
      v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
    >
      <button class="btn-light btn-large left-btn">Deleguj uprawnienia</button>
    </router-link>
    <!-- <hr> -->

    <h4>Ogłoszenia:</h4>
    <router-link
      :to="{ name: 'editmessages' }"
      class
      v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
    >
      <button class="btn-light btn-large left-btn">Edytuj swoje ogłoszenia</button>
    </router-link>

    <router-link
      :to="{ name: 'message' }"
      class
      v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && (this.$store.state.is_leadership || this.$store.state.is_management)"
    >
      <button id="new-mes" class="btn-light btn-large right-btn">Nowa wiadomość</button>
    </router-link>

    <div v-if="this.$store.state.is_leadership || this.$store.state.is_management">
      <!-- <hr> -->
      <h4>Zarządzaj członkami:</h4>
      <router-link
        :to="{ name: 'adduser' }"
        class
        v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
      >
        <button class="btn-light btn-large left-btn">Dodaj członka</button>
      </router-link>

      <router-link
        :to="{ name: 'authorize' }"
        class
        v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized && this.$store.state.is_leadership"
      >
        <button class="btn-light btn-large right-btn">Zatwierdzaj członków oddziału</button>
      </router-link>
    </div>
    <div v-if="this.$store.state.is_management">
      <!-- <hr> -->
      <h4 v-if="this.$store.state.is_management">Zarządzaj oddziałami:</h4>
      <router-link
        :to="{ name: 'divisions' }"
        class
        v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && this.$store.state.is_management"
      >
        <button class="btn-light btn-large left-btn">Zarządzaj oddziałami</button>
      </router-link>

      <router-link
        :to="{ name: 'newdivision' }"
        class
        v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && this.$store.state.is_management"
      >
        <button class="btn-light btn-large right-btn">Nowy oddział</button>
      </router-link>

       <router-link
        :to="{ name: 'chart' }"
        class
        v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && this.$store.state.is_management"
      >
        <button class="btn-light btn-large left-btn">Statystyki</button>
      </router-link>
    </div>
    <br />
    <br />
    <router-link :to="{ name: 'dashboard' }"  v-if="this.$store.state.isLoggedIn">
      <button class="btn btn-primary right">Wróć do tablicy ogłoszeń</button>
    </router-link>

    <br />

    <router-link :to="{ name: 'contact' }"  class="nav-link" ><button id="contact" class="btn btn-light btn-small left">Kontakt</button></router-link>
  </div>
</template>

<script>
export default {
  methods: {}
};
</script>

<style scoped>
.btn-light {
  width: 23em;
}
#contact{
  width: min-content;
}
button .hover {
  background-color: rgba(248, 203, 0, 0.788);
}
button {
  display: inline-block;
  margin: 0.5em;
  font-weight: 500;
}
h4 {
  margin-top: 1em;
  /* text-indent: 1em;
    text-align: left; */
}
hr {
  border-color: #fecb00;
  /* width: 50em;
    margin-left: 0%; */
  /* border-color:  rgba(0, 0, 139, 0.719); */
}
@media (max-device-width: 900px){
  .container{
     padding-left: 1%;
  padding-right: 1%;
  }
}
</style>