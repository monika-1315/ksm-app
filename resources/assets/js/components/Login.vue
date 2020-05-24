<template>
  
        <div id="app">
            <img alt="KSM logo" src="./assets/logo.png">
            <br><br>
            <div style="padding 20px" >
                <center><h4>Witamy w aplikacji Katolickiego Stowarzyszenia Młodzieży Diecezji Legnickiej</h4></center>
            </div>
            
            <div class="alert alert-danger" v-if="loginError && errors.message">
                <span>{{ errors.message[0] }}</span>
            </div>
            <div class="login-form">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="email" type="text" class="validate" v-model="email">
                        <label for="email">Email</label>
                        <span class="text text-danger" v-if="loginError && errors.email">{{ errors.email[0] }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="password" type="password" class="validate" v-model="password" v-on:keyup.enter="login">
                        <label for="password">Hasło</label>
                        <span class="text text-danger" v-if="loginError && errors.password">{{ errors.password[0] }}</span>
                    </div>
                </div>
                <div class="progress" v-if="isProgress">
                    <div class="indeterminate"></div>
                    <br><br>
                </div>
                <button id="log-in" class="btn btn-primary" type="button" name="action" @click="login()">Zaloguj się </button>
            </div>
        
   <br>
      <h6>Nie masz konta?<br></h6>
    <div >
      <router-link id="reg"  :to="{ name: 'register' }" class="nav-link" ><button class="btn btn-primary">Zarejestruj się</button></router-link>
    </div>
<h6><br>Czym jest Katolickie Stowarzysznie Młodzieży? Zobacz na naszej <a href="http://ksm.legnica.pl">stronie! -></a></h6>
  </div>
</template>

<script>
    // import GSignInButton from 'vue-google-signin-button'
    // Vue.use(GSignInButton)
    import store from '../store.js';
    export default {
        data() {
            return {
                email: '',
                password: '',
                loginError: false,
                errors: {},
                isProgress: false,
                googleSignInParams: {
                    client_id: '519675463204-7bc7lvaqti1teo41im6e5he5lbjjvthk.apps.googleusercontent.com'
                }
            }
        },
        methods: {
            login() {
                this.loginError = false;
                
                this.axios.post('api/auth/login', {
                    email: this.email,
                    password: this.password
                }).then(response => {
                    this.isProgress = true;
                    if(response.data.success == true)
                    {
                        setTimeout(() => {
                            this.isProgress = false;
                            store.commit('LoginUser', response.data);
                            store.commit('LoginEmail', this.email)
                            this.$router.push({name: 'dashboard'})
                        },2000);
                    }
                    else{

                        this.isProgress = true;
                        setTimeout(() => {
                            this.isProgress = false;
                            this.loginError = true;
                            this.errors = response.data.errors
                        },1000);
                    }
                }).catch(error => {
                    this.isProgress = false;
                    this.loginError = true;
                    this.errors = error.response.data.errors
                });
            },
        }
    }
</script>

<style scoped>

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
 input:focus {
  border-bottom: 1px solid royalblue !important;
  box-shadow: 0 1px 0 0 royalblue !important;
}
label.active {
  color: royalblue !important;
}
.labels{
  text-align:left;
  width: auto;
  display:inline-block;
}
.btn{
    background-color: #007bff;
}
    .form-wrapper {
        min-height: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }
    .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }
    .form-signin .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
    }
    .form-signin .form-control:focus {
        z-index: 2;
    }
    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }


    .login-form{
        display:inline-block;
        background: white;
        padding: 30px;
        width: 30em;
    }
    .submit:hover{
        color:white;
    }
   
    .btn:focus{
        color:white;
    }
    .progress{
        margin:0px;
        background-color: transparent;
    }
    .indeterminate{
        background-color: #FEBD09;
    }
    .alert{
        margin-bottom:0px;
    }
    .g-signin-btn{
        display: inline-block;
        padding: 4px 8px;
        border-radius: 3px;
        background-color: #3c82f7;
        color: #fff;
        box-shadow: 0 3px 0 #0f69ff;
    }
</style>