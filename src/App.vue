<template>
  <v-app>
    <!--<v-app-bar
      app
      color="primary"
      dark
    >
      <div class="d-flex align-center">
        <v-img
          alt="Vuetify Logo"
          class="shrink mr-2"
          contain
          src="https://cdn.vuetifyjs.com/images/logos/vuetify-logo-dark.png"
          transition="scale-transition"
          width="40"
        />

        <v-img
          alt="Vuetify Name"
          class="shrink mt-1 hidden-sm-and-down"
          contain
          min-width="100"
          src="https://cdn.vuetifyjs.com/images/logos/vuetify-name-dark.png"
          width="100"
        />
      </div>

      <v-spacer></v-spacer>

      <v-btn
        href="https://github.com/vuetifyjs/vuetify/releases/latest"
        target="_blank"
        text
      >
        <span class="mr-2">Latest Release</span>
        <v-icon>mdi-open-in-new</v-icon>
      </v-btn>
    </v-app-bar>-->
    
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
