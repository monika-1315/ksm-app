<template>
  <div class="container">
    <h3>
      <div class="hide-on-med-and-down">
        <button
          class="btn btn-primary"
          type="button"
          name="action"
          @click="getEvents"
          style="float: right"
          id="refreshBtn"
        >Odśwież</button>
        <router-link :to="{ name: 'newevent' }">
          <button
            v-if="is_leadership||is_management"
            class="btn btn-primary yellow"
            type="button"
            name="action"
            style="float: right"
          >Utwórz</button>
        </router-link>
      </div>
      <div class="hide-on-large-only">
        <button
          class="btn btn-primary"
          type="button"
          name="action"
          @click="getEvents"
          style="float: right"
        >
          <i class="material-icons">refresh</i>
        </button>
        <router-link :to="{ name: 'newevent' }">
          <button
            v-if="is_leadership||is_management"
            class="btn btn-primary yellow"
            type="button"
            name="action"
            style="float: right"
          >
            <i class="material-icons">add</i>
          </button>
        </router-link>
      </div>Wydarzenia:
    </h3>
    <br />
    <span v-for="tab in tabs" :key="tab.id">
      <button
        class="tab btn btn-light"
        @click="selectedTab=tab.id"
        v-if="tab.id=='C'||user_id!=0"
      >
        <a :class="{activeTab: selectedTab==tab.id}">{{ tab.text }}</a>
      </button>
    </span>
    <p v-if="!is_authorized" style="font-weight: 500" class="red-text text-darken-2">
      <br />Twoje konto nie zostało jeszcze zatwierdzone. Skontaktuj się z Kierownictwem oddziału
    </p>

    <div v-for="event in events" :key="event.id">
      <div
        class="card card-default sticky-action"
        v-if="selectedTab=='C'|| (selectedTab=='A'||selectedTab=='B'||selectedTab=='D')&&user_id!=0"
      >
        <div class="card-header card-title activator">
          {{event.title}}
          <i class="material-icons right">expand_more</i>
        </div>

        <div class="card-content">
          <h5><i class="tiny material-icons left">event</i>{{formatDate(event.start)+" - "
            +formatDate(event.end)}}
          </h5>
          <h6><i class="tiny material-icons left">location_on</i>{{event.location}}</h6>
        </div>
        <div class="card-reveal">
          <div class="card-title activator">
            {{event.title}}
            <i class="material-icons right">expand_less</i>
          </div>
          <h5>
            <i class="tiny material-icons left">schedule</i>{{formatDate(event.start) +" "+event.start.split(" ")[1].substring(0,5)+" - "
            +formatDate(event.end)+" "+event.end.split(" ")[1].substring(0,5)}}
          </h5>
          <h6><i class="tiny material-icons left">location_on</i>{{event.location}}</h6>

          <p style="white-space: pre-line">
            <em>Opis:</em>
            {{event.about}}
            <br />
          </p>
        </div>
        <div class="card-action" v-if="is_authorized">
          <span v-if="event.is_sure==0" style="color: darkblue; font-style:italic">zapisano</span>
          <span v-if="event.is_sure==1" style="color: darkgreen; font-style:italic">potwierdzono</span>
          <span class="hide-on-small-only">
            
            <button
              v-if="event.is_sure==0"
              class="btn btn-light editbtn"
              type="button"
              name="action"
              @click="signEvent(event.id)"
            >Potwierdź</button>

            <button
              v-if="event.is_sure==null && user_id!=0"
              class="btn btn-light editbtn"
              type="button"
              name="action"
              @click="signEvent(event.id)"
            >Zapisz się</button>
            <button
              class="btn btn-light editbtn"
              type="button"
              name="action"
              @click="showEvent(event.id)"
              style="float: right"
              v-if="user_id!=0"
            >Szczegóły</button>
            <button
              class="btn btn-primary editbtn"
              type="button"
              name="action"
              @click="editEvent(event.id)"
              style="float: right"
              v-if="event.division==division&&is_leadership ||event.division==null && is_management || event.author==user_id"
            >Edytuj</button>
          </span>

          <span class="hide-on-med-and-up">
            <button
              v-if="event.is_sure==0 || event.is_sure==null && user_id!=0"
              class="btn btn-light editbtn"
              type="button"
              name="action"
              @click="signEvent(event.id)"
            ><i class="material-icons">event_available</i></button>
            <button
              class="btn btn-light editbtn"
              type="button"
              name="action"
              @click="showEvent(event.id)"
              style="float: right"
              v-if="user_id!=0"
            >  <i class="material-icons">subject</i></button>
            <button
              class="btn btn-primary editbtn"
              type="button"
              name="action"
              @click="editEvent(event.id)"
              style="float: right"
              v-if="event.division==division&&is_leadership ||event.division==null && is_management || event.author==user_id"
            >  <i class="material-icons">edit</i></button>
          </span>
        </div>
      </div>
    </div>
    <p v-if="events.length==0">
      <br />Brak wydarzeń spełniających kryteria
    </p>
    <div class="progress" v-show="isProgress">
      <div class="indeterminate"></div>
    </div>
  </div>
