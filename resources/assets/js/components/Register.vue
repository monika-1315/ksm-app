<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="card card-default">
        <div class="card-header">
          <h4>Zarejestruj się</h4>Cieszymy się, że chcesz działać razem z nami!
        </div>
        <div class="card-body">
          <form autocomplete="off" @submit.prevent="register"  method="post">
            <div class="form-group">
              <label for="name">Imię</label>
              <input id="name" type="text" class="validate" v-model="name" pattern="([A-Za-z- ])+" required />
              <span class="text text-danger" v-if="error && errors.name">{{ errors.name[0] }}</span>
            </div>
            <div class="form-group">
              <label for="surname">Nazwisko</label>
              <input id="surname" type="text" class="validate" v-model="surname" pattern="([A-Za-z- ])+"  />
              <span class="text text-danger" v-if="error && errors.surname">{{ errors.surname[0] }}</span>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" class="validate" v-model="email" :disabled="success" required />
              <span class="text text-danger" v-if="error && errors.email">{{ errors.email[0] }}</span>
            </div>
            <div class="form-group">
              <label for="password">Hasło</label>
              <input id="password" type="password" class="validate" v-model="password" />
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
              <input type="date" id="birthdate" v-model="birthdate" />
              <span
                class="text text-danger"
                v-if="error && errors.birthdate"
              >{{ errors.birthdate[0] }}</span>
            </div>
            <div class="form-group">
              <label for="division">Oddział</label>
              <br />
              <select class="browser-default" v-model="division">
                <option selected value="null" disabled>Wybierz</option>
                <option v-for="divi in divisions" :value="divi.id" :key="divi.id">
                  <span>{{ ' '+divi.town+' '+divi.parish }}</span>
                </option>
              </select>
            </div>
            <span class="text text-danger" v-if="error && errors.division">{{ errors.division[0] }}</span>
            <div class="form-group">
              <label for="wantMessages">Powiadomienia</label>
              <br />
              <label>
                <input type="checkbox" class="filled-in" id="wantMessages" v-model="wantMessages" />
                <span
                  style="color: black"
                >Chcę otrzymywać wiadomości email o nowych komunikatach i wydarzeniach dla mnie.</span>
              </label>
            </div>

            <div class="form-group" style="text-align:center">
              <label class="black-text" for="email_code">Wpisz kod potwierdzający Twój adres email</label>
              <br />
              <input
                id="email_code"
                type="number"
                min="0"
                max="9999"
                class="validate"
                v-model="email_code"
                style="width:10em"
               
              />
              <button
                v-if="code!= undefined"
                class="btn btn-light btn-tiny"
                type="button"
                name="action"
                @click.prevent="sendCode()"
              >Wyślij ponownie</button>
              <span class="text text-danger" v-if="error && errors.email_code">{{ errors.email[0] }}</span>
            </div>
            <label style="text-align: justify">Rejestrując się wyrażasz zgodę na przetwarzanie Twoich danych osobowych przez Katolickie Stowarzyszenie Młodzieży Diecezji Legnickiej na potrzeby działania aplikacji.<br>Aplikacja wykorzystuje pliki cookie. </label>
           
            <div class="card-action" style="text-align:center">
               <button
                v-if="code== undefined"
                class="btn btn-light btn-tiny"
                type="button"
                name="action"
                @click.prevent="sendCode()"
              >Wyślij kod</button>
              <button
                class="btn btn-primary"
                type="button"
                name="action"
                :disabled="!success"
                @click.prevent="register()"
              >Zarejestruj się</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <h6 align="center">
      <br />Czym jest Katolickie Stowarzysznie Młodzieży? Zobacz na naszej
      <a
        href="http://ksm.legnica.pl"
      >stronie! -></a>
    </h6>
    <br />
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: "",
      surname: "",
      email: "",
      email_code: "",
      code: undefined,
      password: "",
      confirmPassword: "",
      birthdate: "",
      error: false,
      errors: {},
      success: false,
      isProgress: false,
      division: null,
      divisions: [],
      wantMessages: 1,
    };
  },
  methods: {
    register() {
        if(this.email_code==this.code){
      this.axios
        .post("api/auth/register", {
          name: this.name,
          surname: this.surname,
          email: this.email,
          password: this.password,
          confirmPassword: this.confirmPassword,
          birthdate: this.birthdate,
          division: this.division,
          wantMessages: this.wantMessages,
          is_leadership: 0,
        })
        .then((response) => {
          this.isProgress = true;
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$router.push({ name: "login" });
              this.$toaster.success("Rejestracja powiodła się!");
            }, 2000);
          }
        })
        .catch((error) => {
          this.isProgress = false;
          this.error = true;
          this.errors = error.response.data.errors;
          this.$toaster.error("Rejestracja nie powiodła się! Uzupełnij poprawnie formularz");
        });
        } else
         this.$toaster.error("Błędny kod aktywacyjny");
    },
    getDivisions: function () {
      this.axios.get("/api/getDivisions").then(
        function (response) {
          this.divisions = response.data;
        }.bind(this)
      );
    },
    sendCode: function () {
      if (this.code==undefined)
        this.code = Math.round(Math.random() * 10000);
      this.axios
        .post("/api/mail", {
          recipient: this.email,
          name: this.name,
          code: this.code +9999,
        })
        .then((response) => {
          if (response.data.success == true) {
            this.success=true;
            this.$toaster.success("Wysłano email");
          } else {
            this.$toaster.error("Nie udało się wysłać wiadomości email. Sprawdź, czy Twój adres email jest poprawny lub skontaktuj się z administratorem.");
          }
        });
    },
  },
  created: function () {
    this.getDivisions();
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
  border-bottom: 1px solid #00549e !important;
  box-shadow: 0 1px 0 0 #00549e !important;
}
label.active {
  color: #00549e !important;
}
div.card-header {
  background-color: rgba(254, 203, 0, 0.712);
}
</style>