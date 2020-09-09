<template>
  <div>
    <nav class="navbar navbar-expand navbar-light bg-light sticky-top">
      <div id="nav">
        <ul class="left navbar-nav">
          <li v-if="!this.$store.state.isLoggedIn" class="hide-on-med-and-down">
            <router-link :to="{ name: 'home' }" class="nav-link">
              <img
                alt="KSM logo"
                src="./components/assets/icon.png"
                width="40"
                class="navbar-brand"
              />
            </router-link>
          </li>
          <li v-if="this.$store.state.isLoggedIn" data-target="slide-out" class="sidenav-trigger hide-on-med-and-down ">
            <a class=" nav-link" >
              <img
                alt="KSM logo"
                src="./components/assets/icon.png"
                width="40"
                class=" navbar-brand"
              />
            </a>
           <li v-if="this.$store.state.isLoggedIn" class="show-on-medium-and-down">
            <a data-target="slide-out" class="sidenav-trigger  nav-link">
              <img
                alt="KSM logo"
                src="./components/assets/icon.png"
                width="40"
                class="navbar-brand"
              />
            </a>
          </li>
          <li v-else class="show-on-medium-and-down">
           <a data-target="slide-out" class="sidenav-trigger ">
              <img
                alt="KSM logo"
                src="./components/assets/icon.png"
                width="40"
                class="navbar-brand"
              />
            </a>
          </li>
         
          <li>
            <div class="header nav">
              <span v-if="this.$store.state.isLoggedIn">Witaj {{ name}}!</span>
            </div>
          </li>
        </ul>

        <ul class="right navbar-nav hide-on-med-and-down">
          <li class="right">
            <router-link :to="{ name: 'events' }" class="nav-link">Kalendarium</router-link>
          </li>
          <li
            v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
            class="right"
          >
            <router-link id="panel" :to="{ name: 'panel' }" class="nav-link">Panel sterowania</router-link>
          </li>
          <li
            v-if="this.$store.state.isLoggedIn  &&(!(this.$store.state.is_leadership || this.$store.state.is_management) || !this.$store.state.is_authorized)"
            class="right"
          >
            <router-link :to="{ name: 'edit' }" class="nav-link">Edytuj swoje dane</router-link>
          </li>
          <li />

          <li v-if="!this.$store.state.isLoggedIn">
            <router-link :to="{ name: 'contact' }" class="nav-link">Kontakt</router-link>
          </li>
          <li v-if="!this.$store.state.isLoggedIn">
            <router-link :to="{ name: 'login' }" class="nav-link">Zaloguj się</router-link>
          </li>

          <li v-if="!this.$store.state.isLoggedIn">
            <router-link :to="{ name: 'register' }" class="nav-link">Zarejestruj się</router-link>
          </li>

          <li id="log-out" v-if="this.$store.state.isLoggedIn">
            <a href="#" @click="logout()" class="nav-link">Wyloguj się</a>
          </li>
        </ul>
      </div>
    </nav>

    <ul id="slide-out" class="sidenav sidenav-close ">
      <li>
        <div class="user-view ">
          <div class="background yellow darken-1">
        <img src="./components/assets/DSC02533369_.jpg" height="200">
      </div><br><br>
            <span class="white-text name">Witaj {{name}}</span>
          
        </div>
      </li>
      <li v-if="!this.$store.state.isLoggedIn">
        <router-link :to="{ name: 'home' }" class="nav-link">Strona główna</router-link>
      </li>
      <li v-if="this.$store.state.isLoggedIn">
        <router-link :to="{ name: 'dashboard' }" class="nav-link">Moja tablica ogłoszeń</router-link>
      </li>
      <li>
        <router-link :to="{ name: 'events' }" class="nav-link">Kalendarium</router-link>
      </li>
      <li
        v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
      >
        <router-link id="panel" :to="{ name: 'panel' }" class="nav-link">Panel sterowania</router-link>
      </li>
      <li>
        <div class="divider"></div>
      </li>
      <!-- <li>
        <a v-if="this.$store.state.isLoggedIn" class="subheader">Twoje konto</a>
      </li> -->
      <li v-if="!this.$store.state.isLoggedIn">
        <router-link :to="{ name: 'login' }" class="nav-link">Zaloguj się</router-link>
      </li>
      <li v-if="!this.$store.state.isLoggedIn">
        <router-link :to="{ name: 'register' }" class="nav-link">Zarejestruj się</router-link>
      </li>
      <li v-if="this.$store.state.isLoggedIn">
        <router-link :to="{ name: 'edit' }" class="nav-link">Edytuj swoje dane</router-link>
      </li>
      <li />
      <li
        v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
      >
        <router-link :to="{ name: 'delegate' }" class="nav-link">Deleguj uprawnienia</router-link>
      </li>
      <li id="log-out" v-if="this.$store.state.isLoggedIn" class="">
        <a @click="logout()" >
          Wyloguj się
        </a>
      </li>
      <li>
        <div
          v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && (this.$store.state.is_leadership || this.$store.state.is_management)"
          class="divider"
        ></div>
      </li>
      <!-- <li>
        <a
          v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && (this.$store.state.is_leadership || this.$store.state.is_management)"
          class="subheader"
        >Ogłoszenia</a>
      </li> -->
      <li>
        <router-link
          :to="{ name: 'editmessages' }"
          class="nav-link"
          v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
        >Edytuj swoje ogłoszenia</router-link>
      </li>
      <li>
        <router-link
          :to="{ name: 'message' }"
          class="nav-link"
          v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && (this.$store.state.is_leadership || this.$store.state.is_management)"
        >Nowa wiadomość</router-link>
      </li>
      <div v-if="this.$store.state.is_leadership || this.$store.state.is_management">
        <li>
          <div class="divider"></div>
        </li>
        <!-- <li>
          <a v-if="this.$store.state.isLoggedIn" class="subheader">Zarządzaj członkami</a>
        </li> -->
        <li>
          <router-link
            :to="{ name: 'adduser' }"
            class="nav-link"
            v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized &&(this.$store.state.is_leadership || this.$store.state.is_management)"
          >Dodaj członka</router-link>
        </li>
        <li>
          <router-link
            :to="{ name: 'authorize' }"
            class="nav-link"
            v-if="this.$store.state.isLoggedIn && this.$store.state.is_authorized && this.$store.state.is_leadership"
          >Zatwierdzaj członków oddziału</router-link>
        </li>
      </div>
      <div v-if="this.$store.state.is_management">
        <li>
          <div class="divider"></div>
        </li>
        <!-- <li>
          <a class="subheader">Oddziałami</a>
        </li> -->
        <li>
          <router-link
            :to="{ name: 'divisions' }"
            class="nav-link"
            v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && this.$store.state.is_management"
          >Zarządzaj oddziałami</router-link>
        </li>
        <li>
          <router-link
            :to="{ name: 'newdivision' }"
            class="nav-link"
            v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && this.$store.state.is_management"
          >Nowy oddział</router-link>
        </li>
        <li>
          <router-link
            :to="{ name: 'chart' }"
            class="nav-link"
            v-if="this.$store.state.isLoggedIn &&this.$store.state.is_authorized && this.$store.state.is_management"
          >Statystyki</router-link>
        </li>
      </div>

      <li>
        <div class="divider"></div>
      </li>
      <li>
        <router-link :to="{ name: 'contact' }" class="nav-link">Kontakt</router-link>
      </li>
    </ul>
    <br />
    <transition name="fade" mode="out-in">
      <router-view></router-view>
    </transition>
  </div>
