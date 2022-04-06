<template>
  <v-container fluid>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
    <TitleService />
    <v-card min-height="800px" class="py-12">
      <v-row>
        <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
          <RqCardTitle
            :title="$router.currentRoute.name"
            :sub_message="sub_message"
          ></RqCardTitle>
          <hr />
          <Input :arrInput="input.filter((item) => item.visible === true)" />

          <v-card-text> * Поля обязательные для заполнения </v-card-text>
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
    </v-card>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import RqCardTitle from "@/components/RqCardTitle.vue";
import Input from "@/components/Input.vue";
import axios from "axios";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    DialogAfterSendForm,
    RqCardTitle,
    Input,
    TitleService,
  },
  data: () => ({
    sub_message: "ms004",
    id: "",
    input: [
      {
        value: "",
        cols_title: 5,
        cols_input: 6,
        org_err: "",
        type: "selectOrg",
        outlined: true,
        dense: true,
        solo: true,
        visible: true,
      },
      { 
        name: "Подразделение:*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "select",
        select_arr: [ "Агро-Кевсалинский", "Агро-Марьинский", "Агро-ПервоеМая", "Агро-Восход" ],
        outlined: true,
        dense: true,
        solo: true,
        visible: false,
      },
      {
        name: "Наименование ОС:*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        visible: true,
      },
      {
        name: "Государственный регистрационный номер или инвентарный номер:*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        visible: true,
      },
      {
        name: "Арендованная техника:",
        value: "",
        type: "switch",
        class: "mt-2",
        cs: "12",
        sm: "6",
        md: "6",
        visible: true,
      },
      {
        name: "Код ОС 1С:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        visible: true,
      },
    ],
    btnStatus: false,
    btnLoader: false,
    dialog: false,
    dialogMessage: "",
  }),

  created() {
    bus.$on("selectOrg", (data) => {
      const inputOrg = this.input.find((item) => item.type === "selectOrg");
      const inputDepartment = this.input.find(item => item.type === "select");
      inputOrg.org_err = "";
      inputOrg.value = data.name;
      if (inputOrg.value === "АО «Агрохлебопродукт»") {
        inputDepartment.visible = true;
      } else {
        inputDepartment.visible = false;
        inputDepartment.value = null;
      }
    });
  },
  computed: {
    getValue() {
      return this.input;
    },
    getValueSwitch() {
      const inputSwitch = this.input.find((item) => item.type === "switch");      
      return inputSwitch.value;
    },
  },
  watch: {
    getValue: {
      handler(val) {
        console.log('a thing changed ' + val.value);
      },
      deep: true
    },    
    getValueSwitch(newValue) {
      const inputKode1C = this.input.find((item) => item.name === "Код ОС 1С:");
      if (newValue === true) {
        inputKode1C.visible = false;
      } else {
        inputKode1C.visible = true;
      }
    },
  },
  methods: {
    formSend: function () {
      this.btnLoader = true;
      axios({
        method: "post",
        withCredentials: true,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        url: "./ajax/ajax_ms004.php",
        data: {
          input: this.input,
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
          console.log(error);
          this.dialog = true;
          this.dialogMessage = "Произошла ошибка";
          this.btnLoader = false;
        });
    },
    //Действие кнопки "назад"
    formCancl: function () {
      this.$router.go(-1);
    },
  },
};
</script>