<template>
  <v-container fluid>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
    <TitleService icon="./assets/img/1s.png" />
    <v-card min-height="800px" class="py-12">
      <v-row>
        <form id="form" novalidate>
          <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
            <RqCardTitle
              :title="$router.currentRoute.name"
              :sub_message="sub_message"
            ></RqCardTitle>
            <hr />

            <Input :arrInput="input.filter(item => item.title === 'user')" />
            <v-card outlined class="my-6 mx-6" color="grey lighten-2">
              <v-card-title class="mx-auto">
                Куда предоставляется доступ
              </v-card-title>
              <hr />
              <Input :arrInput="input.filter(item => item.title != 'user')" />
            </v-card>
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
        </form>
      </v-row>
    </v-card>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import RqCardTitle from "@/components/RqCardTitle.vue";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import Input from "@/components/Input.vue";
import axios from "axios";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    RqCardTitle,
    DialogAfterSendForm,
    Input,
    TitleService,
  },
  data: () => ({
    sub_message:
      "В рамках исполнения данной заявки будет запущено согласование запрошенных доступов. При изменении статуса заявки, вы будете получать уведомления на электронный почтовый ящик, указанный у вас в профиле. Также вы можете отслеживать статус заявки в разделе.",
    btnLoader: false,
    btnStatus: false,
    input: [
      {
        name: "Организация",
        value: [],
        visible: true,
        items: [],
        err: "",
        multiple: true,
        cs: "12",
        sm: "6",
        md: "6",
        type: "autocomplete",
        outlined: true,
        dense: true,
        solo: true,
        required: true,
      },
      {
        name: "Временный доступ:",
        value: false,
        cs: "12",
        sm: "6",
        md: "6",
        range: true,
        type: "switch",
        class: "mt-2",
      },
      {
        title: "timeInterwall",
        name: "Период временного доступа:",
        value: [],
        cs: "12",
        sm: "6",
        md: "6",
        type: "date",
        outlined: true,
        dense: true,
        solo: true,
        range: true,
        visible: false,
      },
      {
        id: 2,
        title: "db",
        name: "Список БД 1С:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "autocomplete",
        items: [],
        outlined: true,
        dense: true,
        solo: true,
        multiple: true,
        err: "",
      },
      {
        id: 3,        
        title: "accessLevel",
        name: "Права как у кого:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "selectUsr",
        class: "mt-n2",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },
      {
        id: 4,
        name: "Описать уровень доступа:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },
      {
        id: 5,
        name: "Комментарий:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },
      {
        id: 7,
        title: "user",
        name: "ФИО",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "selectUsr",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },
    ],
    cmnt: "",
    cmnt_err: "",
    db: "",
    org: [],
    file: [],
    dialog: false,
    dialogMessage: "",
    source: "./ajax/ajax_1c001.php",
  }),
  created() {
    bus.$on("SelectUsr", (data) => {
      if (data.input_id == "7") {
        this.input.find((item) => {
          if (item.title === "user") {
            item.err = "";
            item.value = "user_" + data.userId;
          }
        });
      }
      if (data.input_id == "3") {
        this.input.find((item) => {
          if (item.title === "accessLevel") {
            item.err = "";
            item.value = "user_" + data.userId;
          }
        });
      }
    });
  },
  mounted() {
    this.getDB();
    this.getOrg();
    this.enableValidation();
  },
  methods: {
    formCancl: function () {
      this.$router.go(-1);
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

    enableValidation() {
      const form = document.querySelector("#form");
      form.addEventListener("submit", (event) => {
        event.preventDefault();
      });
    },

    getDB() {
      axios
        .get(this.source, {
          params: {
            type: "getDB",
          },
        })
        .then((response) =>
          this.input.find((item) => {
            if (item.name === "Список БД 1С:") {
              response.data.forEach(element => {
                item.items.push({NAME: element})
              });
            }
          })
        );
    },

    getOrg() {
      axios
        .get("./ajax/GetOrg.php", {})
        .then((response) => this.findItemArray("Организация", response));
    },

    checkArr(arr) {
      return arr.value != "";
    },

    checkData() {
      let tempArr = this.input.filter((test) => test.id > 1);
      let sts = tempArr.every(this.checkArr);
      if (sts) {
        this.formSend();
      } else {
        this.checkInput();
      }
    },

    findItemArray(elementName, response) {
      this.input.find((item) => {
        if (item.name === elementName) {
          item.items = response.data;
        }
      });
    },

    formSend() {
      console.log(this.input);
      this.btnLoader = true;
      if (this.input) {
        axios({
          method: "post",
          headers: { "Content-Type": "multipart/form-data" },
          url: this.source,
          data: {
            type: "1c001",
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
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка: " + error;
            this.btnLoader = false;
          });
      }
    },
  },
  computed: {
    getValueSwitch() {
      const inputSwitch = this.input.find((item) => item.type === "switch");
      return inputSwitch.value;
    },
  },
  watch: {
    getValueSwitch(newValue) {
      const inputTimeInterval = this.input.find(
        (item) => item.title === "timeInterwall"
      );
      inputTimeInterval.visible = newValue;
    },
  },
};
</script>
