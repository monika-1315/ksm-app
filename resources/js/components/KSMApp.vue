<template>
  <div id="app">
    <img alt="KSM logo" src="./assets/logo.png">
    <br>
    <h4 class= "p-2">
      {{welcome}}
    </h4>
    <br>
    <form autocomplete="off" @submit.prevent="logIn" method="post">
            
    <div class="labels">
      Email:
          <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required/>
    </div><br><br>
    <div class="labels">
     Hasło:
          <input type="password" id="password" class="form-control" v-model="password" required/>
    </div>
        <br><br>
    <div >
          <!-- <router-link :to="{ name: 'dashboard' }" class="nav-link" > -->
            <button class="btn btn-primary" v-on:click="logIn" type="submit">Zaloguj się</button>
            <!-- </router-link> -->
    </div>
    </form>
      <br>
      <h6>Nie masz konta?<br></h6>
    <div >
      <router-link :to="{ name: 'register' }" class="nav-link" ><button class="btn btn-primary">Zarejestruj się</button></router-link>
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
          password:"",
          
				}
			},
methods: {
	logIn() {
    // this.email="";
    // this.password="";
    
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
