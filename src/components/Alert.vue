<template>
  <v-container fluid>
    <transition>
      <v-alert prominent type="error">
        <v-row align="center">
          <v-col class="grow">
            {{ text }}
          </v-col>
          <v-col class="shrink">
            <v-btn @click="formCancl()">Назад</v-btn>
          </v-col>
        </v-row>
      </v-alert>
    </transition>
  </v-container>
</template>

<script>
export default {
  props: {
    visible: { type: Boolean, default: false },
    text: { type: String },
  },
  data: () => ({}),
  methods: {
    formCancl: function () {
      this.$router.go(-1);
    },
  },
};
</script>
