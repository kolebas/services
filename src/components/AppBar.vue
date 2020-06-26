<template>
    <v-app>
    <v-main>
          <v-alert
            class="ml-4 mr-4"
            border="top"
            colored-border
            color="primary"
            elevation="2"
            dense
          >
          <v-btn 
            v-for="item in items" 
            :key="item.text" 
            text
            @click="itemclk(item.lnk, item.route)"
            > {{item.text}}
          </v-btn>
          <v-btn text disabled>{{ this.$router.currentRoute.name}}</v-btn>
          </v-alert>
      <router-view/>
    </v-main>
  </v-app>
</template>

<script>
export default {
   data: () => ({   
      items: [
       {
          text: 'Главная',
          disabled: false,
          lnk: '/steam',
        }
      ],    
  }),
  methods: {
        itemclk: function(lnk, route){
            if(route != null){
                this.$router.push(route);
            } else {
                document.location.href = lnk;
            }
        }
  },
  mounted(){
    {
      if(this.$router.currentRoute.path != "/"){
        this.items.splice(1,1)
        let services={
          text: 'Заявки',
          route: '/'
        }
        this.items.push(services)
        console.log(this.items)
      }
    }
  }
};
</script>