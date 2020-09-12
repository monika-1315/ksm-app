<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="card card-default">
        <div class="card-header">
          <h4>Dodaj nowe wydarzenie</h4>
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
                <option
                  v-for="divi in divisions"
                  :value="divi.id"
                  :key="divi.id"
                  :disabled="!is_management && divi.id!==user_division"
                >
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

            <div class="progress" v-if="isProgress">
              <div class="indeterminate amber lighten-1"></div>
            </div>
            <div class="card-action" style="text-align:center">
              <!-- <button
                class="btn btn-primary"
                type="button"
                name="action"
                @click.prevent="register()"
              >Dodaj wydarzenie</button>-->
              <!-- Modal Trigger -->
              <button
                data-target="modal1"
                class="btn btn-primary modal-trigger waves-effect"
                @click.prevent="register"
                :disabled="title===null|| end_date===null||end_time===null||start_date===null||start_time===null "
              >Dodaj wydarzenie</button>

              <!-- v-show="colliders.length>0"  -->
              <div id="modal1" class="modal fade" role="dialog" tabindex="-1">
                <div class="modal-content">
                    <h4 class="modal-title">Wydarzenia odbywające się w tym terminie:</h4>
                    
                 
                  <div class="modal-body">
                    <div class="progress" v-if="isProgress">
                      <div class="indeterminate amber lighten-1"></div>
                    </div>
                    <h6
                      v-if="!isProgress && colliders.length===0"
                      class="green-text"
                    >Brak kolidujących wydarzeń! :)</h6>
                    <table v-if="!isProgress && colliders.length>0">
                      <th>Wydarzenie</th>
                      <th>Miasto</th>
                      <th>Początek</th>
                      <th>Koniec</th>
                      <tr v-for="event in colliders" :key="event.id">
                        <td>{{event.title}}</td>
                        <td>
                          <span v-if="event.town===null">diecezja</span>
                          <span v-else>{{event.town}}</span>
                        </td>
                        <td>{{event.start}}</td>
                        <td>{{event.end}}</td>
                      </tr>
                    </table>
                    <br />
                    <h5>Czy na pewno chcesz utworzyć wydarzenie {{title}}?</h5>
                  </div>
                </div>
                <div class="modal-footer">
                  <button href="#!" class="modal-close btn btn-light">Wróć</button> &nbsp;
                  <button
                    class="btn btn-primary modal-close"
                    @click.prevent="addEvent"
                    href="#!"
                  >Dodaj</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import M from "materialize-css";
export default {
  data() {
    return {
      title: null,
      about: null,
      start_date: null,
      end_date: null,
      start_time: null,
      end_time: null,
      division: 0,
      location: null,
      price: "",
      timetable: " ",
      details: "",
      divisions: [],
      error: false,
      errors: {},
      success: false,
      isProgress: false,
      author: null,
      colliders: [],
    };
  },
  computed: {
    user_division() {
      return this.$store.state.division;
    },
    is_management() {
      return this.$store.state.is_management;
    },
  },
  methods: {
    register() {
      this.isProgress = true;
      this.axios
        .post("api/auth/getCollidingEvents", {
          token: this.$store.state.token,
          start: this.start_date + " " + this.start_time,
          end: this.end_date + " " + this.end_time,
        })
        .then((response) => {
          this.colliders = response.data;
          this.isProgress = false;
        })
        .catch((error) => {
          this.isProgress = false;
          this.$toaster.error("Edytuj datę");
          this.error = true;
          this.errors = error.response.data.errors;
        });
    },
    addEvent() {
      this.isProgress = true;
      this.axios
        .post("api/auth/newEvent", {
          token: this.$store.state.token,
          title: this.title,
          about: this.about,
          start: this.start_date + " " + this.start_time,
          end: this.end_date + " " + this.end_time,
          division: this.division,
          location: this.location,
          price: this.price,
          timetable: this.timetable,
          details: this.details,
          author: this.author,
        })
        .then((response) => {
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$router.push({ name: "events" });
              this.$toaster.success("Dodano wydarzenie");
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
          this.division = this.$store.state.division;
        }.bind(this)
      );
    },
  },
  created: function () {
    this.getDivisions();
  },
  mounted: function () {
    M.AutoInit();
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
.modal{
  max-height: 85% !important ;
  overflow-y: scroll !important;
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
  background-color: rgba(254, 203, 0, 0.76);
}
table {
  text-align: center !important;
}
.modal-footer {
  /* text-align: center !important; */
  justify-content: center !important;
}
</style>