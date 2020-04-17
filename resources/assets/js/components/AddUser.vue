<template>
    <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">Dodaj użytkownika</div>
          <div class="card-body">
           
            <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
                <div class="form-group">
                        <label for="name">Imię</label>
                    <input id="name" type="text" class="validate" v-model="name" required>
                        <span class="text text-danger" v-if="error && errors.name">{{ errors.name[0] }}</span>
                </div>
                <div class="form-group" >
                        <label for="surname">Nazwisko</label>
                   <input id="surname" type="text" class="validate" v-model="surname">
                        <span class="text text-danger" v-if="error && errors.surname">{{ errors.name[0] }}</span>
                </div>
                <div class="form-group" >
                     <label for="email">Email</label>
                   <input id="email" type="text" class="validate" v-model="email">
                        <span class="text text-danger" v-if="error && errors.email">{{ errors.email[0] }}</span>
                </div>
                <div class="form-group">
                    <label for="password">Hasło</label>
                    <input id="password" type="password" class="validate" v-model="password">
                    <span class="text text-danger" v-if="error && errors.password">{{ errors.password[0] }}</span>
                </div>
                <div class="form-group" >
                     <label for="confirm_password">Powtórz hasło</label>
                    <input id="confirm_password" type="password" class="validate" v-model="confirmPassword">
                    <span class="text text-danger" v-if="error && errors.confirmPassword">{{ errors.confirmPassword[0] }}</span>
                </div>
                <div class="form-group" >
                    <label for="birthdate">Data urodzenia</label>
                    <input type="date" id="birthdate" v-model="birthdate" >
                     <span class="text text-danger" v-if="error && errors.birthdate">{{ errors.birthdate[0] }}</span>
                </div>
                <div class="form-group" >
                    <label for="division">Oddział</label><br>
                      <select class="browser-default" v-model="division" disabled>
                        <option v-for='divi in divisions' :value='divi.id' :key='divi.id'> 
                        <span>{{ '   '+divi.town+' '+divi.parish }}</span></option>
                    </select>
                </div>
                    <span class="text text-danger" v-if="error && errors.division">{{ errors.division[0] }}</span>
                
                <div style="text-align:center">
                <button class="btn btn-primary" type="button" name="action" @click.prevent="register()">Zarejestruj członka</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
 
        
        
    </div>
</template>

<script>
    export default {
        data(){
            return {
                name: '',
                surname: '',
                email: '',
                password: '',
                confirmPassword:'',
                birthdate:'',
                error: false,
                errors: {},
                success: false,
                isProgress: false,
                division: -1,
                divisions: []
            };
        },
        methods: {
            register(){
                this.axios.post('api/auth/register', {
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    password: this.password,
                    confirmPassword: this.confirmPassword,
                    birthdate: this.birthdate,
                    division: this.division
                }).then(response => {
                    this.isProgress = true;
                    if(response.data.success == true)
                    {
                        setTimeout(() => {
                            this.isProgress = false;
                            this.$router.push({ name: 'dashboard'})
                            this.$toaster.success('Rejestracja użytkownika powiodła się!')
                        }, 2000)
                    }
                }).catch(error => {
                    this.isProgress = false;
                    this.error = true;
                    this.errors = error.response.data.errors
                });
            },
            getDivisions: function(){
              this.axios.get('/api/getDivisions')
              .then(function (response) {
                 this.divisions = response.data;
              }.bind(this));
         
            },
            getUser: function(){
              this.axios.post('/api/auth/getUser?token=' + this.$store.state.token+'&email='+this.$store.state.email)
              .then(function (response) {
                 this.division = response.data[0].division;
                 this.$store.commit('refreshUser', response.data[0])
              }.bind(this)); 
              
            },
        },
        created: function(){
            this.getDivisions();
            if (this.$store.state.division != 0){
                this.division=this.$store.state.division;
            }
            else{
                this.getUser();
            }
        }
    }
</script>

<style scoped>
.form-group{
    align-content: left;
}
    .submit:hover{
        color:white;
    }
    .btn{
        display: inline-flex;
    }
    .btn:focus{
        color:white;
    }

    .heading{
        padding: 30px;
        border: none;
    }
    .login-form{
        background: white;
        padding: 30px;
    }
    .progress{
        margin:0px;
        background-color: transparent;
    }
 input:focus{
  border-bottom: 1px solid royalblue !important;
  box-shadow: 0 1px 0 0 royalblue !important;
}
label.active {
  color: royalblue !important;
}
</style>