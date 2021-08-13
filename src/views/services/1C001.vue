<template>
  <v-container fluid>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
    <TitleService icon="./assets/img/1s.png" />
    <v-card min-height="800px" class="py-12">
      <v-row>
        <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
          <RqCardTitle
            :title="$router.currentRoute.name"
            :sub_message="sub_message"
          ></RqCardTitle>
          <hr />
          <SelectUsr
            :cols_title="5"
            :cols_input="6"
            title="ФИО сотрудника:"
            :id="6"
            :userId_err="input[6].err"
          />
          <v-card outlined class="my-6 mx-6" color="grey lighten-2">
            <v-card-title class="mx-auto">
              Куда предоставляется доступ
            </v-card-title>
            <hr />
            <SelectOrg
              :cols_title="5"
              :cols_input="6"
              :org_err="input[7].err"
            />
            <Input :arrInput="input" />
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
      </v-row>
    </v-card>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import SelectUsr from "@/components/SelectUsr.vue";
import SelectOrg from "@/components/SelectOrg.vue";
import RqCardTitle from "@/components/RqCardTitle.vue";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import Input from "@/components/Input.vue";
import axios from "axios";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    SelectUsr,
    SelectOrg,
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
        id: 0,
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
        id: 1,
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
        name: "Список БД 1С:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "select",
        select_arr: [],
        selectDB: [],
        outlined: true,
        dense: true,
        solo: true,
        multiple: true,
        err: "",
      },
      {
        id: 3,
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
        name: "ФИО",
        value: "",
        visible: false,
        err: "",
      },
      {
        id: 8,
        name: "Организация",
        value: "",
        visible: false,
        err: "",
      },
    ],
    cmnt: "",
    cmnt_err: "",
    db: "",
    file: [],
    dialog: false,
    dialogMessage: "",
    source: "./ajax/ajax_1c001.php",
  }),
  created() {
    bus.$on("resultArray", (data) => {
      this.input = data;
      this.input[1].visible = this.input[0].value;
    });
    bus.$on("selectOrg", (data) => {
      this.input[7].value = data.name;
      this.input[7].err = "";
    });
    bus.$on("SelectUsr", (data) => {
      if (data.input_id == "6") {
        this.input[6].err = "";
        this.input[6].value = "user_" + data.userId;
      }
      if (data.input_id == "3") {
        this.input[3].err = "";
        this.input[3].value = "user_" + data.userId;
      }
    });
  },
  mounted() {
    axios
      .get(this.source, {
        params: {
          type: "getDB",
        },
      })
      .then((response) => (this.input[2].select_arr = response.data));
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
    formSend() {
      this.btnLoader = true;
      if (this.input) {
        axios({
          method: "post",
          withCredentials: true,
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
    condition() {
      this.usrid;
      return (
        this.usrid == 1 ||
        this.usrid == 2318 ||
        this.usrid == 2416 ||
        this.usrid == 2385 ||
        this.usrid == 3371 ||
        this.usrid == 1940
      );
    },
  },
};
</script>
