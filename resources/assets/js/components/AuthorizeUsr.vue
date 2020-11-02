<template>
  <div class="container" style="text-align:center" v-if=" this.$store.state.is_leadership">
    <h2>Na autoryzację oczekuje: {{users.length}} członków</h2>
    <button class="btn btn-primary" @click="getUsers">Odśwież</button>
    <br />
    <div class="progress" v-if="isProgress">
      <div class="indeterminate"></div>
    </div>
    <div v-for="user in users" :key="user.id">
      <p>
      <h4>{{user.name+' '+user.surname}}</h4>
      <button class="btn btn-light red-text text-darken-4" type="button" @click="discard(user.id)">Odrzuć i usuń</button>
      <button class="btn btn-primary" type="button" @click="authorize(user.id)">Zatwierdź konto</button>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      isProgress: true
    };
  },
  methods: {
    authorize(id_) {
      this.axios
        .post(
          "/api/auth/authorizeUser",{
            token: this.$store.state.token,
            id: id_}
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
    discard(id_) {
      this.axios
        .post(
          "/api/auth/discardUser", {
            token:   this.$store.state.token,
            id: id_}
        )
        .then(response => {
          this.isProgress = true;
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$toaster.success("Usunięto użytkownika");
              this.getUsers();
            }, 2000);
          }
        })
        .catch(error => {
          this.isProgress = false;
          this.$toaster.error("Coś poszło nie tak!");
        });
    },
    getUsers: function(){
      this.isProgress = true;
        this.axios.post('/api/auth/getUnauthorizedUsers', {token: this.$store.state.token})
            .then(function (response) {
                this.isProgress = false;
                this.users = response.data;
            }.bind(this)) .catch(error => {
          this.isProgress = false;
          this.$toaster.error("Coś poszło nie tak!");
        });
            },
    
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