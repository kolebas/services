<template>
  <v-container>
    <div class="text-center">
      <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
      <v-dialog v-model="cmpNameModal" width="500" persistent>
        <v-card>
          <v-card-title class="headline red lighten-1" primary-title>
            Внимание!!!
          </v-card-title>

          <v-card-text class="subtitle-1 text-center mt-4">
            {{ cmpNameModalMsg }}
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="closeCmpModal()">
              Понятно
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
    <v-row>
      <v-col cols="12">
        <v-card
          max-width="55%"
          raised
          class="mx-auto"
          color="grey lighten-4"
        >
          <RqCardTitle :title="title" :sub_message="sub_message"></RqCardTitle>
          <hr />
          <SelectUsr
            :cols_title="4"
            :cols_input="6"
            title="ФИО:"
            :userId_err="userId_err"
          />
          <v-row class="mb-n6">
            <v-col cols="4">
              <v-card-text class="subtitle-1 text-right pt-2">
                Выберите тип устройства:
              </v-card-text>
            </v-col>
            <v-col cols="6">
              <v-select
                @change="showCmpModal()"
                v-model="type"
                :items="items_type"
                label="Выберите тип устройства"
                solo
                outlined
                dense
                :error-messages="type_err"
                ><template v-slot:selection="{ item }">
                  <v-chip color="#bcedfc" close label @click:close="type = ''"
                    >{{ item }}
                  </v-chip>
                </template>
              </v-select>
            </v-col>
          </v-row>
          <v-row class="mb-n6" v-if="type == 'Служебный компьютер'">
            <v-col cols="4">
              <v-card-text class="subtitle-1 text-right pt-2">
                Имя компьютера:
              </v-card-text>
            </v-col>
            <v-col cols="6">
              <v-text-field
                v-model="cmpName"
                outlined
                solo
                dense
                :error-messages="cmpNameErr"
                label="Укажите имя служебного компьютера"
              ></v-text-field>
            </v-col>
          </v-row>
              <v-expand-transition>
              <v-card-text v-if="type">
              <v-icon color="blue darken-2"> mdi-information-outline</v-icon>
              {{ typeInfo }}</v-card-text
            >
            </v-expand-transition>
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
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import RqCardTitle from "@/components/RqCardTitle";
import SelectUsr from "@/components/SelectUsr";
import axios from "axios";
export default {
  components: {
    DialogAfterSendForm,
    RqCardTitle,
    SelectUsr,
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
    typeInfo: "",
    cmpNameModal: "",
    cmpNameModalMsg:
      "Для исполнения данной заявки: служебный компьютер должен находится в корпоративной сети, и после её исполнения необходимо выполнить перезагрузку",
    cmpName: "",
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
      if (
        (this.userId && this.type && this.type != "Служебный компьютер") ||
        (this.userId && this.type && this.cmpName)
      ) {
        this.btnLoader = true;
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url: "./ajax/ajax_ns001.php",
          data: {
            userId: this.userId,
            type: this.type,
            cmpname: this.cmpName,
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
          });
      }
      if (!this.userId) {
        this.userId_err = "Необходимо выбрать сотрудника";
      }
      if (!this.type) {
        this.type_err = "Необходимо выбрать тип устройства";
      }
      if (this.type == "Служебный компьютер") {
        if (!this.cmpName) {
          this.cmpNameErr = "Необходимо указать имя компьютера";
        }
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