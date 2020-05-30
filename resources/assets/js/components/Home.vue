<template>
  <div id="app">
      <br>
      <div >
      <center>
        <h4>Witamy w aplikacji <br>Katolickiego Stowarzyszenia Młodzieży <br>Diecezji Legnickiej</h4>
      </center> <br />
    <img alt="KSM logo" src="./assets/logo.png" />
    <br />
   
    
    </div>
    <br />
    <h6>
      Zaloguj się, aby uzyskać dostęp do tablicy ogłoszeń.
      <br />
    </h6>
     <div>
      <router-link id="reg" :to="{ name: 'login' }" class="nav-link">
        <button class="btn btn-primary">Zaloguj się</button>
      </router-link>
    </div>
    <h6>
      Nie masz konta?
      <br />
    </h6>
    <div>
      <router-link id="reg" :to="{ name: 'register' }" class="nav-link">
        <button class="btn btn-primary">Zarejestruj się</button>
      </router-link>
    </div>
    <h5>
      <br />Czym jest Katolickie Stowarzysznie Młodzieży? Zobacz na naszej
      <a href="http://ksm.legnica.pl">stronie! -></a>
    </h5>
  </div>
</template>

<style scoped>
#app {
  text-align: center;
}
.center{
    padding: 10%;
}
</style>