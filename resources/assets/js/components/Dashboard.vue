<template>

    
    <div class="container">
      <h1>Witaj {{ name}}!</h1>
      <p v-if="!this.$store.state.is_authorized">Twoje konto nie zostało jeszcze zatwierdzone. Skontaktuj się z Kierownictwem oddziału</p>
      <h3>Najnowsze wiadomości:</h3>
      <br>
        <button class="tab btn btn-light" @click="selectedTab=tab.id" v-for="tab in tabs" :key="tab.id" >
          <a :class="{activeTab: selectedTab===tab.id}">{{ tab.text }}</a>
        </button>

        <div class="card card-default"  v-for="message in messages" :key="message.id">
          <div class="card-header"><h5>{{message.title}}</h5></div>
          <div class="card-body">
              <p>{{message.body}}</p>
              <p class="stamp">{{message.author}} {{message.published_at}}</p>
          </div>
        </div>
    </div>
</template>
<script>
  export default {
     
    data() {
      return {
        messages: [],
        tabs: [{id:'A', text:'Wszystkie'},{id:'B', text:'Oddział'}, {id:'C', text:'Od Zarządu'}],
        selectedTab: 'A'
      }
    },
    components: {
      //
    },
    computed:{
      name(){
        return this.$store.state.name;
      }
    },
    methods:{
    getMessages: function(){
              this.axios.post('/api/auth/getMessages?token=' + this.$store.state.token)
              .then(function (response) {
                 this.messages= response.data;
              }.bind(this));
             
            },
        },
    created: function(){
      this.getMessages();
    }
  }
</script>

<style>
.container{
  text-align: left;
}
.card-body{
  text-align: left;
}
.stamp{
  text-align: right;
  font-style: italic;
}

.activeTab{
  font-weight: bold;
}
</style>