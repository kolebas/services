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
        <Input :arrInput="input1" />
        <v-expand-transition>
          <template v-if="input1[0].value">
            <Input :arrInput="input" />
          </template>
        </v-expand-transition>
        <hr />
        <v-card-actions class="py-4">
          <div class="mx-auto">
            <v-btn
              class="mx-1"
              :loading="btnLoader"
              :disabled="btnStatus"
              color="green lighten-2 white--text"
              @click="checkArray()"
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
      "Коллеги просим обратить внимание что заявка на запись обучения, вебинара попадает в работу только после успешного согласования. Заявка заводиться не менее чем за сутки до мероприятия",
    dialog: false,
    dialogMessage: "",
    btnLoader: false,
    btnStatus: true,
    input: [
      {
        id: 0,
        name: "Тема мероприятия:*",
        label: "Пример: SAS Virtual Learning Conference",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },
      {
        id: 1,
        name: "Организатор/Партнёр:*",
        label: "Пример: SAS Russia Training Center",
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
        id: 2,
        name: "Контатное лицо(ФИО, телефон, e-mail)*:",
        label:
          "Пример: Телефон: +7901001020, Электронная почта: agro@example.com",
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
        id: 3,
        name: "Дата проведения мероприятия:*",
        label: "Пример: 18.12.2020",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "date",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 4,
        name: "Данные для подключения:*",
        hint: "При необходимости регистрации, данные для входа предоставляет заявитель",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
      }
    ],
    input1: [
      {
        id: 0,
        name: "Подтвердите что организатор не предосталяет запись мероприятия",
        value: "",
        cs: "14",
        sm: "4",
        md: "4",
        type: "switch",
        class: "mt-2",
      },
    ],
  }),
  created() {
    bus.$on("inputFile", (data) => {
      this.input[3].value = data;
    });
  },
  computed: {
    change_switch1() {
      return this.input1[0].value;
    },
    /*change_switch2() {
      return this.input[5].value;
    },*/
  },
  watch: {
    change_switch1(newValue) {
      if (newValue == true) {
        this.input1[0].label = "Подтверждаю";
        this.btnStatus = false
      } else {
        this.input1[0].label = "";
        this.btnStatus = true
      }
    },
    /*change_switch2(newValue) {
      if (newValue == true) {
        this.input[5].label = "ДА";
        this.input[6].visible = true
      } else {
        this.input[5].label = "";
        this.input[6].visible = false
      }
    },*/
  },
  methods: {
    formCancl() {
      this.$router.go(-1);
    },
    checkInput(arr) {
      for (var i = 0; i < arr.length; i++) {
        if (arr[i].value == "") {
          var item = arr[i];
          item["err"] = "Необходимо заполнить данное поле";
        } else {
          arr[i]["err"] = "";
        }
      }
    },
    checkArrayValue(arr) {
      return arr.value != "";
    },
    checkArray() {
      var tempArr = this.input.filter((test) => test.name != "Комментарий:");
      let sts = tempArr.every(this.checkArrayValue);
      console.log(sts);
      if (sts) {
        this.formSend();
      } else {
        this.checkInput(tempArr);
      }
    },
    formSend() {
      this.btnLoader = true;
      if (this.input) {
        console.log(this.input);
        axios({
          method: "post",
          headers: { "Content-Type": "multipart/form-data" },
          url: "./ajax/ajax_rc001.php",
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