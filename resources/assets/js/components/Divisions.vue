<template>
<div class="container" v-if="this.$store.state.is_management" >
    <br>
     <button class="btn btn-primary floating yellow" type="button"  @click="newDiv">Dodaj nowy</button>
    <h2>Zarządzaj oddziałami</h2>
    <div class="progress" v-show="isProgress">
      <div class="indeterminate"></div>
    </div>
   <br>
    
   <hr>
    <div v-for="division in divisions" :key="division.id" >
        <p>
            <button class="btn btn-primary floating edit" type="button"  @click="edit(division.id)">Edytuj</button>
            <h4>{{division.town}} <span class="inactive" v-if="division.is_active==0"> NIEAKTYWNY</span></h4>
            
            {{'parafia '+division.parish}}       
            <hr>
        </p>
    </div>
</div>
</template>

<script>
export default {
  data() {
    return {
      isProgress: true,
      divisions: [],
    };
  },
  methods: {
    edit: function (id) {
      this.$router.push({ name: "division", params: { id: id } });
    },
    newDiv: function () {
      this.$router.push({ name: "newdivision" });
    },
    getDivisions: function () {
      this.axios
        .post("/api/auth/allDivisions", { token: this.$store.state.token })
        .then(
          function (response) {
            this.divisions = response.data;
            this.isProgress = false;
          }.bind(this)
        );
    },
  },
  created: function () {
    this.getDivisions();
  },
};
</script>
        
<style scoped>
.floating {
  float: right;
}
.inactive {
  font-size: normal;
  color: darkgrey;
}
.yellow {
  background-color: rgb(254, 203, 0) !important;
  border-color: rgb(254, 203, 0);
  color: black;
}
.indeterminate {
  background-color: rgb(254, 203, 0);
}
</style>