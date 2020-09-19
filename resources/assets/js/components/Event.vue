<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="card card-default">
        <div class="card-header">
          <h4>{{title}}</h4>
        </div>
        <div class="progress" v-if="isProgress">
          <div class="indeterminate light-blue darken-4"></div>
        </div>

        <div class="card-body">
          <a
            :href="`https://calendar.google.com/calendar/r/eventedit?trp=false&sf=true&text=${title}&location=${location}&details=${about}&dates=${start_date.replace(/-/g,'')}T${start_time.replace(/:/g,'')}/${end_date.replace(/-/g,'')}T${end_time.replace(/:/g,'')}`"
            target="_blank"
          >
            <button
              class="btn btn-primary"
              type="button"
              name="action"
              style="float: right"
            ><i class="material-icons left">event</i>Dodaj do kalendarza Google</button>
          </a>

          <br />
          <div>
            <h6>Opis</h6>
            <p style="white-space: pre-line">{{about}}</p>
          </div>
          <div>
            <h6>Data rozpoczęcia</h6>
            <p>{{formatDate(start_date)+" godz. "+start_time.substring(0,5)}}</p>
          </div>
          <div>
            <h6>Data zakończenia</h6>
            <p>{{formatDate(end_date)+" godz. "+end_time.substring(0,5)}}</p>
          </div>
          <div>
            <h6>Grupa docelowa</h6>
            <select class="browser-default" v-model="division" disabled>
              <option value="0" key="0">Wydarzenie diecezjalne</option>
              <option v-for="divi in divisions" :value="divi.id" :key="divi.id">
                <span>{{ 'Oddział '+divi.town+' parafia '+divi.parish }}</span>
              </option>
            </select>
          </div>

          <div>
            <h6>Kontakt</h6>
            <p v-if="email!==null"><i class="material-icons left">mail_outline</i>{{email}}</p>
            <p v-else><i class="material-icons left">mail_outline</i>ksmdl.zarzad@gmail.com</p>
          </div>

          <div>
            <h6>Lokalizacja</h6>
            <p><i class="material-icons left">location_on</i>{{location}}</p>
          </div>

          <div>
            <h6>Koszt</h6>
            <p v-if="price!==null">{{price}}</p>
            <p v-else style="color: grey">Nie określono</p>
          </div>

          <div v-if="timetable!==null">
            <h6>Plan wydarzenia:</h6>
            <p style="white-space: pre-line">{{timetable}}</p>
          </div>

          <div v-if="details!==null">
            <h6>Dodatkowe informacje</h6>
            <p style="white-space: pre-line">{{details}}</p>
          </div>
          <div>
            <h5>
              Liczba zadeklarowanych uczestników: {{participants_cnt}}<i class="material-icons">people</i>
              <button
                class="btn btn-light"
                type="button"
                name="action"
                @click="getParticipantsList"
                v-if="!this.showParticipants &&this.participants_cnt>0"
              >Pokaż</button>
            </h5>
            <table v-if="this.showParticipants">
              <th>Imię i nazwisko</th>
              <th>Miasto</th>
              <th>Potwierdzone</th>
              <tr id="person" v-for="person in participants" :key="person.id">
                <td>{{person.name+' '+person.surname}}</td>
                <td>{{person.town}}</td>
                <td>
                  <span v-if="person.is_sure===1">tak</span>
                  <span v-else>nie</span>
                </td>
              </tr>
            </table>
            <p
              v-if="!is_admin&&showParticipants"
              style="font-size: x-small"
            >* Niektórzy użytkownicy mogą nie być widoczni dla wszystkich</p>

            <p style="font-style:italic; font-size:small">
              Utworzono {{this.created_at}} przez {{author}}
              <span v-if="modified_at!==null">
                <br />
                ostatnia modyfikacja: {{modified_at}}
              </span>
            </p>
          </div>

          <div id="enrollments" class="card-action" style="text-align:center">
            <ul class="collapsible">
              <li :class="{ active: is_signing }">
                <div class="collapsible-header">
                  <span>
                    <span style="font-weight:500">Zapisz się!</span>
                    <span
                      v-if="is_coming===0"
                      style="color: darkblue; font-style:italic; float:right"
                    >&nbsp;(Zapisano) Potwierdź przybycie!</span>
                    <span
                      v-if="is_coming===1"
                      style="color: darkgreen; font-style:italic; "
                    >(Potwierdzono przybycie)</span>
                  </span>
                  <i class="material-icons">event_available</i>
                </div>
                <div class="collapsible-body">
                  <form autocomplete="off" @submit.prevent="signParticipant" v-if="!success">
                    <div class="form-group">
                      <label
                        class="black-text"
                      >Czy będziesz być może, czy na pewno i organizatorzy mogą liczyć Twoje zgłoszenie?</label>
                      <div class="switch">
                        <label class="black-text">
                          Być może
                          <input type="checkbox" v-model="is_sure" />
                          <span class="lever"></span>
                          Na pewno!
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label
                        class="black-text"
                      >Czy chcesz, aby inni KSMowicze mogli zobaczyć, że bierzesz udział? Jeżeli wybierzesz nie, Twoje nazwisko na liście będzie widoczne tylko dla organizatorów.</label>
                      <div class="switch">
                        <label class="black-text">
                          Nie
                          <input type="checkbox" v-model="is_visible" />
                          <span class="lever"></span>
                          Tak
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label
                        class="black-text"
                      >Czy chcesz dostawać powiadomienia email o ewentualnych zmianach w wydarzeniu?</label>
                      <div class="switch">
                        <label class="black-text">
                          Nie
                          <input type="checkbox" v-model="want_messages" />
                          <span class="lever"></span>
                          Tak
                        </label>
                      </div>
                    </div>

                    <br />
                    <div class="card-action" style="text-align:center">
                      <button
                        class="btn btn-light"
                        v-if="is_coming!==null"
                        type="button"
                        @click="deleteParticipant"
                      >Wypisz mnie</button>
                      <button class="btn btn-primary" type="submit" name="action">Zapisz mnie</button>
                    </div>
                  </form>
                </div>
              </li>
            </ul>
            <button
              class="btn btn-primary editbtn"
              type="button"
              name="action"
              @click="editEvent"
              v-if="is_admin"
            ><i class="material-icons left">edit</i>Edytuj wydarzenie</button>
          </div>
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
      showParticipants: false,
      errors: {},
      success: false,
      isProgress: false,
      is_coming: null,
      participants_cnt: 0,
      participants: [],
      email: "",
      author: "",
      author_id: null,
      created_at: "",
      modified_at: null,
      is_sure: 0,
      is_visible: 1,
      want_messages :1,
      is_signing: 0,
    };
  },
  computed: {
    is_admin() {
      return (
        (this.division === 0 && this.$store.state.is_management) ||
        (this.division === this.$store.state.division &&
          this.$store.state.is_leadership) ||
        this.author_id === this.$store.state.user_id
      );
    },
  },
  methods: {
     formatDate(date){
      return new Date(date.replace(' ', 'T')).toLocaleDateString("PL", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
    },
    getDivisions: function () {
      this.axios.get("/api/getDivisions").then(
        function (response) {
          this.divisions = response.data;
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
            this.email = response.data[0].email;
            this.price = response.data[0].price;
            this.timetable = response.data[0].timetable;
            this.details = response.data[0].details;

            this.participants_cnt = response.data[0].participants;
            this.author =
              response.data[0].name + " " + response.data[0].surname;
            this.author_id = response.data[0].author_id;
            this.created_at = response.data[0].created_at;
            this.modified_at = response.data[0].modified_at;
            this.isProgress = false;
          }.bind(this)
        );
    },
    editEvent: function () {
      this.$router.push({ name: "editevent", params: { id: this.id } });
    },
    deleteParticipant: function () {
      this.isProgress = true;
      this.axios
        .post("/api/auth/deleteParticipant", {
          token: this.$store.state.token,
          event_id: this.id,
          user_id: this.$store.state.user_id,
        })
        .then(
          function (response) {
            if (response.data.success == true) {
              setTimeout(() => {
                this.isProgress = false;
                this.$router.push({ name: "events" });
                this.$toaster.success("Zostałeś wypisany");
              }, 2000);
            }
          }.bind(this)
        );
    },
    signParticipant: function () {
      if (this.is_coming === null) {
        this.axios
          .post("/api/auth/newParticipant", {
            token: this.$store.state.token,
            event_id: this.id,
            user_id: this.$store.state.user_id,
            visible: this.is_visible,
            is_sure: this.is_sure,
            want_messages: this.want_messages,
          })
          .then((response) => {
            this.isProgress = true;
            if (response.data.success == true) {
              setTimeout(() => {
                this.isProgress = false;
                this.$router.push({ name: "events" });
                this.$toaster.success("Zostałeś zapisany");
              }, 2000);
            }
          });
      } else {
        this.axios
          .post("/api/auth/editParticipant", {
            token: this.$store.state.token,
            event_id: this.id,
            user_id: this.$store.state.user_id,
            visible: this.is_visible,
            is_sure: this.is_sure,
            want_messages: this.want_messages,
          })
          .then((response) => {
            this.isProgress = true;
            if (response.data.success == true) {
              setTimeout(() => {
                this.isProgress = false;
                this.$router.push({ name: "events" });
                this.$toaster.success("Zostałeś zapisany");
              }, 2000);
            }
          });
      }
    },
    getParticipantsList: function () {
      this.isProgress = true;
      this.axios
        .post("/api/auth/getParticipants", {
          token: this.$store.state.token,
          id: this.id,
          is_admin: this.is_admin,
        })
        .then(
          function (response) {
            this.participants = response.data;
            this.isProgress = false;
            this.showParticipants = true;
          }.bind(this)
        );
    },
    checkParticipant: function () {
      this.isProgress = true;
      this.axios
        .post("/api/auth/checkParticipant", {
          token: this.$store.state.token,
          event_id: this.id,
          user_id: this.$store.state.user_id,
        })
        .then(
          function (response) {
            if (response.data.length > 0) {
              this.is_coming = response.data[0].is_sure;
              this.is_sure = this.is_coming;
              this.is_visible = response.data[0].visible;
              this.want_messages = response.data[0].want_messages;
            }
            this.isProgress = false;
          }.bind(this)
        );
    },
  },
  created: function () {
    if (this.$route.params.is_signing === 1) this.is_signing = true;
    this.getDivisions();
    this.getEventInfo();
    this.checkParticipant();
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

div.card-header,
.collapsible-header {
  background-color: rgba(254, 203, 0, 0.712);
}

div.card {
  width: 32em;
}

table td,
table {
  text-align: center;
}
th {
  font-weight: 500;
}
select {
  color: black !important;
}

.card-action button {
  margin: 0.25em
}

.switch label input[type="checkbox"]:checked + .lever:after {
  background-color: #fdd835 !important;
}
.switch label input[type="checkbox"]:checked + .lever {
  background-color: #fdd835 !important;
}
</style>