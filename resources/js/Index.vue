<template>
    <div id="main">
        <header id="header">
           
            <h1>Katolickie Stowarzyszenie Młodzieży </h1>
        </header>
        <div id="content">
          <Menu></Menu>
        </div>
    </div>
</template>
<script>
import Menu from './components/Menu.vue'
  export default {
    data() {
      return {
        //
      }
    },
    components: {
        Menu
    }
  }
</script>