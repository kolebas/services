<template>
  <v-container fluid>
    <div class="text-center">
      <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
    </div>
    <TitleService />
    <v-card min-height="800px" class="py-12">
    <v-row>
      <v-col cols="12">
        <v-card max-width="55%" raised class="mx-auto" color="grey lighten-4">
          <RqCardTitle :title="title" :sub_message="sub_message"></RqCardTitle>
          <hr />
          <SelectUsr
            :cols_title="4"
            :cols_input="6"
            title="ФИО:"
            :userId_err="userId_err"
          />

          <v-container>
            <v-alert
              outlined
              type="info"
              icon="mdi-information-outline"
              dense
              prominent
              border="left"
              >{{ typeInfo }}
            </v-alert>
          </v-container>
          <hr />
          <v-card-actions class="py-4">
            <div class="mx-auto">
              <v-btn
                class="mx-1"
                :loading="btnLoader"
                color="green lighten-2 white--text"
                @click="formSend()"
              >
                Отправить
              </v-btn>
              <v-btn class="mx-1" @click="formCancl()"> Отмена </v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    </v-card>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import RqCardTitle from "@/components/RqCardTitle";
import SelectUsr from "@/components/SelectUsr";
import axios from "axios";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    DialogAfterSendForm,
    RqCardTitle,
    SelectUsr,
    TitleService,
  },
  data: () => ({
    title: "Удаленный доступ (VPN)",
    sub_message:
      "Удаленный доступ (VPN) В рамках исполнения данной заявки вам будет предоставлен доступ к внутренним ресурсам компании. Статус созданной заявки вы можете отслеживать в разделе ",
    dialogMessage: "",
    items_type: [
      "Служебный компьютер",
      "Личный компьютер",
      "Мобильное устройство",
    ],
    dialog: false,
    users: [],
    userId: "",
    type: "",
    typeInfo:
      "После предоставления данной услуги вы сможете подключаться к корпоративным ресурсам компании с помощью мобильного устройства, домашнего или рабочего компьютера.",
    cmpNameErr: "",
    userId_err: "",
    type_err: "",
    btnLoader: false,
  }),
  created() {
    bus.$on("SelectUsr", (data) => {
      this.userId = data;
    });
  },
  methods: {
    //Показ предупреждения о Консультанте
    showCmpModal: function () {
      this.type_err = "";
      if (this.type == "Служебный компьютер") {
        this.cmpNameModal = true;
      }
    },
    closeCmpModal: function () {
      this.cmpNameModal = false;
    },
    //Отправка формы
    formSend: function () {
      //Проверка полей тип
      if (this.userId) {
        this.btnLoader = true;
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url: "./ajax/ajax_ns001.php",
          data: {
            userId: this.userId,
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
            this.dialogMessage = `Произошла ошибка: ${error}`;
          });
      }
      if (!this.userId) {
        this.userId_err = "Необходимо выбрать сотрудника";
      }
    },
    //Действие кнопки "назад"
    formCancl: function () {
      this.$router.go(-1);
    },
    //Действие кнопки "Мои заявки"
    btnToMyreq() {
      document.location.href = "/it-uslugi/helpdesk/my_ticket.php";
    },
    //Взаимодействие с диалогом
    funcDialog() {
      this.$router.go(-1);
    },
  },
  mounted() {
    axios
      .get("./ajax/ajax_user.php", {})
      .then((response) => (this.users = response.data));
  },
  watch: {
    type: function (val) {
      if (val == "Служебный компьютер") {
        this.typeInfo =
          "Служебный компьютер - позволяет подключаться  с доменных ПК и ноутбуков, доменными учетными записями посредством VPN (приложение Cisco Any Connect) из сети Internet.";
      }
      if (val == "Мобильное устройство") {
        this.typeInfo =
          "Мобильное устройств позволяет подключаться с мобильных устройств Android и IOS доменными учетными записями посредством VPN (приложение Cisco Any Connect)  из сети Internet по протоколу RDP (Удаленный рабочий стол).";
      }
      if (val == "Личный компьютер") {
        this.typeInfo =
          "Личный компьютер позволяет подключаться с личных ПК и ноутбуков, доменными учетными записями, либо через браузер Internet Explorer (Web VPN) посредством ресурса  vpn.ahstep.ru, либо посредством VPN (приложение Cisco Any Connect)  из сети Internet по протоколу RDP (Удаленный рабочий стол).";
      }
    },
  },
};
</script>