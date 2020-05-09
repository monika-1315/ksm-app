<template>
  <div id="app" class="container">
    <img alt="KSM logo" src="./assets/logo.png" />
    <br />
    <br />
    <div style="padding 20px">
      <center>
        <h4>Skontaktuj się z nami!</h4>
      </center>
    </div>
    <p align="justify">
      Jeżeli masz jakieś pytania, sprawy - napisz do nas na
      <a href="mailto:ksmdl.zarzad@gmail.com">ksmdl.zarzad@gmail.com</a>, chętnie pomożemy!
      Jeżeli masz problemy lub uwagi co do działania aplikacji, napisz na
      <a href="mailto:moniusiar@gmail.com">moniusiar@gmail.com</a>. Zachęcamy
      także do kontaktu bezpośrednio z <b>członkami zarządu</b>:

      <table>
          <tr v-for="person in management" :key="person.user_id">
              <td>{{person.function_name}}</td>
              <td>{{person.name+' '+person.surname}}</td>
              <td><a :href="`mailto:${person.function_mail}`">{{person.function_mail}}</a></td>
          </tr>
      </table>
    </p>
  </div>
</template>


<script>
    export default {
        data(){
            return {
                management: []
            };
        },
        methods: {
            getManagement: function(){
              this.axios.get('/api/getManagement')
              .then(function (response) {
                 this.management = response.data;
              }.bind(this));
         
            },
        },
        created: function(){
            this.getManagement()
        }
    }
</script>



<style scoped>
#app {
  text-align: center;
}
</style>