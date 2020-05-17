<template>
  <div class="container" v-if="this.$store.state.is_management"> 
    <div class="row justify-content-md-center">
      <div class="card card-default">
        <div class="card-header">
          <h4>Edytuj informacje o oddziale</h4>
        </div>
        <div class="card-body">
          <div class="progress" v-if="isProgress">
      <div class="indeterminate"></div>
    </div>
          <form autocomplete="off" @submit.prevent="editDivision" v-if="!success" method="post">
            <div class="form-group">
              <label for="town">Miasto</label>
              <input id="town" type="text" class="validate" v-model="town" required />
              <span class="text text-danger" v-if="error && errors.town">{{ errors.town[0] }}</span>
            </div>
            <div class="form-group">
              <label for="parish">Parafia</label>
              <input id="parish" type="text" class="validate" v-model="parish" required />
              <span class="text text-danger" v-if="error && errors.parish">{{ errors.parish[0] }}</span>
            </div>
            <div class="switch">
              <label>
                Zawieszony
                <input type="checkbox" v-model="is_active" />
                <span class="lever"></span>
                Aktywny
              </label>
            </div>
            <br />
            <div style="text-align:center">
              <button class="btn btn-primary" type="submit" name="action">Zapisz</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      id: this.$route.params.id,
      town: "",
      parish: "",
      is_active: 0,
      error: false,
      errors: {},
      success: false,
      isProgress: true,
    };
  },
  watch: {
    receiver_group: function() {
      if (this.receiver_group !== 1) {
        this.division = -1;
      }
    }
  },
  methods: {
    editDivision() {
      this.axios
        .post("/api/auth/editDivision", {
          id: this.id,
          token: this.$store.state.token,
          town: this.town,
          parish: this.parish,
          is_active: this.is_active
        })
        .then(response => {
          this.isProgress = true;
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$router.push({ name: "divisions" });
              this.$toaster.success("Zmiany zostały zapisane");
            }, 2000);
          }
        })
        .catch(error => {
          this.isProgress = false;
          this.error = true;
          this.errors = error.response.data.errors;
          this.$toaster.error("Coś poszło nie tak");
        });
    },
    getDivision: function() {
      this.axios
        .get("/api/getDivisionById?id="+this.id)
        .then(
          function(response) {
            this.town = response.data[0].town,
            this.parish = response.data[0].parish,
            this.is_active = response.data[0].is_active;
            this.isProgress=false;
          }.bind(this)
        );
    }
  },
  created: function() {
    this.getDivision();
  }
};
</script>

<style scoped>
.form-group {
  align-content: left;
}
.submit:hover {
  color: white;
}
.btn {
  display: inline-flex;
}
.btn:focus {
  color: white;
}
.indeterminate {
  background-color:royalblue;
}
.heading {
  padding: 30px;
  border: none;
}
.login-form {
  background: white;
  padding: 30px;
}
.progress {
  margin: 0px;
  background-color: transparent;
}
input:focus {
  border-bottom: 1px solid royalblue !important;
  box-shadow: 0 1px 0 0 royalblue !important;
}
label.active {
  color: royalblue !important;
}
textarea {
  height: 16em;
}
</style>