<template>
    <div>
        <nav>
            <div class="navbar navbar-expand-lg  navbar-light bg-light">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="left hide-on-med-and-down">
                        <li>
                            <router-link :to="{ name: 'home' }" class="nav-link" ><img alt="KSM logo" src="./components/assets/logo.png" width="40"></router-link>
                        </li>
                    </ul>
                    <ul class="navbar-nav mr-auto">
                        <li v-if="!this.$store.state.isLoggedIn" class="right">
                            <router-link :to="{ name: 'login' }" class="nav-link">Zaloguj się</router-link>
                        </li>

                        <li v-if="!this.$store.state.isLoggedIn" class="right">
                            <router-link :to="{ name: 'register' }" class="nav-link">Zarejestruj się</router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn" class="right">
                            <router-link :to="{ name: 'edit' }" class="nav-link">Edytuj swoje dane</router-link>
                        </li>
                        <li v-if="this.$store.state.isLoggedIn" class="right">
                            <a href="#"  @click="logout()" class="nav-link">Wyloguj się</a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </nav>  <br><br><br>
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
                    //
                });
            }
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

    .container{
        padding:10px;
        width:80%;
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


</style>