<template>
  <div class="container" v-if="this.$store.state.is_management || this.$store.state.is_leadership">
    <div class="row justify-content-md-center">
      <div class="card card-default">
        <div class="card-header">Dodaj użytkownika</div>
        <div class="card-body">
          <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
            <div class="form-group">
              <label for="name">Imię</label>
              <input id="name" type="text" class="validate" v-model="name" required />
              <span class="text text-danger" v-if="error && errors.name">{{ errors.name[0] }}</span>
            </div>
            <div class="form-group">
              <label for="surname">Nazwisko</label>
              <input id="surname" type="text" class="validate" v-model="surname" />
              <span class="text text-danger" v-if="error && errors.surname">{{ errors.name[0] }}</span>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="text" class="validate" v-model="email" />
              <span class="text text-danger" v-if="error && errors.email">{{ errors.email[0] }}</span>
            </div>
            
            <div class="form-group">
              <label for="birthdate">Data urodzenia</label>
              <input type="date" id="birthdate" v-model="birthdate" />
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
                :disabled="!this.$store.state.is_management"
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
              <label>
                <input type="checkbox" class="filled-in" id="leader" v-model="is_leadership" />
                <span style="color: black">Członek Kierownictwa</span><br>
                <span v-if=" this.$store.state.is_leadership || is_leadership">Członek zostanie automatycznie zautoryzowany.</span>
              </label>
            </div>

            <div class="card-action" style="text-align:center">
              <button
                class="btn btn-primary"
                type="button"
                name="action"
                @click.prevent="register()"
              >Zarejestruj członka</button>
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
      birthdate: "",
      is_leadership: 0,
      error: false,
      errors: {},
      success: false,
      isProgress: false,
      division: -1,
      divisions: [],
    };
  },
  methods: {
    register() {
      this.axios
        .post("api/auth/addUser", {
          token: this.$store.state.token,
          name: this.name,
          surname: this.surname,
          email: this.email,
          birthdate: this.birthdate,
          division: this.division,
          is_leadership: this.is_leadership,
          is_authorized: this.is_leadership || this.$store.state.is_leadership
        })
        .then((response) => {
          this.isProgress = true;
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$router.push({ name: "dashboard" });
              this.$toaster.success("Rejestracja użytkownika powiodła się!");
            }, 2000);
          }
         else {
            setTimeout(() => {
              this.isProgress = false;
              this.$router.push({ name: "dashboard" });
              this.$toaster.error("Coś poszło nie tak! Prawdopodobnie nie masz już uprawnień");
            }, 2000);
          }
        })
        .catch((error) => {
          this.isProgress = false;
          this.error = true;
          this.errors = error.response.data.errors;
        });
    },
    getDivisions: function () {
      this.axios.get("/api/getDivisions").then(
        function (response) {
          this.divisions = response.data;
        }.bind(this)
      );
    },
    getUser: function () {
      this.axios
        .post("/api/auth/getUser", {
          token: this.$store.state.token
        })
        .then(
          function (response) {
            this.division = response.data[0].division;
            this.$store.commit("refreshUser", response.data[0]);
          }.bind(this)
        );
    },
  },
  created: function () {
    this.getDivisions();
    if (this.$store.state.division != 0) {
      this.division = this.$store.state.division;
    } else {
      this.getUser();
    }
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
</style>