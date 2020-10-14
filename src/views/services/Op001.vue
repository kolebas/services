<template>
  <v-container>
    <DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage" />
    <v-row>
      <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
        <RqCardTitle
          :title="$router.currentRoute.name"
          :sub_message="sub_message"
        ></RqCardTitle>
        <hr />
        <Input :arrInput="input" />
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
    sub_message:
      "Для создания заявки на опыт, инициатор должен обязательно прикрепить скан приказа на проведение опыта.",
    dialog: false,
    dialogMessage: "",
    btnLoader: false,
    btnStatus: true,
    input: [
      {
        id: 0,
        name: "Название организации:",
        label: "Пример: ПАО Родина",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 0,
        name: "Контактное лицо:",
        label: "Пример: Кисс Николай Николаевич",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 0,
        name: "Контактная информация:",
        label: "Пример: Телефон: +7901001020, Электронная почта: agro@example.com",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 0,
        name: "Тема опыта:",
        labbel: "Пример: тестирование нового препарата",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 0,
        name: "Цели опыта:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 0,
        name: "Задачи опыта:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 0,
        name: "Объект опыта:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 0,
        name: "Экономическая эффективность: ",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      
      {
        id: 0,
        name: "Агрономические параметры опыта:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 0,
        name: "Реквизиты организации:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 2,
        name: "Комментарий:",
        label: "Напишите что по вашему мнению должны знать согласующие",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      }
    ],
  }),
  created() {
    bus.$on("inputFile", (data) => {
      this.input[3].value = data;
    });
  },
  methods: {
    formCancl() {
      this.$router.go(-1);
    },
    formSend() {
      this.btnLoader = true;
      if (this.input) {
        console.log(this.input);
        axios({
          method: "post",
          headers: { "Content-Type": "multipart/form-data" },
          url: "./ajax/ajax_op001.php",
          data: this.input,
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
            console.log(error);
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка";
            this.btnLoader = false;
          });
      }
    },
  },
};
</script>

<style>
</style>