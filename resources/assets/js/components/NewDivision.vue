<template>
  <div class="container" v-if="this.$store.state.is_management">
    <div class="row justify-content-md-center">
      <div class="card card-default">
        <div class="card-header">
          <h4>Dodaj nowy oddział</h4>
        </div>
        <div class="card-body">
          <form autocomplete="off" @submit.prevent="saveDivision" v-if="!success" method="post">
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
     
      error: false,
      errors: {},
      success: false,
      isProgress: false
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
    saveDivision() {
      this.axios
        .post("/api/auth/newDivision", {
          id: this.id,
          token: this.$store.state.token,
          town: this.town,
          parish: this.parish,
        })
        .then(response => {
          this.isProgress = true;
          if (response.data.success == true) {
            setTimeout(() => {
              this.isProgress = false;
              this.$router.push({ name: "divisions" });
              this.$toaster.success("Oddział został zapisany");
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
    
  },
 
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