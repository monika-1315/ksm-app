<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="card card-default">
        <div class="card-header">Edytuj swoje dane osobowe</div>
        <div class="card-body">
          <div class="progress" v-if="isProgress">
            <div class="indeterminate"></div>
          </div>
          <form autocomplete="off" @submit.prevent="update" v-if="currentUser" method="post">
            <div class="form-group">
              <label for="name">Imię</label>
              <input :disabled="currentUser.is_authorized===1" id="name" type="text" class="validate" v-model="name" required />
              <span class="text text-danger" v-if="error && errors.name">{{ errors.name[0] }}</span>
            </div>
            <div class="form-group">
              <label for="surname">Nazwisko</label>
              <input id="surname" type="text" class="validate" v-model="surname" required />
              <span class="text text-danger" v-if="error && errors.surname">{{ errors.name[0] }}</span>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="text" class="validate" v-model="email" required />
              <span class="text text-danger" v-if="error && errors.email">{{ errors.email[0] }}</span>
            </div>
            <div class="form-group">
              <label for="password">Hasło</label>
              <input
                id="password"
                type="password"
                class="validate"
                v-model="password"
                placeholder="Wpisz i potwierdź hasło, jeżeli chcesz je zmienić"
              />
              <span
                class="text text-danger"
                v-if="error && errors.password"
              >{{ errors.password[0] }}</span>
            </div>
            <div class="form-group">
              <label for="confirm_password">Powtórz hasło</label>
              <input
                id="confirm_password"
                type="password"
                class="validate"
                v-model="confirmPassword"
              />
              <span
                class="text text-danger"
                v-if="error && errors.confirmPassword"
              >{{ errors.confirmPassword[0] }}</span>
            </div>
            <div class="form-group">
              <label for="birthdate">Data urodzenia</label>
              <input type="date" id="birthdate" v-model="birthdate" required />
              <span
                class="text text-danger"
                v-if="error && errors.birthdate"
              >{{ errors.birthdate[0] }}</span>
            </div>
            <div class="form-group">
              <label for="division">Oddział</label>
              <br />
              <select
                class="browser-default"
                v-model="division"
                :disabled="currentUser.is_authorized===1"
                required
              >
                <option v-for="divi in divisions" :value="divi.id" :key="divi.id">
                  <span>{{ ' '+divi.town+' '+divi.parish }}</span>
                </option>
              </select>
              <span
                class="text text-danger"
                v-if="error && errors.division"
              >{{ errors.division[0] }}</span>
            </div>
            <div class="form-group">
              <label for="wantMessages">Powiadomienia</label> <br>
              <label>
                <input type="checkbox" class="filled-in" id="wantMessages" v-model="wantMessages" />
                <span style="color: black">Chcę otrzymywać wiadomości email o nowych komunikatach i wydarzeniach dla mnie.</span>
              </label>
            </div>
            <div
              class="form-group"
              v-if="this.$store.state.is_leadership || this.$store.state.is_management"
            >
              <label>Uprawnienia</label>
              <br />
              <label
                style="color: rgb(211, 7, 41)"
              >Uwaga! Jeżeli zrezygnujesz z nadanych uprawnień, otrzymasz je dopiero, jeżeli inny uprawiony Ci je nada</label>
              <br />
              <label v-if="this.$store.state.is_leadership">
                <input type="checkbox" class="filled-in" id="leader" v-model="is_leadership" />
                <span style="color: black">Członek Kierownictwa</span>
              </label>
              <br />
              <label v-if="this.$store.state.is_management">
                <input type="checkbox" class="filled-in" id="management" v-model="is_management" />
                <span style="color: black">Członek Zarządu</span>
              </label>
            </div>

            <div class="card-action" style="text-align:center">
              <button
                class="btn btn-primary"
                type="button"
                name="action"
                @click.prevent="update()"
              >Zapisz</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: "",
      surname: "",
      email: "",
      password: "",
      confirmPassword: "",
      birthdate: "",
      error: false,
      errors: {},
      success: false,
      isProgress: true,
      division: "",
      divisions: [],
      currentUser: null,
      is_leadership: this.$store.state.is_leadership,
      is_management: this.$store.state.is_management,     
      wantMessages: 1,
    };
  },
  methods: {
    update() {
      if (
        this.password === this.confirmPassword &&
        (this.password === "" || this.password.length > 5)
      ) {
        this.axios
          .post("api/auth/updateUser", {
            token: this.$store.state.token,
            id: this.currentUser.id,
            name: this.name,
            surname: this.surname,
            email: this.email,
            password: this.password,
            confirmPassword: this.confirmPassword,
            birthdate: this.birthdate,
            division: this.division,
            is_leadership: this.is_leadership,
            is_management: this.is_management,
            wantMessages: this.wantMessages,
          })
          .then((response) => {
            this.isProgress = true;
            if (response.data.success == true) {
              setTimeout(() => {
                this.isProgress = false;
                this.$store.commit("LoginEmail", this.email);
                this.$router.push({ name: "dashboard" });
                this.$toaster.success("Dane pomyślnie zapisane");
              }, 2000);
            }
          })
          .catch((error) => {
            this.isProgress = false;
            this.error = true;
            this.errors = error.response.data.errors;
          });
      } else {
        this.error = true;
        this.errors.confirmPassword = [
          "Wpisz 2 razy to samo hasło, minimum 6 znaków",
        ];
      }
    },
    getDivisions: function () {
      this.axios.get("/api/getDivisions").then(
        function (response) {
          this.divisions = response.data;
        }.bind(this)
      );
    },
    getUser: function () {
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
            this.currentUser = response.data[0];
            this.name = myThis.currentUser.name;
            this.surname = myThis.currentUser.surname;
            this.email = myThis.currentUser.email;
            this.birthdate = myThis.currentUser.birthdate;
            this.division = myThis.currentUser.division;
            this.is_leadership = myThis.currentUser.is_leadership;
            this.is_management = myThis.currentUser.is_management;
            this.wantMessages = myThis.currentUser.want_messages;
            this.isProgress = false;
          }.bind(this)
        );
    },
  },
  created: function () {
    this.getDivisions();
    this.getUser();
  },
};
</script>

<style scoped>
.form-group {
  align-content: left;
}
.submit:hover {
  color: white;
}
.btn {
  display: inline-flex;
}
.btn:focus {
  color: white;
}

.heading {
  padding: 30px;
  border: none;
}
.login-form {
  background: white;
  padding: 30px;
}
.progress {
  margin: 0px;
  background-color: transparent;
}
input:focus {
  border-bottom: 1px solid royalblue !important;
  box-shadow: 0 1px 0 0 royalblue !important;
}
label.active {
  color: royalblue !important;
}
.indeterminate {
  background-color: #febd09;
}
</style>