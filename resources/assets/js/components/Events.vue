<template>
  <div class="container">
    <h3>
     
      <button
        class="btn btn-primary"
        type="button"
        name="action"
        @click="getEvents"
        style="float: right"
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
       Wydarzenia:
    </h3>
    <br />
    <span v-for="tab in tabs" :key="tab.id">
      <button
        class="tab btn btn-light"
        @click="selectedTab=tab.id"
        v-if="tab.id==='C'||user_id!==0"
      >
        <a :class="{activeTab: selectedTab===tab.id}">{{ tab.text }}</a>
      </button>
    </span>
    <p
      v-if="!is_authorized" style="font-weight: 500" class="red-text text-darken-2"
    ><br>Twoje konto nie zostało jeszcze zatwierdzone. Skontaktuj się z Kierownictwem oddziału</p>

    <div v-for="event in events" :key="event.id">
      <div
        class="card card-default sticky-action"
        v-if="selectedTab==='C'|| (selectedTab==='A'||selectedTab==='B'||selectedTab==='D')&&user_id!==0"
      >
        <div class="card-header card-title activator">
          {{event.title}}
          <i style="float: right; font-size:small">więcej</i>
        </div>

        <div class="card-content">
          <h5>{{new Date(event.start).toLocaleDateString("PL", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })+" - "
            +new Date(event.end).toLocaleDateString("PL", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}}</h5>
          <h6>{{event.location}}</h6>
        </div>
        <div class="card-reveal">
          <div class="card-title activator">
            {{event.title}}
            <i style="float: right; font-size:small">mniej</i>
          </div>
          <h5>
            {{new Date(event.start).toLocaleDateString("PL", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
            +" "+event.start.split(" ")[1].substring(0,5)+" - "
            +new Date(event.end).toLocaleDateString("PL", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })+" "
            +event.end.split(" ")[1].substring(0,5)}}
          </h5>
          <h6>{{event.location}}</h6>

          <p style="white-space: pre-line">
            <em>Opis:</em>
            {{event.about}}
          </p>
        </div>
        <div class="card-action"  v-if="is_authorized">
          <span v-if="event.is_sure===0" style="color: darkblue; font-style:italic">zapisano</span>
          <button v-if="event.is_sure===0" class="btn btn-light editbtn" type="button" name="action"  @click="signEvent(event.id)">Potwierdź</button>

          <span v-if="event.is_sure===1" style="color: darkgreen; font-style:italic">potwierdzono</span>
          <button
            v-if="event.is_sure===null && user_id!=0"
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
            v-if="event.division===division&&is_leadership ||event.division===null && is_management || event.author===user_id"
          >Edytuj</button>
        </div>
      </div>
    </div>
    <p v-if="events.length==0">
      <br />Brak wydarzeń spełniających kryteria
    </p>
    <div class="progress" v-if="isProgress">
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
        { id: "A", text: "Moje zbliżające się" },
        { id: "B", text: "Moje minione" },
        { id: "C", text: "Zbliżające się w diecezji" },
        { id: "D", text: "Zbliżające się w oddziale" },
      ],
      selectedTab: "C",
    };
  },
  components: {
    //
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
    is_authorized(){
      return this.$store.state.is_authorized;
    }
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
    getEvents: function () {
      this.isProgress = true;
      switch (this.selectedTab) {
        case "A":
          this.axios
            .post("/api/auth/getUserUpcomingEvents", {
              token: this.$store.state.token,
              id: this.user_id,
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
              token: this.$store.state.token,
              id: this.user_id,
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
      this.$router.push({ name: "showevent", params: { id: id, is_signing:1 } });
    },
  },

  created: function () {
    if (this.user_id !== 0) {
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
a{
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