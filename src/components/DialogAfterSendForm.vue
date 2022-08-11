<template>
  <div class="text-center">
    <v-dialog
      :value="dialog"
      :width="widthDialogParam"
      :persistent="disablePersistentParam"
    >
      <v-card>
        <v-card-title :class="classTitle" primary-title color="red">
          {{ titleDialogParam }}
        </v-card-title>
        <v-card-text v-if="warnMessage" class="subtitle-1 text-center mt-4">
          {{ warnMessage }}
        </v-card-text>
        <v-alert v-if="alertMessage" :outlined="alertMessageOutlined" :type="alertMessageType" :border="alertMessageBorder" :colored-border="alertMessageColorBorder" prominent :class="alertMessageClass" :rounded="alertMessageRounded">
          {{ alertMessage }}
        </v-alert>
        <template v-if="dataArray">
          <v-card background-color="red">
            <v-list dense>
              <v-list-item v-for="item in dataArray" :key="item.id">
                <v-col cols="1"> </v-col>
                <v-col cols="8">
                  <v-card-text class="subtitle-1 pt-2">
                    {{ item.text }}
                  </v-card-text>
                </v-col>
                <v-col cols="2">
                  <v-text-field
                    outlined
                    solo
                    dense
                    suffix="шт."
                    v-model="item.value"
                  />
                </v-col>
              </v-list-item>
            </v-list>
          </v-card>
        </template>
        <v-card-text
          v-if="subMessage"
          color="info"
          class="subtitle-1 text-center mt-4"
          ><p v-html="subMessage"></p>
        </v-card-text>
        <v-divider />
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            :disabled="btnDisable"
            text
            @click="funcDialog()"
          >
            {{ btnTextParam }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { bus } from "@/main.js";
export default {
  components: {},
  props: {
    disablePersistentParam: { type: Boolean, default: true },
    dialog: { type: Boolean },
    classTitle: { type: String, default: "headline grey lighten-2" },
    warnMessage: { type: String },
    subMessage: { type: String },
    alertMessage: { type: String },
    alertMessageType: { type: String, default: "info" },
    alertMessageBorder: { type: String },
    alertMessageColorBorder: { type:Boolean },
    alertMessageOutlined: { type:Boolean },
    alertMessageClass: { type: String, default: "mb-0" },
    alertMessageRounded: { type: String, default: "0" },
    route: { type: String, default: "-1" },
    widthDialogParam: { type: String, default: "500px" },
    titleDialogParam: { type: String, default: "Статус" },
    btnTextParam: { type: String, default: "Закрыть" },
    btnDisable: { type: Boolean, default: false },
    dataArray: { type: Array },
  },
  data: () => ({}),
  methods: {
    funcDialog() {
      if (this.route == 1) {
        this.dialog = false;
        bus.$emit("chngSwitch", this.dialog);
      } else {
        this.$router.go(this.route);
      }
    },
  },
};
</script>
