<template>
<div class="container" >
    <h2>Zarządzaj oddziałami</h2>
    <br>
   
    <div v-for="division in divisions" :key="division.id" >
        <p>
            <button class="btn btn-primary floating" type="button"  @click="edit(division.id)">Edytuj</button>
            <h4>{{division.town}} <span class="inactive" v-if="division.is_active===0"> NIEAKTYWNY</span></h4>
            
            {{'parafia '+division.parish}}       
            <hr>
        </p>
    </div>
</div>
</template>

<script>
    export default {
        data(){
            return{
            divisions: [],
            }
        },
        methods:{
            edit: function(id){

            },
            getDivisions: function(){
              this.axios.post('/api/auth/allDivisions',{token: this.$store.state.token})
              .then(function (response) {
                 this.divisions = response.data;
              }.bind(this));
         
            },
        },
        created: function(){
            this.getDivisions()
        }
    }
</script>
        
<style scoped>
    .floating{
        float: right;
    }
    .inactive{
        font-size: normal;
        color: darkgrey;
    }
</style>