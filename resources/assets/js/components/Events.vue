<template>
  <div class="container">
    <h3>
      Wydarzenia:
      <button
        class="btn btn-primary"
        type="button"
        name="action"
        @click="getEvents"
        style="float: right"
      >Odśwież</button>
    </h3>
    <br />
    <span v-for="tab in tabs" :key="tab.id">
      <button
        class="tab btn btn-light"
        @click="selectedTab=tab.id"
        v-if="tab.id!=='C'||user_id!==0"
      >
        <a :class="{activeTab: selectedTab===tab.id}">{{ tab.text }}</a>
      </button>
    </span>

    <div v-for="event in events" :key="event.id">
      <div
        class="card card-default"
        v-if="selectedTab==='C'|| (selectedTab==='A'||selectedTab==='B'||selectedTab==='D')&&user_id!==0"
      >
        <div class="card-header">
          <h4>
            {{event.title}}
            <button
              class="btn editbtn"
              type="button"
              name="action"
              @click="showEvent(event.id)"
              style="float: right"
            >Szczegóły</button>
            <button
              class="btn btn-primary editbtn"
              type="button"
              name="action"
              @click="editEvent(event.id)"
              style="float: right"
            >Edytuj</button>
          </h4>
        </div>
        <div class="card-body">
          <h5>{{event.start+" - "+event.end}}</h5>
          <h6>{{event.location}}</h6>
          <p style="white-space: pre-line">{{event.about}}</p>
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
      selectedTab: "A",
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
              id:  this.user_id,
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
             .post("/api/auth/getDivisionEvents", {
              token: this.$store.state.token,
              id: 0,
            })
            .then(
              function (response) {
                this.events = response.data;
                this.isProgress = false;
              }.bind(this)
            );
            break;
        case "D":
          this.axios
            .post("/api/auth/getDivisionEvents", {
              token: this.$store.state.token,
              id: this.division,
            })
            .then(
              function (response) {
                this.events = response.data;
                this.isProgress = false;
              }.bind(this)
            );
            break;
      }//switch case
    },
    editEvent: function (id) {
      this.$router.push({ name: "editevent", params: { id: id } });
    },
    showEvent: function (id) {
      this.$router.push({ name: "showevent", params: { id: id } });
    },
  },

  created: function () {
    if (this.user_id !== 0) this.getEvents();
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
  background-color: rgba(3, 35, 138, 0.774);
}
.activeTab {
  font-weight: bold;
}
</style>