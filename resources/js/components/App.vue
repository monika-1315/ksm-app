 <template>
        <div>
                <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                <div class="container">
                    <ul class="navbar-nav">
                         <router-link :to="{ name: 'KSM' }" class="nav-link" ><img alt="KSM logo" src="./assets/logo.png" width="40"></router-link>
                        <router-link :to="{ name: 'welcome' }" class="nav-link">Home</router-link>
                        <router-link :to="{ name: 'page' }" class="nav-link" >Spa-Page</router-link>
                        <router-link :to="{ name: 'KSM' }" class="nav-link" >KSM</router-link>
                        <router-link :to="{ name: 'register' }" class="nav-link" >Zarejestruj się</router-link>
                    </ul>
                </div>
            </nav>
            <main>
                <router-view></router-view>
            </main>
        </div>
    </template>
    <script>
        export default {}
    </script>

