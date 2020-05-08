<template>
  <div class="container" style="text-align:center" v-if=" this.$store.state.is_leadership">
    <h2>Na autoryzację oczekuje: {{users.length}} członków</h2>
    <br />
    <div class="progress" v-if="isProgress">
      <div class="indeterminate"></div>
    </div>
    <div v-for="user in users" :key="user.id">
      <h4>{{user.name+' '+user.surname}}</h4>
      <button class="btn btn-primary" type="button" @click="authorize(user.id)">Zatwierdź konto</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      usersDiv: [],
      usersAll: [],
      isProgress: false
    };
  },
  computed: {
    is_management() {
      return this.$store.state.is_management;
    },
    is_leadership() {
      return this.$store.state.is_leadership;
    }
  },
  watch:{
      is_management: function(){
          this.getUsers();
      }
  },
  methods: {
    authorize(id) {
      this.axios
        .post(
          "/api/auth/authorizeUser?token=" +
            this.$store.state.token +
            "&id=" +
            id
        )
        .then(response => {
          this.isProgress = true;
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$toaster.success("Zatwierdzono członka oddziału");
              this.getUsers();
            }, 2000);
          }
        })
        .catch(error => {
          this.isProgress = false;
          this.$toaster.error("Coś poszło nie tak!");
        });
    },
    getUsers: function() {
      this.axios
        .post(
          "/api/auth/getUnauthorizedUsers?token=" +
            this.$store.state.token +
            "&division=" +
            this.$store.state.division
        )
        .then(
          function(response) {
            this.users = response.data;
          }.bind(this)
        );
    }
  },
  created: function() {
    this.getUsers();
  }
};
</script>
        
<style scoped>
.progress {
  margin: 0px;
  background-color: transparent;
}
.indeterminate {
  background-color: royalblue;
}
</style>