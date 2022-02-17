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
          <Buttons
            :input="input"
            :sendButtonDisable="sendButtonDisable"
            ajax="./ajax/ajax_nc.php.php"
          />
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
import Buttons from "@/components/Buttons.vue";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    DialogAfterSendForm,
    RqCardTitle,
    Input,
    Buttons,
    TitleService,
  },
  data: () => ({
    sub_message:
      "Данная услуга удаляет сетевой файловый каталог. Также вы сможете отслеживать статус заявки в разделе",
    dialog: false,
    btnLoader: false,
    btnStatus: true,
    dialogMessage: "",
    sendButtonDisable: true,
    input: [
      {
        id: 0,
        name: "Имя каталога:*",
        type: "autocomplete",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        items: [],
        color: "orange lighten-3",
        outlined: true,
        dense: true,
        solo: true,
        visible: true,
        icon: "mdi-folder",
        err: "",
      },
      {
        id: 1,
        name: "Причина удаления:*",
        type: "textarea",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },
    ],
  }),
  created() {
    bus.$on("valueAutocomplete", (data) => {
      this.input[1].value = data;
    });
  },
  watch: {
    change_switch1(newValue) {
      if (newValue == true) {
        this.input[0].label = "Подтверждаю";
        this.btnStatus = false;
      } else {
        this.input[0].label = "";
        this.btnStatus = true;
      }
    },
  },
  methods: {
    formCancl: function () {
      this.$router.go(-1);
    },
    getFolder() {
      axios
        .get("./ajax/ajax_nc.php", {
          data: {
            type: "getFldr",
          },
        })
        .then((response) => (this.input[1].items = response.data))
        .catch(
          (error) => (
            (this.dialogMessage = "Произошла ошибка: " + error),
            (this.dialog = false)
          )
        );
    },
    checkInput() {
      for (var i = 0; i < this.input.length; i++) {
        if (this.input[i].value == "") {
          var item = this.input[i];
          item["err"] = "Необходимо заполнить данное поле";
        } else {
          this.input[i]["err"] = "";
        }
      }
    },
    checkArrValue(arr) {
      return arr.value != "";
    },
    checkData() {
      let tempArr = this.input;
      let sts = tempArr.every(this.checkArrValue);
      if (sts) {
        this.formSend();
      } else {
        this.checkInput();
      }
    },
    formSend() {
      this.btnLoader = true;
      if (this.input) {
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "multipart/form-data" },
          url: "./ajax/ajax_nc.php",
          data: {
            type: "nc006",
            input: this.input,
          },
        })
          .then((response) => {
            if (response.status == 200) {
              this.dialog = true;
              this.dialogMessage = "Ваша заявка успешно зарегистрирована.";
              this.btnLoader = false;
            }
          })
          .catch((error) => {
            (this.dialog = true),
              (this.dialogMessage = "Произошла ошибка: " + error),
              (this.btnLoader = false);
          });
      }
    },
  },
  mounted() {
    this.getFolder();
  },
};
</script>

<style>
</style>