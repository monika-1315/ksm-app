<template>
  <div class="container" style="text-align:center" v-if=" this.$store.state.is_leadership">
    <h2>Zmień uprawnienia Kierownictwa</h2>
    <br />
    <div class="progress" v-if="isProgress">
      <div class="indeterminate"></div>
    </div>
    <h3>Obecne Kierownictwo:</h3>
    <div v-for="user in usersDiv" :key="user.id">
      <div v-if="user.is_leadership===1">
        <h4>{{user.name+' '+user.surname}}
        <button class="btn btn-primary" type="button" @click="chLeader(user.id)">Odbierz uprawnienia</button></h4>
      </div>
    </div>
    <br>
    <h3>Pozostali członkowie:</h3>
    <div v-for="user in usersDiv" :key="user.id">
      <div v-if="user.is_leadership===0">
        <h4>{{user.name+' '+user.surname}}
        <button class="btn btn-primary" type="button" @click="chLeader(user.id)">Nadaj uprawnienia</button></h4>
      </div>
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
  watch: {
    is_management: function() {
      this.getUsers();
    }
  },
  methods: {
    chLeader(id) {
      this.axios
        .post("/api/auth/changeLeadership", {
          token: this.$store.state.token,
          id: id
        })
        .then(response => {
          this.isProgress = true;
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$toaster.success("Zmieniono uprawnienia");
              this.getUsers();
            }, 2000);
          }
        })
        .catch(error => {
          this.isProgress = false;
          this.$toaster.error("Coś poszło nie tak!");
        });
    },
    chMan(id) {
      this.axios
        .post("/api/auth/changeManagement", {
          token: this.$store.state.token,
          id: id
        })
        .then(response => {
          this.isProgress = true;
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$toaster.success("Zmieniono uprawnienia");
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
      if (this.is_management) {
        this.axios
          .post("/api/auth/getAuthorizedUsers", {
            token: this.$store.state.token
          })
          .then(
            function(response) {
              this.usersAll = response.data;
            }.bind(this)
          );
      }
      if (this.is_leadership) {
        this.axios
          .post("/api/auth/getAuthorizedUsersDiv", {
            token: this.$store.state.token,
            division: this.$store.state.division
          })
          .then(
            function(response) {
              this.usersDiv = response.data;
            }.bind(this)
          );
      }
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