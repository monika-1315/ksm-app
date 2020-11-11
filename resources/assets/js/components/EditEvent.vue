<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="card card-default">
        <div class="card-header">
          <h4>
            Edytuj wydarzenie
            <button
              class="btn btn-primary"
              type="button"
              name="action"
              @click="deleteEvent"
              style="float: right"
            >Usuń</button>
          </h4>
        </div>
        <div class="progress" v-show="isProgress">
          <div class="indeterminate light-blue darken-4"></div>
        </div>
        <div class="card-body">
          <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
            <div class="form-group">
              <label for="title">Tytuł wydarzenia</label>
              <input id="title" type="text" class="validate" v-model="title" required autofocus />
              <span class="text text-danger" v-if="error && errors.title">{{ errors.title[0] }}</span>
            </div>
            <div class="form-group">
              <label for="about">Krótki opis</label>
              <input id="about" type="text" class="validate" v-model="about" required />
              <span class="text text-danger" v-if="error && errors.about">{{ errors.about[0] }}</span>
            </div>
            <div class="form-group">
              <label for="start_date">Data rozpoczęcia</label>
              <input type="date" id="start_date" v-model="start_date" required />
              <span class="text text-danger" v-if="error && errors.start">{{ errors.start[0] }}</span>
            </div>
            <div class="form-group">
              <label for="start_time">Godzina rozpoczęcia</label>
              <input type="time" id="start_time" v-model="start_time" required />
            </div>
            <div class="form-group">
              <label for="end_date">Data zakończenia</label>
              <input type="date" id="end_date" v-model="end_date" required />
              <span class="text text-danger" v-if="error && errors.end">{{ errors.end[0] }}</span>
            </div>
            <div class="form-group">
              <label for="end_time">Godzina zakończenia</label>
              <input type="time" id="end_time" v-model="end_time" required />
            </div>
            <div class="form-group">
              <label for="division">Grupa docelowa:</label>
              <br />
              <select class="browser-default" v-model="division">
                <option value="0" key="0">Wydarzenie diecezjalne</option>
                <option v-for="divi in divisions" :value="divi.id" :key="divi.id">
                  <span>{{ 'Oddział '+divi.town+' parafia '+divi.parish }}</span>
                </option>
              </select>
              <span
                class="text text-danger"
                v-if="error && errors.division"
              >{{ errors.division[0] }}</span>
            </div>

            <div class="form-group">
              <label for="location">Lokalizacja:</label>
              <input id="location" type="text" class="validate" v-model="location" required />
              <span
                class="text text-danger"
                v-if="error && errors.location"
              >{{ errors.location[0] }}</span>
            </div>

            <div class="form-group">
              <label for="price">Koszt:</label>
              <input id="price" type="text" class="validate" v-model="price" />
              <span class="text text-danger" v-if="error && errors.price">{{ errors.price[0] }}</span>
            </div>

            <div class="form-group">
              <label for="timetable" class="form-group">Plan wydarzenia:</label>
              <textarea id="timetable" cols="50" rows="10" v-model="timetable" />
              <span
                class="text text-danger"
                v-if="error && errors.timetable"
              >{{ errors.timetable[0] }}</span>
            </div>

            <div class="form-group">
              <label for="details">Dodatkowe informacje:</label>
              <textarea id="details" cols="50" rows="5" v-model="details" />
              <span class="text text-danger" v-if="error && errors.details">{{ errors.details[0] }}</span>
            </div>

            <div class="card-action" style="text-align:center">
              <button
                class="btn btn-primary"
                type="button"
                name="action"
                @click.prevent="register()"
              >Zapisz wydarzenie</button>
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
      id: this.$route.params.id,
      title: "",
      about: "",
      start_date: "",
      end_date: "",
      start_time: "",
      end_time: "",
      division: 0,
      location: "",
      price: "",
      timetable: " ",
      details: "",
      divisions: [],
      error: false,
      errors: {},
      success: false,
      isProgress: false,
      author: null,
    };
  },
  methods: {
    register() {
      var email = confirm("Czy chcesz wysłać uczestnikom informacje o modyfikacji?")
      this.axios
        .post("/api/auth/editEvent", {
          token: this.$store.state.token,
          id: this.id,
          title: this.title,
          about: this.about,
          start: this.start_date + " " + this.start_time,
          end: this.end_date + " " + this.end_time,
          division: this.division,
          location: this.location,
          price: this.price,
          timetable: this.timetable,
          details: this.details,
          emails: email,
          // author: this.author,
        })
        .then((response) => {
          this.isProgress = true;
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$router.push({ name: "events" });
              this.$toaster.success("Zapisano wydarzenie");
            }, 2000);
          }
          else {
            setTimeout(() => {
              this.isProgress = false;
              this.$router.push({ name: "events" });
              this.$toaster.error("Coś poszło nie tak! Sprawdź uprawnienia.");
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
          this.author = this.$store.state.user_id;
        }.bind(this)
      );
    },
    getEventInfo: function () {
      this.isProgress = true;
      this.axios
        .post("/api/auth/getEventInfo", {
          token: this.$store.state.token,
          id: this.id,
        })
        .then(
          function (response) {
            this.title = response.data[0].title;
            this.division = response.data[0].division;
            this.about = response.data[0].about;
            this.start_date = response.data[0].start.split(" ")[0];
            this.start_time = response.data[0].start.split(" ")[1];
            this.end_date = response.data[0].end.split(" ")[0];
            this.end_time = response.data[0].end.split(" ")[1];
            this.division = response.data[0].division;
            if (this.division === null) this.division = 0;
            this.location = response.data[0].location;
            this.price = response.data[0].price;
            this.timetable = response.data[0].timetable;
            this.details = response.data[0].details;
            this.isProgress = false;
          }.bind(this)
        );
    },

    deleteEvent() {
      this.axios
        .post("/api/auth/deleteEvent", {
          id: this.id,
          token: this.$store.state.token,
        })
        .then((response) => {
          this.isProgress = true;
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$router.push({ name: "events" });
              this.$toaster.success("Wydarzenie usunięte");
            }, 2000);
          }

        })
        .catch((error) => {
          this.isProgress = false;
          this.error = true;
          this.$toaster.error("Coś poszło nie tak! Sprawdź uprawnienia.");
        });
    },
  },
  created: function () {
    this.getEventInfo();
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
  border-bottom: 1px solid royalblue !important;
  box-shadow: 0 1px 0 0 royalblue !important;
}
label.active {
  color: royalblue !important;
}
div.card-header {
  background-color: rgba(254, 203, 0, 0.712);
}
</style>