</template>
<script>

export default {
  data() {
    return {
      isProgress: true,
      events: [],
      tabs: [
        { id: "A", text: "Moje nadchodzące" },
        { id: "B", text: "Moje minione" },
        { id: "C", text: "W diecezji" },
        { id: "D", text: "W oddziale" },
      ],
      selectedTab: "C",
    };
  },
  computed: {
    user_id() {
      return this.$store.state.user_id;
    },
    is_management() {
      return this.$store.state.is_management;
    },
    is_leadership() {
      return this.$store.state.is_leadership;
    },
    division() {
      return this.$store.state.division;
    },
    is_authorized() {
      return this.$store.state.is_authorized;
    },
  },
  watch: {
    user_id: function () {
      this.getEvents();
    },
    selectedTab: function () {
      this.getEvents();
    },
  },
  methods: {
    formatDate(date) {
      return new Date(date.replace(" ", "T")).toLocaleDateString("PL", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
      });
    },
    getEvents: function () {
      this.isProgress = true;
      switch (this.selectedTab) {
        case "A":
          this.axios
            .post("/api/auth/getUserUpcomingEvents", {
              token: this.$store.state.token
            })
            .then(
              function (response) {
                this.events = response.data;
                this.isProgress = false;
              }.bind(this)
            );
          break;
        case "B":
          this.axios
            .post("/api/auth/getUserOldEvents", {
              token: this.$store.state.token
            })
            .then(
              function (response) {
                this.events = response.data;
                this.isProgress = false;
              }.bind(this)
            );
          break;
        case "C":
          this.axios
            .get("/api/getDivisionEvents?id=0&user_id=" + this.user_id)
            .then(
              function (response) {
                this.events = response.data;
                this.isProgress = false;
              }.bind(this)
            );
          break;
        case "D":
          this.axios
            .get(
              "/api/getDivisionEvents?id=" +
                this.division +
                "&user_id=" +
                this.user_id
            )
            .then(
              function (response) {
                this.events = response.data;
                this.isProgress = false;
              }.bind(this)
            );
          break;
      } //switch case
    },
    editEvent: function (id) {
      this.$router.push({ name: "editevent", params: { id: id } });
    },
    showEvent: function (id) {
      this.$router.push({ name: "showevent", params: { id: id } });
    },
    signEvent: function (id) {
      this.$router.push({
        name: "showevent",
        params: { id: id, is_signing: 1 },
      });
    },
  },

  created: function () {
    if (this.user_id != 0) {
      this.selectedTab = "A";
    }
    this.getEvents();
  },
};
</script>

<style scoped>
.container {
  text-align: left;
}
.card-body {
  text-align: left;
}
.stamp {
  text-align: right;
  font-style: italic;
}
.indeterminate {
  background-color: #00549e;
}
.activeTab {
  font-weight: bold;
}
.card-title {
  font-weight: 400;
}
em {
  font-weight: 500;
  font-style: normal;
}
a {
  color: black !important;
}
.yellow {
  background-color: #fecb00 !important;
  border-color: #fecb00;
  color: black;
}
div.card-header {
  background-color: rgba(254, 203, 0, 0.61);
}

.card-reveal {
  overflow: scroll;
}
</style>