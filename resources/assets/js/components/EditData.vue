<template>
    <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">Edytuj swoje dane osobowe</div>
          <div class="card-body">
           
            <form autocomplete="off" @submit.prevent="update" v-if="currentUser" method="post">
                <div class="form-group">
                        <label for="name">Imię</label>
                    <input id="name" type="text" class="validate" v-model="name" required>
                        <span class="text text-danger" v-if="error && errors.name">{{ errors.name[0] }}</span>
                </div>
                <div class="form-group" >
                        <label for="surname">Nazwisko</label>
                   <input id="surname" type="text" class="validate" v-model="surname" required>
                        <span class="text text-danger" v-if="error && errors.surname">{{ errors.name[0] }}</span>
                </div>
                <div class="form-group" >
                     <label for="email">Email</label>
                   <input id="email" type="text" class="validate" v-model="email" required>
                        <span class="text text-danger" v-if="error && errors.email">{{ errors.email[0] }}</span>
                </div>
                <div class="form-group">
                    <label for="password">Hasło</label>
                    <input id="password" type="password" class="validate" v-model="password" placeholder="Wpisz i potwierdź hasło, jeżeli chcesz je zmienić">
                    <span class="text text-danger" v-if="error && errors.password">{{ errors.password[0] }}</span>
                </div>
                <div class="form-group" >
                     <label for="confirm_password">Powtórz hasło</label>
                    <input id="confirm_password" type="password" class="validate" v-model="confirmPassword">
                    <span class="text text-danger" v-if="error && errors.confirmPassword">{{ errors.confirmPassword[0] }}</span>
                </div>
                <div class="form-group" >
                    <label for="birthdate">Data urodzenia</label>
                    <input type="date" id="birthdate" v-model="birthdate" required>
                     <span class="text text-danger" v-if="error && errors.birthdate">{{ errors.birthdate[0] }}</span>
                </div>
                <div class="form-group" >
                    <label for="division">Oddział</label><br>
                      <select class="browser-default" v-model="division" required>
                        <option v-for='divi in divisions' :value='divi.id' :key='divi.id'> 
                        <span>{{ '   '+divi.town+' '+divi.parish }}</span></option>
                    </select>
                </div>
                    <span class="text text-danger" v-if="error && errors.division">{{ errors.division[0] }}</span>
                
                <div style="text-align:center">
                <button class="btn btn-primary" type="button" name="action" @click.prevent="update()">Zapisz</button>
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
                division: '',
                divisions: [],
                currentUser: null
            };
        },
        methods: {
            update(){
                if(this.password===this.confirmPassword){
                this.axios.post('api/auth/updateUser?token=' + this.$store.state.token, {
                    id: this.currentUser[0].id,
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
                            this.$toaster.success('Dane pomyślnie zapisane')
                        }, 2000)
                    }
                }).catch(error => {
                    this.isProgress = false;
                    this.error = true;
                    this.errors = error.response.data.errors
                });
                }
                else{
                    this.error=true;
                    this.errors.confirmPassword=['Wpisz 2 razy to samo hasło'];
                }
            },
            getDivisions: function(){
              this.axios.get('/api/getDivisions')
              .then(function (response) {
                 this.divisions = response.data;
              }.bind(this));
         
            },
            getUser: function(){
                var myThis=this;
              this.axios.get('/api/auth/getUser?token=' + this.$store.state.token+'&email='+this.$store.state.email)
              .then(function (response) {
                 this.currentUser = response.data;
                 this.name = myThis.currentUser[0].name;                 
                 this.surname = myThis.currentUser[0].surname;
                 this.email = myThis.currentUser[0].email;
                 this.birthdate = myThis.currentUser[0].birthdate;
                 this.division = myThis.currentUser[0].division;
              }.bind(this)); 
              
            },
        },
        created: function(){
            this.getDivisions();
            this.getUser();
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