<template>
    <div class="container">
    <div class="row justify-content-md-center">
        <div class="card card-default">
          <div class="card-header">Nowa wiadomość</div>
          <div class="card-body">
           
            <form autocomplete="off" @submit.prevent="addMessage" v-if="!success" method="post">

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
                title: '',
                body: '',
                email: '',
                receiver_group: 1,
                author: null,
                // is_leadership: 0,
                error: false,
                errors: {},
                success: false,
                isProgress: false,
                division: -1,
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
            addMessage(){
                this.axios.post('api/auth/newMessage', {
                    token: this.$store.state.token,
                    title: this. title,
                    body: this.body,
                    email: this.email,
                    receiver_group: this.receiver_group,
                    division: this.division,
                    author: this.author,
                }).then(response => {
                    this.isProgress = true;
                    if(response.data.success == true)
                    {
                        setTimeout(() => {
                            this.isProgress = false;
                            this.$router.push({ name: 'dashboard'})
                            this.$toaster.success('Wiadomość zapisana')
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
                 this.author = this.$store.state.user_id;
                this.division = this.$store.state.division;
              }.bind(this));
         
            },
            // getUser: function(){
            //   this.axios.post('/api/auth/getUser', {
            //       token: this.$store.state.token, 
            //       email:this.$store.state.email
            //       })
            //   .then(function (response) {
            //      this.author = response.data[0].id;
            //      this.division = response.data[0].division;
            //      this.$store.commit('refreshUser', response.data[0])
            //   }.bind(this)); 
              
            // },
        },
        created: function(){
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
</style>