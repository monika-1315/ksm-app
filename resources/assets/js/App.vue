<template>
    <div>
        <nav class="navbar navbar-expand-lg  navbar-light bg-light">
            <div id="nav">
                    <ul class="left navbar-nav hide-on-med-and-down">
                        <li v-if="!this.$store.state.isLoggedIn">
                            <router-link :to="{ name: 'home' }" class="nav-link" ><img alt="KSM logo" src="./components/assets/logo.png" width="40"></router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn">
                            <router-link :to="{ name: 'dashboard' }" class="nav-link" ><img alt="KSM logo" src="./components/assets/logo.png" width="40"></router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn" >
                            <router-link :to="{ name: 'edit' }" class="nav-link">Edytuj swoje dane</router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn && (this.$store.state.is_leadership || this.$store.state.is_management)" class="right">
                            <router-link :to="{ name: 'adduser' }" class="nav-link">Dodaj członka</router-link>
                        </li>
                         <li v-if="this.$store.state.isLoggedIn && this.$store.state.is_leadership" class="right">
                            <router-link :to="{ name: 'authorize' }" class="nav-link">Zatwierdzaj</router-link>
                        </li>
                    </ul>
                    <ul class="right navbar-nav hide-on-med-and-down">
                        <li v-if="!this.$store.state.isLoggedIn">
                            <router-link :to="{ name: 'login' }" class="nav-link">Zaloguj się</router-link>
                        </li>

                        <li v-if="!this.$store.state.isLoggedIn" >
                            <router-link :to="{ name: 'register' }" class="nav-link">Zarejestruj się</router-link>
                        </li>
                        
                        <li id="log-out" v-if="this.$store.state.isLoggedIn" >
                            <a href="#"  @click="logout()" class="nav-link">Wyloguj się</a>
                        </li>
                        
                    </ul>
            </div>
        </nav>  <br>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
      
    </div>
</template>

<script> 

    export default {
        methods: {

            logout() {

                this.axios.get('api/auth/logout?token=' + this.$store.state.token).then(response => {
                    if(response.data.success == true)
                    {
                        // login user, store the token and redirect to dashboard
                        this.$store.commit('LogoutUser')
                        this.$router.push({name: 'login'})
                    }
                }).catch(error => {
                    this.$store.commit('LogoutUser')
                    this.$router.push({name: 'login'})
                });
            },
            checkToken(){
                if (this.$store.state.isLoggedIn){
                    var myThis=this;
                    this.axios.post('api/auth/getUser?token=' + this.$store.state.token+'&email='+this.$store.state.email)
                        .then(function (response) {
                            this.$store.commit('refreshUser', response.data[0])
                            }.bind(this))                
                        .catch(error=> {
                            if (error.response && error.response.status === 401) {
                                myThis.$store.commit('LogoutUser');
                                myThis.$router.push({name: 'login'})
                            }
                            return Promise.reject(error.response);
                        })
                }
            },
            getUser: function(){
              this.axios.post('/api/auth/getUser?token=' + this.$store.state.token+'&email='+this.$store.state.email)
              .then(function (response) {
                 this.$store.commit('refreshUser', response.data[0])
              }.bind(this)); 
           },
        },
        created: function(){
            this.checkToken();
            // if (this.$store.state.division === 0 && this.$store.state.isLoggedIn){
            //     this.getUser();
            // }
        }
    }

</script>

<style scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition-duration: 0.2s;
        transition-property: opacity;
        transition-timing-function: ease;
    }

    .fade-enter,
    .fade-leave-active {
        opacity: 0
    }

    #nav{
        width: 100%;
    }
    .container{
        padding-left: 9%;
        padding-right: 9%;
    }
    .navbar{
        text-align: center;
        padding-left: 10%;
        padding-right: 10%;
    }
    #nav-mobile a:hover{
        text-decoration: none !important;
        color:white;
    }
    #nav-mobile a:focus{
        text-decoration: none !important;
        color:white;
        background-color:rgba(0,0,0,0.1);
    }

    #nav-mobile2 a:hover{
        text-decoration: none !important;
        color:white;
    }
    #nav-mobile2 a:focus{
        text-decoration: none !important;
        color:white;
        background-color:rgba(0,0,0,0.1);
    }
#log-out{
  float: right;
}
</style>