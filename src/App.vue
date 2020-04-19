<template>
  <v-app>
    <div color="green">
      <v-breadcrumbs :items="items">
      <template v-slot:item="{ item }">
        <v-breadcrumbs-item
          :href="item.href"
          :disabled="item.disabled"
        >
          {{ item.text.toUpperCase() }}
        </v-breadcrumbs-item>
      </template>
    </v-breadcrumbs>
    </div>
    <v-content>
      <router-view/>
    </v-content>
  </v-app>
</template>

<script>
//import Services from './components/Services'

export default {
  name: 'App',

  components: {
   // Services,
  },

  data: () => ({
      items: [
        {
          text: 'Главная',
          disabled: false,
          href: 'https://portal.ahstep.ru/',
        },
        {
          text: 'Заявки',
          disabled: false,
          href: '/',
        },
      ],    
  }),
};
</script>

<style>
html {
		overflow: hidden;
  }
  .bx-im-bar {
		width: 80px;
	}
  .v-breadcrumbs{
    background: #f5f5f5;
    margin-left: 1%!important;
    margin-right: 1%!important;
    margin-top: 10px!important;
    margin-bottom: 10px!important;
    border-radius: 10px!important;
  }
</style>
