<template>
  <div class="container" style="text-align:center" v-if=" this.$store.state.is_leadership">
    <h2>Zmień uprawnienia Kierownictwa</h2>
    <br />
    <div class="progress" v-if="isProgress">
      <div class="indeterminate"></div>
    </div>
    <h3>Obecne Kierownictwo:</h3>
    <div align="left" v-for="user in usersDiv1" :key="user.id">
      <button v-if="user.id!==user_id"
        class="btn btn-primary btn-small floating"
        type="button"
        @click="chLeader(user.id)"
      >Odbierz uprawnienia</button>
      <button v-else
        class="btn btn-primary btn-small floating"
        type="button"
        @click="chLeader(user.id)"
      >Zrezygnuj</button>
      <h5>{{user.name+' '+user.surname}}</h5>

      <hr />
    </div>
    <br />
    <h3>Pozostali członkowie:</h3>
    <div align="left" v-for="user in usersDiv0" :key="user.id">
      <button
        class="btn btn-primary btn-small floating"
        type="button"
        @click="chLeader(user.id)"
      >Nadaj uprawnienia</button>
      <h5>{{user.name+' '+user.surname}}</h5>
      <hr />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      usersDiv0: [],
      usersDiv1: [],
      usersAll0: [],
      usersAll1: [],
      isProgress: false
    };
  },
  computed: {
    is_management() {
      return this.$store.state.is_management;
    },
    user_id() {
      return this.$store.state.user_id;
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
              for (var user of response.data) {
                if (user.is_management === 0) this.usersAll0.push(user);
                else this.usersAll1.push(user);
              }
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
              for (var user of response.data) {
                if (user.is_management === 0) this.usersDiv0.push(user);
                else this.usersDiv1.push(user);
              }
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
.floating {
  float: right;
}
.btn{
    width:15em;
}
</style>