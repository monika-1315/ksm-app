<template>

    
    <div class="container">
      <router-link :to="{ name: 'KSM' }" class="nav-link" id='log-out' ><button class="btn btn-primary">Wyloguj się</button></router-link>
      <h1>Witaj!</h1>
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
        //
      }
    },
    components: {
      //
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
#log-out{
  float: right;
}
</style>