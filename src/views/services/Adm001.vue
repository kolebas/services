<template>
  <v-container>
    <DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage" />
    <v-row>
      <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
        <RqCardTitle
          :title="$router.currentRoute.name"
          :sub_message_enable="false"
        ></RqCardTitle>
        <hr />
        <Input :arrInput="input" />
        <v-card-text>
          * Поля обязательные для заполнения
        </v-card-text>
        <hr />
        <v-card-actions class="py-4">
          <div class="mx-auto">
            <v-btn
              class="mx-1"
              :loading="btnLoader"
              :disabled="btnStatus"
              color="green lighten-2 white--text"
              @click="formSend()"
            >
              Отправить
            </v-btn>
            <v-btn class="mx-1" @click="formCancl()"> Отмена </v-btn>
          </div>
        </v-card-actions>
      </v-card>
    </v-row>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import RqCardTitle from "@/components/RqCardTitle.vue";
import DialogAfterSendFrom from "@/components/DialogAfterSendForm.vue";
import Input from "@/components/Input.vue";
import axios from "axios";
export default {
  components: {
    RqCardTitle,
    DialogAfterSendFrom,
    Input,
  },
  data: () => ({
    dialog: false,
    dialogMessage: "",
    btnLoader: false,
    btnStatus: true,
    input: [
      {
        name: "ФИО*:",
        label: "Пример: Крючков Иван Петрович",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        class: "mt-n2 mb-n3",
        type: "selectUsr",
      },
      {
        name: "Тип доступа*:",
        value: "",
        items: [
          { NAME: "Сетевое", ICON: "mdi-router-network" },
          { NAME: "Серверное", ICON: "mdi-server" },
          { NAME: "Другое", ICON: "mdi-crosshairs-question" },
        ],
        cs: "12",
        sm: "6",
        md: "6",
        type: "autocomplete",
        color: "green",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Комментарий:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
      },
    ],
  }),
  created() {
    bus.$on("SelectUsr", (data) => {
      this.input[0].value = data;
    });
  },
  computed: {
    getValue() {
      return this.input[0].value && this.input[1].value;
    },
  },
  watch: {
    getValue(newValue) {
      if (newValue) {
        this.btnStatus = false;
      } else {
        this.btnStatus = true;
      }
    },
  },
  methods: {
    formCancl() {
      this.$router.go(-1);
    },
    formSend() {
      if (this.input[1].value) {
        this.btnLoader = true;
        axios({
          method: "post",
          headers: { "Content-Type": "multipart/form-data" },
          url: "./ajax/ajax_service.php",
          data: {
            input: this.input,
            type: "adm001"
          },
        })
          .then((response) => {
            if (response.status == 200) {
              this.dialog = true;
              this.dialogMessage =
                "Успешно. Номер вашей заявки: " + response.data;
              this.btnLoader = false;
            }
          })
          .catch((error) => {
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка " + error;
            this.btnLoader = false;
          });
      }
    },
  },
};
</script>

<style>
</style>