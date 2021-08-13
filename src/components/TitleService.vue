<template>
  <v-container>
    <v-card-title class="white--text mt-n12">
      <v-img v-if="icon" :src="icon" />
      <h1>{{ title }}</h1>
    </v-card-title>
    <v-divider />
  </v-container>
</template>

<script>
export default {
  props: {
    title: { type: String },
    icon: { type: String },
  },

  data: () => ({}),
};
</script>

 