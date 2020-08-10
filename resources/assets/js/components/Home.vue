<template>
  <div id="app">
    <br />
    <div>
      <center>
        <h4>
          Witamy w aplikacji
          <br />Katolickiego Stowarzyszenia Młodzieży
          <br />Diecezji Legnickiej
        </h4>
      </center>
      <br />
      <img alt="KSM logo" src="./assets/logo.png" />
      <br />
    </div>
    <br />
    <div v-if="!this.$store.state.isLoggedIn">
      <h6>
        Zaloguj się, aby uzyskać dostęp do tablicy ogłoszeń.
        <br />
      </h6>
      <div>
        <router-link id="reg" :to="{ name: 'login' }" class="nav-link">
          <button class="btn btn-primary">Zaloguj się</button>
        </router-link>
      </div>
      <h6>
        Nie masz konta?
        <br />
      </h6>
      <div>
        <router-link id="reg" :to="{ name: 'register' }" class="nav-link">
          <button class="btn btn-primary">Zarejestruj się</button>
        </router-link>
      </div>
      <h5>
        <br />Czym jest Katolickie Stowarzysznie Młodzieży? Zobacz na naszej
        <a
          href="http://ksm.legnica.pl"
        >stronie! -></a>
      </h5>
    </div>
    <div v-else>
      <router-link id="dash" :to="{ name: 'dashboard' }" class="nav-link">
          <button class="btn btn-large btn-primary">Wiadomości</button>
        </router-link>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    is_logged() {
      return this.$store.state.isLoggedIn;
    },
  },
  watch: {
    is_logged: function () {
      if (is_logged === true) this.$router.push("dashboard");
    },
  },
  methods: {
    created: function () {
      // if (is_logged===true)
      this.$router.push({ name: "login" });
    },
  },
};
</script>

<style scoped>
#app {
  text-align: center;
}
.center {
  padding: 10%;
}
</style>