</template>

<script>
export default {
  computed: {
    name() {
      return this.$store.state.name;
    },
  },
  methods: {
    logout() {
      this.axios
        .get("/api/auth/logout?token=" + this.$store.state.token)
        .then((response) => {
          if (response.data.success == true) {
            // login user, store the token and redirect to dashboard
            this.$store.commit("LogoutUser");
            this.$router.push({ name: "login" });
          }
        })
        .catch((error) => {
          this.$store.commit("LogoutUser");
          this.$router.push({ name: "login" });
        });
    },
    checkToken() {
      if (this.$store.state.isLoggedIn) {
        var myThis = this;
        this.axios
          .post(
            "/api/auth/getUser?token=" +
              this.$store.state.token +
              "&email=" +
              this.$store.state.email
          )
          .then(
            function (response) {
              this.$store.commit("refreshUser", response.data[0]);
            }.bind(this)
          )
          .catch((error) => {
            if (error.response && error.response.status === 401) {
              myThis.$store.commit("LogoutUser");
              myThis.$router.push({ name: "login" });
            }
            return Promise.reject(error.response);
          });
      }
    },
    getUser: function () {
      this.axios
        .post(
          "/api/auth/getUser?token=" +
            this.$store.state.token +
            "&email=" +
            this.$store.state.email
        )
        .then(
          function (response) {
            this.$store.commit("refreshUser", response.data[0]);
          }.bind(this)
        );
    },
  },
  created: function () {
    this.checkToken();
    // if (this.$store.state.division === 0 && this.$store.state.isLoggedIn){
    //     this.getUser();
    // }
    document.addEventListener("DOMContentLoaded", function () {
      var elems = document.querySelectorAll(".sidenav");
      var options = {};
      var instances = M.Sidenav.init(elems, options);
    });
  },
};
</script>

<style scoped>
.header {
  color: black;
  font-size: x-large;
  font-weight: 550;
  align-content: center;
  align-items: center;
  padding: 7.5px;
}

.fade-enter-active,
.fade-leave-active {
  transition-duration: 0.2s;
  transition-property: opacity;
  transition-timing-function: ease;
}

.fade-enter,
.fade-leave-active {
  opacity: 0;
}

#nav {
  width: 100%;
}
.container {
  padding-left: 9%;
  padding-right: 9%;
}
.navbar {
  text-align: center;
  padding-left: 10%;
  padding-right: 10%;
}

@media (max-device-width: 900px) {
  .navbar {
    text-align: center;
    padding-left: 0%;
    padding-right: 0%;
    width: 100%;
  }
}

.nav-link {
  font-weight: 400 !important;
  color: black !important;
}
</style>