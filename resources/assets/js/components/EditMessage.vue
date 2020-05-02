<template>
    <div class="container" v-if="this.$store.state.is_management || this.$store.state.is_leadership">
    <div class="row justify-content-md-center">
        <div class="card card-default">
          <div class="card-header"><h4>Edytuj wiadomość
              <button class="btn btn-primary" type="button" name="action" @click="deleteMessage" style="float: right">Usuń</button></h4>
          </div>
          <div class="card-body">
           
            <form autocomplete="off" @submit.prevent="editMessage" v-if="!success" method="post">

                <div class="form-group" >
                    <label for="receivers">Wiadomość do:</label><br>
                      <select class="browser-default" v-model="receiver_group" :disabled="!this.$store.state.is_management">
                        <option v-for='group in groups' :value='group.id' :key='group.id'> 
                        <span>{{ group.text }}</span></option>
                    </select>
                      <span class="text text-danger" v-if="error && errors.group">{{ errors.group[0] }}</span>
                </div>
                <div class="form-group" >
                    <label for="division">Oddział</label><br>
                      <select class="browser-default" v-model="division" :disabled="!this.$store.state.is_management || this.receiver_group!==1">
                        <option v-for='divi in divisions' :value='divi.id' :key='divi.id'> 
                        <span>{{ '   '+divi.town+' '+divi.parish }}</span></option>
                    </select>
                      <span class="text text-danger" v-if="error && errors.division">{{ errors.division[0] }}</span>
                </div>
                <div class="form-group" >
                     <label for="title">Tytuł</label>
                   <input id="title" type="text" class="validate" v-model="title" required>
                        <span class="text text-danger" v-if="error && errors.title">{{ errors.title[0] }}</span>
                </div>
                <div class="form-group">
                    <label for="body">Treść</label>
                    <textarea id="body" cols="100" rows="100" v-model="body" required/>
                    <span class="text text-danger" v-if="error && errors.password">{{ errors.password[0] }}</span>
                </div>
                
                
                <div style="text-align:center">
                <button class="btn btn-primary" type="submit" name="action">Zapisz wiadomość</button>
                </div>
            </form>
          </div>
      </div>
    </div>
 
        
        
    </div>
</template>

<script>
    export default {
        data(){
            return {
                id: this.$route.params.id,
                title: '',
                body: '',
                email: '',
                receiver_group: 1,
                error: false,
                errors: {},
                success: false,
                isProgress: false,
                division: this.$store.state.division,
                divisions: [],
                groups : [{id: 0, text: 'Wszystkich'}, {id: 1, text:'Oddziału'}, {id: 2, text:'Kierownictw'}]
            };
        },
       watch:{
            receiver_group: function(){
                if(this.receiver_group!==1){
                    this.division=-1;
                }
            }
        },
        methods: {
            editMessage(){
                this.axios.post('/api/auth/editMessage', {
                    id: this.id,
                    token: this.$store.state.token,
                    title: this. title,
                    body: this.body,
                    email: this.email,
                    receiver_group: this.receiver_group,
                    division: this.division,
                }).then(response => {
                    this.isProgress = true;
                    if(response.data.success == true)
                    {
                        setTimeout(() => {
                            this.isProgress = false;
                            this.$router.push({ name: 'editmessages'})
                            this.$toaster.success('Wiadomość zapisana')
                        }, 2000)
                    }
                }).catch(error => {
                    this.isProgress = false;
                    this.error = true;
                    this.errors = error.response.data.errors                    
                    this.$toaster.error('Coś poszło nie tak')
                });
            },
            getDivisions: function(){
              this.axios.get('/api/getDivisions')
              .then(function (response) {
                 this.divisions = response.data;
              }.bind(this));
         
            },
            getMessage: function(){
            this.axios.post('/api/auth/getMessageById', {
                  token: this.$store.state.token, 
                  id: this.id
                  })
                  .then(function (response) {
                    this.title= response.data[0].title,
                    this.body= response.data[0].body,
                    this.email=  response.data[0].email,
                    this.receiver_group= response.data[0].receiver_group,
                    this.division = response.data[0].division;
              }.bind(this)); 
              
            },
            deleteMessage(){
                this.axios.post('/api/auth/deleteMessage', {
                    id: this.id,
                    token: this.$store.state.token,
                }).then(response => {
                    this.isProgress = true;
                    if(response.data.success == true)
                    {
                        setTimeout(() => {
                            this.isProgress = false;
                            this.$router.push({ name: 'editmessages'})
                            this.$toaster.success('Wiadomość usunięta')
                        }, 2000)
                    }
                }).catch(error => {
                    this.isProgress = false;
                    this.error = true;
                    this.errors = error.response.data.errors                    
                    this.$toaster.error('Coś poszło nie tak')
                });
            },
            
        },
        created: function(){
            this.getMessage();
            this.getDivisions();
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
textarea{
    height: 16em;
}
</style>