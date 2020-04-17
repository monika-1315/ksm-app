<template>

    
    <div class="container">
      <h1>Witaj <span v-for="user in currentUser" :key="user.id">{{ user.name}}</span>!</h1>
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
        currentUser: null,
      }
    },
    components: {
      //
    },
    methods:{
    getUser: function(){
              this.axios.get('/api/auth/getUser?token=' + this.$store.state.token+'&email='+this.$store.state.email)
              .then(function (response) {
                 this.currentUser = response.data;
              }.bind(this));
             
            },
        },
    created: function(){
          this.getUser()
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