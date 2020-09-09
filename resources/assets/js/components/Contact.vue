<template>
  <div id="app" class="container">
    <img alt="KSM logo" src="./assets/logo.png" width="250" />
    <br />
    <br />
    <div style="padding 20px">
      
      <center>
        <h4>Skontaktuj się z nami!</h4>
      </center>
    </div>
    <p align="justify" id="info">
      Jeżeli masz jakieś pytania, sprawy - napisz do nas na
      <a
        href="mailto:ksmdl.zarzad@gmail.com"
      >ksmdl.zarzad@gmail.com</a>, chętnie pomożemy!
      Jeżeli masz problemy lub uwagi co do działania aplikacji, napisz na
      <a
        href="mailto:moniusiar@gmail.com"
      >moniusiar@gmail.com</a>. Zachęcamy
      także do kontaktu bezpośrednio z
      <b>członkami zarządu</b>:
    </p>
    <div class="progress" v-if="isProgress">
        <div class="indeterminate"></div>
    </div>
    <table>
      <tr id="person" v-for="person in management" :key="person.user_id">
        <td>{{person.function_name}}</td>
        <td>{{person.name+' '+person.surname}}</td>
        <td>
          <a :href="`mailto:${person.function_mail}`">{{person.function_mail}}</a>
        </td>
      </tr>
    </table>
  </div>
</template>


<script>
export default {
  data() {
    return {
      isProgress:true,
      management: []
    };
  },
  methods: {
    getManagement: function() {
      this.axios.get("/api/getManagement").then(
        function(response) {
          this.management = response.data;
          this.isProgress=false;
        }.bind(this)
      );
    }
  },
  created: function() {
    this.isProgress=true;
    this.getManagement();
  }
};
</script>



<style scoped>
#app {
  text-align: center;
}

.indeterminate {
  background-color: rgb(254, 209, 9);
}
</style>