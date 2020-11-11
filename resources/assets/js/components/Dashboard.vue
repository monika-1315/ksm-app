<template>
  <div class="container">
    <p
      v-if="!this.$store.state.is_authorized"
    >Twoje konto nie zostało jeszcze zatwierdzone. Skontaktuj się z Kierownictwem oddziału</p>
    <div v-if="this.$store.state.is_authorized">
      <h3>
        <div class="hide-on-med-and-down">
      <button
        class="btn btn-primary"
        type="button"
        name="action"
         @click="getMessages"
        style="float: right"
      >Odśwież</button>
      
        <button
           v-if="this.$store.state.is_leadership || this.$store.state.is_management"
          class="btn btn-primary yellow"
          type="button"
          name="action"
          style="float: right"
           @click="newMessage"
        >Utwórz</button></div>
        <div class="hide-on-large-only">
        <button
        id="getMessages"
        class="btn btn-primary"
        type="button"
        name="action"
         @click="getMessages"
        style="float: right"
      ><i class="material-icons">refresh</i></button>
      
        <button
          v-if="this.$store.state.is_leadership || this.$store.state.is_management"
          class="btn btn-primary yellow"
          type="button"
          name="action"
          style="float: right"
           @click="newMessage"
        ><i class="material-icons">add</i></button></div>
        
       Najnowsze ogłoszenia:<br>
      </h3>
      <br />
<div class="progress" v-show="isProgress">
      <div class="indeterminate"></div>
    </div>
      <span v-for="tab in tabs" :key="tab.id">
        <button
          class="tab btn btn-light"
          @click="tabSelected(tab.id)"
          v-if="tab.id!=='D'||is_leadership"
          :id="tab.text"
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
            <p class="stamp">
              {{message.name+' '+message.surname+', '+message.published_at}}
              <span
                v-if="message.modified===1"
              >, edytowana</span>
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
import store from "../store.js";
export default {
  data() {
    return {
      isProgress: true,
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
      this.isProgress = true;
      this.axios
        .post("/api/auth/getMessages", {
          token: this.$store.state.token,
          card: this.selectedTab
        })
        .then(
          function(response) {
             this.isProgress = true;
            this.messages = response.data;
            this.isProgress = false;
            if ((this.selectedTab === "A"))
              store.commit("SaveMessages", response.data);
          }.bind(this)
        );
    },
    tabSelected: function(cardId) {
      this.selectedTab = cardId;
      if (cardId === "A" && this.$store.state.messages !== null) {
        this.messages = this.$store.state.messages;
      } else {
        this.getMessages();
      }
    },
    newMessage: function() {
      this.$router.push({ name: "message" });
    },
    getPage: function(url) {
      this.axios
        .post(url, {
          is_leadership: this.is_leadership,
          token: this.$store.state.token,
          division: this.$store.state.division,
          card: this.selectedTab
        })
        .then(
          function(response) {
            this.messages = response.data;
          }.bind(this)
        );
    }
  },
  created: function() {
    if (this.$store.state.division !== 0) {
      if (this.$store.state.messages !== null) {
        this.messages = this.$store.state.messages;
        this.isProgress = false;
      } else this.getMessages();
    }
     
  }
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

.activeTab {
  font-weight: bold;
}

.yellow {
  background-color: rgb(254, 203, 0) !important;
  border-color: rgb(254, 203, 0);
  color: black;
}
a{
   color: black !important;
}
.indeterminate {
  background-color: #00549e;
}
div.card-header {
  background-color: rgba(254, 203, 0, 0.61);
}
</style>