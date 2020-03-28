<template>
  <div id="app">
    <img alt="KSM logo" src="./assets/logo.png">
    <br>
    <h4 class= "p-2">
      {{welcome}}
    </h4>
    <br>
    <div class="labels">
      Email:
          <input v-model="email" class="form-control" />
    </div><br><br>
    <div class="labels">
     Hasło:
          <input v-model="haslo" class="form-control" />
    </div>
        <br><br>
    <div >
          <button class="btn btn-primary" v-on:click="logIn">Zaloguj się</button>
    </div>
      <br>
      <h6>Nie masz konta?<br></h6>
    <div >
      <button class="btn btn-primary" v-on:click="logIn">Zarejestruj się</button>
    </div>
<h6><br>Czym jest Katolickie Stowarzysznie Młodzieży? Zobacz na naszej <a href="http://ksm.legnica.pl">stronie! -></a></h6>
  </div>
</template>

<script>
	export default {
		name: 'app',
			data() {
				return {
          welcome: "Witamy w aplikacji Katolickiego Stowarzyszenia Młodzieży Diecezji Legnickiej",
          email: "",
          haslo:"",
          
				}
			},
methods: {
	logIn() {
    this.email="";
    this.haslo="";
	}
}
	}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  align-content:center;
  color: #2c3e50;
  margin-top: 20px;
  padding-left: 5%;
  padding-right: 5%;
}
.labels{
  text-align:left;
  width: auto;
  display:inline-block;
}
</style>
