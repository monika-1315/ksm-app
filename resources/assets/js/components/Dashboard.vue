<template>

    
    <div class="container">
      <h1>Witaj {{ name}}!</h1>
      <p v-if="!this.$store.state.is_authorized">Twoje konto nie zostało jeszcze zatwierdzone. Skontaktuj się z Kierownictwem oddziału</p>
      <h3>Najnowsze wiadomości:</h3>
      <br>
        <div class="card card-default">
            <div class="card-header">Tytuł wiadomości</div>
            <div class="card-body">
                Treść wiadomości
            </div>
        </div>
    </div>
</template>
<script>
  export default {
     
    data() {
      return {
        
      }
    },
    components: {
      //
    },
    computed:{
      name(){
        return this.$store.state.name;
      }
    },
    methods:{
    // getUser: function(){
    //           this.axios.post('/api/auth/getUser?token=' + this.$store.state.token+'&email='+this.$store.state.email)
    //           .then(function (response) {
    //              this.$store.commit('refreshUser', response.data[0])
    //           }.bind(this));
             
    //         },
    //     },
    created: function(){
      //  if (this.$store.state.division === 0){
      //     this.getUser()
       }
    }
  }
</script>

<style>
.container{
  text-align: left;
}
.card-body{
  text-align: left;
}

</style>