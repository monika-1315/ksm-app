<template>
<div class="container" style="text-align:center">
    <h2>Na autoryzację oczekuje: {{users.length}} członków</h2>
    <br>
    <div class="progress" v-if="isProgress">
        <div class="indeterminate"></div>
    </div>
    <div v-for="user in users" :key="user.id" >
        <p>
            <h4>{{user.name+' '+user.surname}} </h4>       
            <button class="btn btn-primary" type="button"  @click="authorize(user.id)">Zatwierdź konto</button>
        </p>
    </div>
</div>
</template>

<script>
    export default {
        data(){
            return{
            users: [],
            isProgress: false,
            }
        },
        methods:{
            authorize(id){
                this.axios.post('/api/auth/authorizeUser?token=' + this.$store.state.token+'&id='+id)
                 .then(response => {
                    this.isProgress = true;
                    if(response.data.success == true)
                    {
                        setTimeout(() => {
                            this.isProgress = false;
                            this.$toaster.success('Zatwierdzono członka oddziału')
                             this.getUsers();
                        }, 2000)
                    }
                }).catch(error => {
                    this.isProgress = false;
                    this.$toaster.error('Coś poszło nie tak!')
                });
            },
             getUsers: function(){
              this.axios.post('/api/auth/getUnauthorizedUsers?token=' + this.$store.state.token+'&division='+this.$store.state.division)
              .then(function (response) {
                 this.users = response.data;
              }.bind(this)); 
              
            },
        },
        created: function(){
                this.getUsers();
        }
    }
</script>
        
<style scoped>
.progress{
        margin:0px;
        background-color: transparent;

    }
    .indeterminate{
        background-color: royalblue;
    }
</style>