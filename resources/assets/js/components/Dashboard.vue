<template>
  <div class="container">
    <p
      v-if="!this.$store.state.is_authorized"
    >Twoje konto nie zostało jeszcze zatwierdzone. Skontaktuj się z Kierownictwem oddziału</p>
    <div v-if="this.$store.state.is_authorized">
      <h3>
        Najnowsze ogłoszenia:
        <button
          class="btn btn-primary yellow"
          type="button"
          name="action"
          @click="newMessage"
          style="float: right"
          v-if="this.$store.state.is_leadership || this.$store.state.is_management"
        >Nowa wiadomość</button>
        <button
          class="btn btn-primary"
          type="button"
          name="action"
          @click="getMessages"
          style="float: right"
        >Odśwież</button>
      </h3>
      <br />
      <span v-for="tab in tabs" :key="tab.id">
        <button
          class="tab btn btn-light"
          @click="selectedTab=tab.id"
          v-if="tab.id!=='D'||is_leadership"
        >
          <a :class="{activeTab: selectedTab===tab.id}">{{ tab.text }}</a>
        </button>
      </span>

      <div v-for="message in messages.data" :key="message.id">
        <div
          class="card card-default"
          v-if="selectedTab==='A'&&(message.receiver_group!==2||is_leadership)|| selectedTab==='B'&&message.receiver_group===1||selectedTab==='C'&&message.receiver_group===0||selectedTab==='D'&&message.receiver_group===2"
        >
          <div class="card-header">
            <h5>{{message.title}}</h5>
          </div>
          <div class="card-body">
            <p style="white-space: pre-line">{{message.body}}</p>
            <!-- <textarea disabled :placeholder=message.body></textarea> -->
            <p class="stamp">
              Opublikowana: {{message.published_at}}
              <span v-if="message.modified===1">, edytowana</span>
            </p>
          </div>
        </div>
      </div>
      <br />
      <button
        class="btn btn-primary"
        type="button"
        name="action"
        @click="getPage(messages.prev_page_url)"
        style="float: left"
        v-if="messages.prev_page_url!==null"
      >Poprzednia strona</button>
      <button
        class="btn btn-primary"
        type="button"
        name="action"
        @click="getPage(messages.next_page_url)"
        style="float: right"
        v-if="messages.next_page_url!==null"
      >Następna strona</button>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      messages: [],
      tabs: [
        { id: "A", text: "Wszystkie" },
        { id: "B", text: "Oddział" },
        { id: "C", text: "Od Zarządu" },
        { id: "D", text: "Do Kierownictw" }
      ],
      selectedTab: "A"
    };
  },
  components: {
    //
  },
  computed: {
   
    division() {
      return this.$store.state.division;
    },
    is_leadership() {
      return this.$store.state.is_leadership;
    }
  },
  watch: {
    division: function() {
      this.getMessages();
    }
  },
  methods: {
    getMessages: function() {
      this.axios
        .post("/api/auth/getMessages", {
          is_leadership: this.is_leadership,
          token: this.$store.state.token,
          division: this.$store.state.division
        })
        .then(
          function(response) {
            this.messages = response.data;
          }.bind(this)
        );
    },
    newMessage: function() {
      this.$router.push({ name: "message" });
    },
    getPage: function(url) {
      this.axios
        .post(url, {
          is_leadership: this.is_leadership,
          token: this.$store.state.token,
          division: this.$store.state.division
        })
        .then(
          function(response) {
            this.messages = response.data;
          }.bind(this)
        );
    }
  },
  created: function() {
    if (this.$store.state.division !== -1) this.getMessages();
  }
};
</script>

<style>
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

.activeTab {
  font-weight: bold;
}

.yellow {
  background-color: rgb(254, 209, 9) !important;
  border-color: rgb(254, 209, 9);
  color: black;
}
</style>