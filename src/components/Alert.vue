<template>
  <v-container fluid>
    <transition>
      <v-alert prominent :type="type" :colored-border="colorBorder" :border="borderType" elevation="2">
        <v-row align="center">
          <v-col class="grow">
            {{ text }}
          </v-col>
          <v-col v-if="btnBack" class="shrink">
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
    btnBack: { type: Boolean, default: true},
    type: { type: String, default: "error"},
    borderType: { type: String, default: "error"},
    colorBorder: { type: Boolean, default: false},
    alertElevation: { type: String, default: "2"},
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
