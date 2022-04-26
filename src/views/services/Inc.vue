<template>
  <v-container fluid>
    <TitleService />
    <v-card min-height="800px" class="py-12">
      <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
      <v-card width="55%" raised class="mx-auto" color="grey lighten-4">
        <v-card-text class="pa-0">
          <p class="text-center pt-4 headline text--primary">{{ title }}</p>
          <p class="subtitle-1 font-weight-medium mx-8">{{ sub_title }}</p>
        </v-card-text>
        <v-divider />
        <br />
        <v-row>
          <v-col cols="11" class="mx-auto">
            <Input :arrInput="inputs" />
          </v-col>
        </v-row>
        <v-divider />
        <v-card-actions class="py-4">
          <div class="mx-auto">
            <v-btn
              class="mx-1"
              :loading="btnLoader"
              :disabled="!getIncTitle"
              color="green lighten-2 white--text"
              @click="formSend()"
            >
              Отправить
            </v-btn>
            <v-btn class="mx-1" @click="formCancl()"> Отмена </v-btn>
          </div>
        </v-card-actions>
      </v-card>
    </v-card>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import axios from "axios";
import TitleService from "@/components/TitleService.vue";
import Input from "@/components/Input.vue";
export default {
  components: {
    DialogAfterSendForm,
    TitleService,
    Input,
  },
  data: () => ({
    title: "Новое обращение",
    sub_title:
      'Если у вас возникли проблемы с используемым оборудованием, программным обеспечением или вы нуждаетесь в консультации специалистов, заполните предлагаемую форму и нажмите кнопку "ОТПРАВИТЬ".',
    dialog: false,
    dialogMessage: "",
    btnLoader: false,
    sendButtonDisable: false,
    inputs: [],
    fields: [
      {
        label: "Краткое описание",
        title: "title_inc",
        value: "",
        required: true,
        type: "string",
        rule: [
          (value) => !!value || "Обязательное поле",
          (value) => (value && value.length > 8) || "Минимальное количество символов 8",
          ],
      },
      {
        label: "Кто обратился",
        title: "responsible",
        value: "",
        class: "mt-n2",
        type: "selectUsr",
        visible: false,
      },
      {
        label: "Проблема связана с 1С",
        title: "switch_1c",
        value: "",
        type: "switch",
        class: "mt-n4",
      },
      {
        label: "Проблема связана мониторингом",
        title: "switch_mon",
        value: "",
        type: "switch",
        class: "mt-n4",
      },
      {
        label: "Направление 1С",
        title: "type_1c_slct",
        value: "",
        type: "select",
        select_arr: [
          "Бухгалтерский учёт",
          "ГК ПИР",
          "Зарплата и управление персоналом",
          "Документооборот (договоры, приказы, поручение, закупки)",
          "Казначейство",
          "Закупка",
          "Весовые",
          "Эдо, EDI, ККТ",
          "Логистика",
          "МСФО",
          "1С не работает, работает медленно",
          "Ошибка в НСИ",
        ],
        rule: [],
        visible: false,
      },
      {
        label: "Подробное описание",
        title: "cmnt",
        value: "",
        class: "mt-n2",
        type: "textarea",
      },
      {
        label: "",
        value: "",
        type: "file",
      },
    ],
    userData: {},
    usrid: null,
    usrGroup: [],
  }),
  created() {
    this.createInputs();
    bus.$on("inputFile", (data) => {
      this.file = data;
      this.inputs.find((item) => item.type === "file").value = data;
    });
    bus.$on("SelectUsr", (data) => {
      const userInput = this.inputs.find((item) => item.type === "selectUsr");
      userInput.value = data.userId;
    });
    this.getUserData();
  },
   computed: {
    condition() {
      return this.usrid == 1 || this.usrGroup.includes("22");
    },
    getSwitch1C(){
      if(this.inputs.length > 0){
        const switch1C = this.inputs.find(item => item.title === "switch_1c");
        return switch1C.value;
      } else {
        return false;
      }
    },
    getIncTitle(){
      if(this.inputs.length > 0){
        const title = this.inputs.find((item) => item.title === "title_inc").value;
        if (title.length > 8){
            return true;
          } else {
            return false;
          }
      } else {
          return false;
        }
    }      
  },
  watch:{
    getSwitch1C(value){
      const select1C = this.inputs.find(item => item.title === "type_1c_slct");
      if(value === false){
        select1C.value = null;
      }
      select1C.visible = value;
      select1C.required = value;
      select1C.rule = [
          (value) => !!value || "Обязательное поле",
      ];
    },
    condition(value){
      const userField = this.inputs.find(item => item.title === "responsible");
      userField.visible = value;
    }
  },
  methods: {
    formSend() {
      this.checkArray(this.inputs);
      this.btnLoader = true;
      var formData = new FormData();
      this.inputs.forEach((element) => {
        formData.append(element.title, element.value);
      });
      const fileInput = this.inputs.find(item => item.type === "file").value
      for (var i = 0; i < fileInput.length; i++) {
        let file = fileInput[i];
        formData.append("file[" + i + "]", file);
      }
      axios({
        method: "post",
        withCredentials: true,
        headers: { "Content-Type": "multipart/form-data" },
        url: "./ajax/ajax_inc.php",
        data: formData,
      })
        .then((response) => {
          if (response.status == 200) {
            this.dialog = true;
            this.dialogMessage = "Ваша заявка успешно зарегистрирована.";
            this.btnLoader = false;
          }
        })
        .catch((error) => {
          this.dialog = true;
          this.dialogMessage = `Произошла ошибка ${error}`;
        });
    },
    formCancl: function () {
      this.$router.go(-1);
    },
    getUserData() {
      axios
        .get("./ajax/ajax_usr.php", {
          auth: {},
        })
        .then(
          (response) => (
            (this.usrid = response.data[0]["ID"]),
            (this.usrGroup = response.data[0]["GROUP"])
          )
        );
    },
    createInputs(){
        this.fields.forEach((element) => {
          const obj = {
            value: "",
            cs: "12",
            sm: "12",
            md: "12",
            outlined: true,
            dense: true,
            solo: true,
            err: "",
          };
        let item = Object.assign(obj, element);
        this.inputs.push(item);
      });
    },
    checkArray(){
      this.inputs.filter(item => item.required === true).forEach((element) => {
        if(element.value == null) {
          return false;
        } else {
          return true;
        }
      })
    },
  },
};
</script>