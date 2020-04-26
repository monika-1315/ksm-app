<template>

    
    <div class="container">
      <h1>Witaj {{ name}}!</h1>
      <p v-if="!this.$store.state.is_authorized">Twoje konto nie zostało jeszcze zatwierdzone. Skontaktuj się z Kierownictwem oddziału</p>
      <div v-if="this.$store.state.is_authorized">
      <h3>Najnowsze wiadomości:
      <button class="btn btn-primary" type="button" name="action" @click="getMessages" style="float: right">Odśwież</button></h3>
      <br>
      <span v-for="tab in tabs" :key="tab.id">
        <button class="tab btn btn-light" @click="selectedTab=tab.id"  v-if="tab.id!=='D'||is_leadership">
          <a :class="{activeTab: selectedTab===tab.id}" >{{ tab.text }}</a>
        </button>
      </span>

        <div  v-for="message in messages" :key="message.id" >
        <div class="card card-default" 
        v-if="selectedTab==='A'&&(message.receiver_group!==2||is_leadership)|| selectedTab==='B'&&message.receiver_group===1||selectedTab==='C'&&message.receiver_group===0||selectedTab==='D'&&message.receiver_group===2">
          <div class="card-header"><h5>{{message.title}}</h5></div>
          <div class="card-body">
              <p>{{message.body}}</p>
              <p class="stamp">{{message.published_at}}</p>
          </div>
        </div>
        </div>
      </div>
    </div>
</template>
<script>
  export default {
     
    data() {
      return {
        messages: [],
        tabs: [{id:'A', text:'Wszystkie'},{id:'B', text:'Oddział'}, {id:'C', text:'Od Zarządu'}, {id:'D', text:'Do Kierownictw'}],
        selectedTab: 'A',
      }
    },
    components: {
      //
    },
    computed:{
      name(){
        return this.$store.state.name;
      },
      division(){
        return this.$store.state.division;
      },
      is_leadership(){
        return this.$store.state.is_leadership;
      }
    },
    watch:{
      division: function(){
        this.getMessages();
      }
    },
    methods:{
    getMessages: function(){
      this.$router.push({ name: 'editmessage', params:{id:1}})
              // this.axios.post('/api/auth/getMessages', {
              //   is_leadership: this.is_leadership,
              //   token:this.$store.state.token,
              //   division: this.$store.state.division
              // })
              // .then(function (response) {
              //    this.messages= response.data;
              // }.bind(this));
             
            },
        },
    created: function(){
      if(this.$store.state.division!==-1)
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