 <template>
        <div>
            <Menu></Menu>
                
            <main>
                <router-view></router-view>
            </main>
        </div>
    </template>
    <script>
        import Menu from './Menu.vue'
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

