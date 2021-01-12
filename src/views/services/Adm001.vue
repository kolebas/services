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
  data:() => ({
      sub_message: "Заявка для ????????????????????????????????????????????????????",
      dialog: false,
        dialogMessage: "",
        btnLoader: false,
        btnStatus: true,
      input: [{
        id: 0,
        name: "ФИО:",
        label: "Пример: Крючков Иван Петрович",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        class: "mt-n2 mb-n3",
        type: "selectUsr",
      },
      {
        id: 1,
        name: "Тип доступа:",
        value: "",
        items: [
            {NAME: "Микротики", ICON: "mdi-router-network"},
            {NAME: "Сервера", ICON: "mdi-server"}
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
        id: 2,
        name: "Комментарий:",
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
  }),
  computed: {
    getValue() {
      return this.input[0].value && this.input[1].value;
    },
  },
  watch: {
    getValue(newValue) {
        console.log(newValue)
      if (newValue) {
        //this.input[0].label = "Подтверждаю";
        this.btnStatus = false
      } else {
        //this.input[0].label = "";
        this.btnStatus = true
      }
    },
  },
  methods: {
      formCancl() {
      this.$router.go(-1);
    },
    formSend() {
      this.btnLoader = true;
      if (this.input) {
        axios({
          method: "post",
          headers: { "Content-Type": "multipart/form-data" },
          url: "./ajax/ajax_adm001.php",
          data: {
            input: this.input,
            params: this.params
          }
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
  }
}
</script>

<style>

</style>