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
          <Input :arrInput="input" />

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
        name: "Подразделение*",
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
      },
    ],
    btnStatus: true,
    btnLoader: false,
    dialog: false,
    dialogMessage: "",
  }),

  created() {
    bus.$on("selectOrg", (data) => {
      this.input[0].org_err = "";
      this.input[0].value = data.name;
    });
  },
  computed: {
    getValue() {      
      return (
        this.input[0].value &&
        this.input[1].value &&
        this.input[2].value &&
        this.input[4].value
      );
    },
    getValueSwitch() {
      return this.input.filter((item) => item.type === "switch").value;
    },
  },
  watch: {
    getValue(newValue) {
      if (newValue) {
        if (this.input[3].value == true) {
          this.btnStatus = false;
          this.getValueSwitch();
        }
        if (this.input[3].value == false) {
          this.btnStatus = false;
        }
      } else {
        this.btnStatus = true;
      }
    },
    getValueSwitch(newValue) {
      if (!newValue) {
        this.input[4].visible = true;
        this.input[4].value = "";
        this.input[3].label = "";
        this.btnStatus = true;
      } else {
        this.input[4].visible = false;
        this.input[3].label = "Да";
        this.btnStatus = false;
      }
    },
  },
  methods: {
    formSend: function () {
      console.log(this.input);
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
      /*if(!this.org_name){
                this.org_err = 'Не выбрана организация';                
            }
            if(!this.inputs[0].value){
                this.inputs[0].err = 'Не указано наименование ОС'                
            }
            if(!this.inputs[1].value){
                this.inputs[1].err = 'Не указан государственный регистрационный номер или инвентарный номер'                
            }
            if(!this.inputs[2].value){
                this.inputs[2].err = 'Не указан код ОС 1С'                
            } */
    },
    //Действие кнопки "назад"
    formCancl: function () {
      this.$router.go(-1);
    },
  },
};
</script>