<template>
  <div class="container">
    <h3>
      Twoje wiadomości:
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
        v-if="tab.id!=='D'&&tab.id!=='C'||is_management"
      >
        <a :class="{activeTab: selectedTab===tab.id}">{{ tab.text }}</a>
      </button>
    </span>

    <div v-for="message in messages" :key="message.id">
      <div
        class="card card-default"
        v-if="selectedTab==='A'|| selectedTab==='B'&&message.receiver_group===1||selectedTab==='C'&&message.receiver_group===0||selectedTab==='D'&&message.receiver_group===2"
      >
        <div class="card-header">
          <h4>
            {{message.title}}
            <button
              class="btn btn-primary editbtn"
              type="button"
              name="action"
              @click="editMessage(message.id)"
              style="float: right"
            >Edytuj</button>
          </h4>
        </div>
        <div class="card-body">
          <p>{{message.body}}</p>
          <p class="stamp">{{message.published_at}}</p>
        </div>
      </div>
    </div>
    <p v-if="messages.length==0">
      <br />Nie publikowałeś żadnych wiadomości
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
    user_id() {
      return this.$store.state.user_id;
    },
    is_management() {
      return this.$store.state.is_management;
    },
    is_leadership() {
      return this.$store.state.is_leadership;
    }
  },
  watch: {
    user_id: function() {
      this.getMessages();
    }
  },
  methods: {
    getMessages: function() {
      this.isProgress = true;
      this.axios
        .post("/api/auth/getMessageByAuthor", {
          token: this.$store.state.token,
          id: this.$store.state.user_id
        })
        .then(
          function(response) {
            this.messages = response.data;
            this.isProgress = false;
          }.bind(this)
        );
    },
    editMessage: function(id) {
      this.$router.push({ name: "editmessage", params: { id: id } });
    }
  },

  created: function() {
    if (this.user_id !== 0) this.getMessages();
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
.indeterminate {
  background-color:#00549e
}
a{
   color: black !important;
}
.activeTab {
  font-weight: bold;
}
</style>