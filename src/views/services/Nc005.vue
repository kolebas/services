<template>
  <v-container>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
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
              @click="checkData()"
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
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import RqCardTitle from "@/components/RqCardTitle.vue";
import Input from "@/components/Input.vue";
import axios from "axios";
export default {
  components: {
    DialogAfterSendForm,
    RqCardTitle,
    Input,
  },
  data: () => ({
    sub_message: "Данная услуга позволяет увеличить размер сетевого каталога. Также вы сможете отслеживать статус заявки в разделе",
    dialog: false,
    btnLoader: false,
    btnStatus: false,
    dialogMessage: "",
    input: [
      {
        id: 0,
        name: "Подтвердите отсуствие дублирующей информации, а также видеоматериалов и другой ифнормации не относящейся с служебной деятельности",
        value: "",
        type: "switch",
        class: "mt-2",
      },
      {
        id: 1,
        name: "Имя каталога:",
        type: "autocomplete",
        value: [],
        items: [],
        color: "orange lighten-3",
        outlined: true,
        dense: true,
        solo: true,
        icon: "mdi-folder",
        err: "",
      },
      {
        id: 2,
        name: "Объем на который необходимо увеличить квоту:",
        type: "select",
        value: "",
        cs: "13",
        md: "2",
        select_arr: ["10 Гб.", "50 Гб.", "100 Гб.", "150 Гб."],
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },      
    ],
  }),
  created() {
    bus.$on("valueAutocomplete", (data) => {
      this.input[0].value = data;
    });
  },
  computed: {
    change_switch1() {
      return this.input[0].value;
    },
  },
  watch: {
    change_switch1(newValue) {
      if (newValue == true) {
        this.input[0].label = "Подтверждаю";
        this.btnStatus = false
      } else {
        this.input[0].label = "";
        this.btnStatus = true
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
        .then((response) => (this.input[0].items = response.data))
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
            type: "nc005",
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