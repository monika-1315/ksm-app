<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="card card-default">
        <div class="card-header">
          <h4>{{title}}</h4>
        </div>
        <div class="progress" v-if="isProgress">
          <div class="indeterminate blue darken-4"></div>
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
            >Dodaj do kalendarza Google</button>
          </a>
          <br />
          <div>
            <h6>Opis</h6>
            <p style="white-space: pre-line">{{about}}</p>
          </div>
          <div>
            <h6>Data rozpoczęcia</h6>
            <p>{{new Date(start_date).toLocaleDateString("PL", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })+" godz. "+start_time.substring(0,5)}}</p>
          </div>
          <div>
            <h6>Data zakończenia</h6>
            <p>{{new Date(end_date).toLocaleDateString("PL", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })+" godz. "+end_time.substring(0,5)}}</p>
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
            <p v-if="email!==null">{{email}}</p>
            <p v-else>ksmdl.zarzad@gmail.com</p>
          </div>

          <div>
            <h6>Lokalizacja</h6>
            <p>{{location}}</p>
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
              Liczba zadeklarowanych uczestników: {{participants_cnt}}
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
                ,
                <br />
                ostatnia modyfikacja: {{modified_at}}
              </span>
            </p>
          </div>

          <div class="card-action" style="text-align:center">
            <button
              class="btn btn-primary editbtn"
              type="button"
              name="action"
              @click="editEvent"
              v-if="is_admin"
            >Edytuj</button>
            <ul class="collapsible expandable">
              <li>
                <div class="collapsible-header">
                  <h6>Zapisz się!</h6>
                </div>
                <div class="collapsible-body">
                  <form
                    autocomplete="off"
                    @submit.prevent="signParticipant"
                    v-if="!success"
                  >
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

                    <br />
                    <div class="card-action" style="text-align:center">
                      <button
                        class="btn btn-light"
                        v-if="is_coming!==null"
                        @click="deleteParticipant"
                      >Wypisz mnie</button>
                      <button class="btn btn-primary" type="submit" name="action">Zapisz mnie</button>
                    </div>
                  </form>
                </div>
              </li>
            </ul>
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
      created_at: "",
      modified_at: null,
      is_sure: 0,
      is_visible: 1,
    };
  },
  computed: {
    is_admin() {
      return (
        (this.division === 0 && this.$store.state.is_management) ||
        (this.division === this.$store.state.division &&
          this.$store.state.is_leadership)
      );
    },
  },
  methods: {
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
            this.is_coming = response.data[0].is_sure;
            if (this.is_coming !== null) this.is_sure = this.is_coming;
            this.participants_cnt = response.data[0].participants;
            this.author =
              response.data[0].name + " " + response.data[0].surname;
            this.created_at = response.data[0].created_at;
            this.modified_at = response.data[0].modified_at;
            this.isProgress = false;
          }.bind(this)
        );
    },
    editEvent: function () {
      this.$router.push({ name: "editevent", params: { id: this.id } });
    },
    deleteParticipant: function () {},
    signParticipant: function(){

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
  },
  created: function () {
    this.getDivisions();
    this.getEventInfo();
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
input:focus {
  border-bottom: 1px solid royalblue !important;
  box-shadow: 0 1px 0 0 royalblue !important;
}
label.active {
  color: royalblue !important;
}
div.card-header,
.collapsible-header {
  background-color: rgba(254, 209, 9, 0.712);
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

.switch label input[type="checkbox"]:checked + .lever:after {
  background-color: #fdd835 !important;
}
.switch label input[type="checkbox"]:checked + .lever {
  background-color: #fdd835 !important;
}
</style>