<template>
  <v-container fluid>
    <DialogAfterSendFrom
      :dialog="dialog"
      :warnMessage="dialogMessage"
      :route="route"
      :titleDialogParam="titleDialog"
      :alertMessage="alertMessage"
      :alertMessageType="alertMessageType"
      :alertMessageOutlined="alertMessageOutlined"
    />
    <TitleService />
    <v-card min-height="800px" class="py-12">
      <v-row>
        <v-card max-width="65%" raised class="mx-auto" color="grey lighten-4">
          <RqCardTitle :title="title" :sub_message="sub_message"></RqCardTitle>
          <hr />
          <InputCard
            v-for="item in inputs"
            :key="item.id"
            :id="item.id"
            :title="item.title"
            :input_err="item.err"
            :rules="item.rules"
            :label="item.label"
            :icon_in_rt="item.icon"
            :cols_title="item.cols_title"
            :cols_input="item.cols_input"
          ></InputCard>
          <v-row class="my-n4">
            <v-col cols="4">
              <v-card-text class="subtitle-1 text-right pt-2">
                Внешний сотрудник:
              </v-card-text>
            </v-col>
            <v-col cols="4">
              <v-switch
                v-model="out_user"
                @change="chngSwitch()"
                inset
                class="mt-1"
                label="Доступ для внешнего сотрудника"
              ></v-switch>
            </v-col>
          </v-row>
          <v-expand-transition>
            <v-card
              v-if="out_user"
              outlined
              max-width="90%"
              class="mx-auto"
              elevation="1"
            >
              <v-card-subtitle>
                <b>Настройки временного доступа</b>
                <v-divider />
              </v-card-subtitle>
              <Input :arrInput="input" />
            </v-card>
          </v-expand-transition>
          <SelectOrg :cols_title="4" :cols_input="6" :org_err="org_err" />
          <v-expand-transition>
            <Select
              v-if="
                org_name == 'АО Агрохолдинг «СТЕПЬ»' ||
                org_name == 'ООО «Торговый Дом Агрохолдинг «СТЕПЬ»' ||
                org_name == 'ООО «Техюгснаб»'
              "
              title="Департамент:"
              icon="mdi-account-group"
              label="Начните набирать название департамента(отдела)"
              :cols_title="4"
              :cols_input="6"
              source="./ajax/ajax_nu001.php"
              :param="org_name"
              :error-messages="dep_err"
            />
          </v-expand-transition>
          <SelectUsr
            :cols_title="4"
            :cols_input="6"
            title="ФИО руководителя*:"
            :userId_err="mng_err"
          />
          <v-row class="mb-n6">
            <v-col cols="4">
              <v-card-text class="subtitle-1 text-right pt-2">
                Контактный телефон*:
              </v-card-text>
            </v-col>
            <v-col cols="3">
              <v-text-field
                @input="tel_err = ''"
                type="tel"
                v-model="tel"
                outlined
                solo
                dense
                :rules="[this.inputRule]"
                :error-messages="tel_err"
                label="+79008500254"
                append-icon="mdi-cellphone"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row class="mb-n6">
            <v-col cols="4">
              <v-card-text class="subtitle-1 text-right pt-2">
                Дата выхода*:
              </v-card-text>
            </v-col>
            <v-col cols="3">
              <v-menu
                ref="menu1"
                v-model="date1"
                :close-on-content-click="false"
                offset-x
                offset-y
                transition="scale-transition"
              >
                <template v-slot:activator="{}">
                  <v-text-field
                    outlined
                    solo
                    type="date"
                    dense
                    v-model="date_start"
                    :rules="rules"
                    label="Дата"
                    :error-messages="date_start_err"
                  ></v-text-field>
                </template>
                <v-date-picker
                  locale="ru"
                  v-model="date_start"
                  no-title
                  @input="(date1 = false), (date_start_err = '')"
                ></v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
          <v-row class="mb-n4">
            <v-col cols="4">
              <v-card-text class="subtitle-1 text-right pt-2">
                Дата рождения*:
              </v-card-text>
            </v-col>
            <v-col cols="3">
              <v-menu
                ref="menu2"
                v-model="date2"
                :close-on-content-click="false"
                offset-x
                offset-y
                transition="scale-transition"
              >
                <template v-slot:activator="{}">
                  <v-text-field
                    outlined
                    solo
                    dense
                    v-model="date_burn"
                    :rules="rules"
                    type="date"
                    label="Дата"
                    :error-messages="date_burn_err"
                  ></v-text-field>
                </template>
                <v-date-picker
                  locale="ru"
                  v-model="date_burn"
                  no-title
                  @input="(date2 = false), (date_burn_err = '')"
                ></v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
          <v-row class="mb-n4">
            <v-col cols="4">
              <v-card-text class="subtitle-1 text-right pt-2">
                Дополнительные сведения:
              </v-card-text>
            </v-col>
            <v-col cols="6">
              <v-textarea
                v-model="cmnt"
                outlined
                solo
                label="Для указания дополнительной информации используйте это поле"
              ></v-textarea>
            </v-col>
          </v-row>
          <v-row class="mb-n2 mt-n10">
            <v-col cols="4">
              <v-card-text class="subtitle-1 text-right my-auto">
                Рабочее место:
              </v-card-text>
            </v-col>
            <v-col cols="8">
              <v-switch
                v-model="buyWorkspace"
                inset
                label="Закупка оборудования для рабочего места"
              ></v-switch>
            </v-col>
          </v-row>
          <v-row class="mb-4">
            <v-expand-transition>
              <v-card
                v-if="buyWorkspace"
                outlined
                width="90%"
                class="mx-auto"
                elevation="1"
              >
                <v-card-subtitle>
                  <b>Рабочее место</b>
                  <v-divider />
                  <v-row class="mt-2">
                    <v-col cols="4">
                      <v-card-text class="subtitle-1 text-right pt-2">
                        Описание рабочего места:
                      </v-card-text>
                    </v-col>
                    <v-col cols="6">
                      <v-textarea
                        v-model="workspace"
                        outlined
                        solo
                        label="Опишите что нужно приобрести или установить, также укажите дату когда нужно чтобы место было установлено"
                      ></v-textarea>
                    </v-col>
                  </v-row>
                </v-card-subtitle>
              </v-card>
            </v-expand-transition>
          </v-row>
          <v-card-text> * Поля обязательные для заполнения </v-card-text>
          <hr />
          <v-card-actions class="py-4">
            <div class="mx-auto">
              <v-btn
                class="mx-1"
                :loading="btnLoader"
                :disabled="sendButtonDisable"
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
import DialogAfterSendFrom from "@/components/DialogAfterSendForm.vue";
import RqCardTitle from "@/components/RqCardTitle";
import SelectOrg from "@/components/SelectOrg.vue";
import SelectUsr from "@/components/SelectUsr.vue";
import Select from "@/components/Select";
import InputCard from "@/components/InputCard.vue";
import Input from "@/components/Input.vue";
import axios from "axios";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    DialogAfterSendFrom,
    RqCardTitle,
    SelectOrg,
    SelectUsr,
    Select,
    InputCard,
    Input,
    TitleService,
  },
  data: () => ({
    title: "Заявка на нового сотрудника",
    sub_message:
      'Данная заявка в первую очередь согласовывается сотрудниками "Департамента управления персоналом", если выбрана опция "Внешний сотрудник", то заявка дополнительно будет отправлена на согласование директору по информационной безопасности и директору департамента ИТ также вы сможете отслеживать статус заявки в разделе',
    dialog: false,
    dialogMessage: "",
    alertMessage: "",
    alertMessageType: "",
    alertMessageOutlined: "",
    titleDialog: "",
    arr: [],
    inputs: [
      {
        id: "1",
        title: "ФИО*:",
        err: "",
        label: "Например: Крючков Иван Петрович",
        value: null,
        icon: "mdi-account",
        rules: [(value) => !!value || "Обязательное поле"],
        cols_title: "4",
        cols_input: "6",
      },
      {
        id: "2",
        title: "Должность*:",
        err: "",
        label: "Например: Весовщик",
        value: null,
        icon: "mdi-briefcase-account",
        rules: [(value) => !!value || "Обязательное поле"],
        cols_title: "4",
        cols_input: "6",
      },
    ],
    input: [
      {
        id: 0,
        name: "Электронная почта (внешнего сотрудника)*:",
        label: "Например: user@contoso.com",
        icon: "mdi-email",
        value: "",
        cs: "12",
        sm: "4",
        md: "4",
        type: "string",
        typeDomInput: "email",
        rule: [(value) => !!value || "Обязательное поле"],
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },
      {
        id: 1,
        name: "Период временного доступа*:",
        value: "",
        cs: "12",
        sm: "4",
        md: "4",
        type: "date",
        outlined: true,
        rule: [(value) => !!value || "Обязательное поле"],
        dense: true,
        solo: true,
        range: true,
        err: "",
      },
    ],
    dep: "",
    dep_err: "",
    org_name: "",
    org_err: "",
    out_user: "",
    out_user_mail: "",
    out_user_err: "",
    mng: "",
    mng_err: "",
    tel: "",
    tel_err: "",
    date1: "",
    date2: "",
    date_start: "",
    date_start_err: "",
    date_burn: "",
    date_burn_err: "",
    btnLoader: false,
    buyWorkspace: false,
    obj: {},
    cmnt: "",
    workspace: "",
    route: "",
    sendButtonDisable: false,
    rules: [(value) => !!value || "Обязательное поле"],
  }),
  created() {
    bus.$on("selectOrg", (data) => {
      this.org_err = "";
      this.org_name = data.name;
    });
    bus.$on("resultArray", (data) => {
      this.input = data;
    });
    bus.$on("Select", (data) => {
      this.dep_err = "";
      this.dep = data;
    });
    bus.$on("SelectUsr", (data) => {
      this.mng_err = "";
      this.mng = data;
    });
    bus.$on("inputCard", (data) => {
      this.inputs[data.input_id - 1].value = data.value;
      this.inputs[data.input_id - 1].err = "";
    });
    bus.$on("chngSwitch", (data) => {
      this.dialog = data;
    });
  },
  computed: {
    checkUser() {
      return [this.date_burn, this.inputs[0].value];
    },
  },
  watch: {
    checkUser(val) {
      if (val[0] && val[1]) {
        this.checkUserInfo(val);
      }
    },
  },
  methods: {
    formCancl: function () {
      this.$router.go(-1);
    },
    chngSwitch() {
      if (this.out_user) {
        this.route = "1";
        this.dialog = true;
        this.dialogMessage =
          "Сотруднику будет предоставлен удаленный доступ, при этом заявка будет отправлена на дополнительное согласование";
      }
    },
    inputRule(value) {
      this.sendButtonDisable = true;
      if (
        value.length != 12 ||
        value == 0 ||
        value[0] != "+" ||
        value[1] != "7"
      ) {
        return "Неверный формат";
      }
      if (!Number.isInteger(Number(value))) {
        return "Недопустимый символ";
      } else {
        this.sendButtonDisable = false;
        return true;
      }
    },
    checkUserInfo(val) {
      this.sendButtonDisable = true;
      axios({
        method: "post",
        withCredentials: true,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        url: "./ajax/ajax_nu001.php",
        data: {
          type: "checkUser",
          array: val,
        },
      })
        .then((response) => {
          if (response.status == 200 && response.data) {
            this.dialog = true;
            this.titleDialog = "Внимание";
            this.alertMessageType = "warning";
            this.alertMessage =
              "Пользователь: " +
              response.data[0].NAME +
              " должен быть только один :)";
            this.alertMessageOutlined = true;
          } else {
            this.sendButtonDisable = false;
          }
        })
        .catch((error) => {
          this.dialog = true;
          this.dialogMessage = "Произошла ошибка: " + error;
          this.btnLoader = false;
        });
    },
    formSend() {
      if (
        this.inputs[0].value &&
        this.inputs[1].value &&
        this.org_name &&
        this.tel &&
        this.date_start &&
        this.date_burn
      ) {
        this.obj.fio = this.inputs[0].value;
        this.obj.wp = this.inputs[1].value;
        this.obj.out_user_mail = this.input[0].value;
        this.obj.out_user_date = this.input[1].value;
        this.obj.org = this.org_name;
        this.obj.dep = this.dep;
        this.obj.mng = this.mng;
        this.obj.tel = this.tel;
        this.obj.start = this.date_start;
        this.obj.burn = this.date_burn;
        this.obj.cmnt = this.cmnt;
        this.obj.workspace = this.workspace;
        this.btnLoader = true;
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url: "./ajax/ajax_nu001.php",
          data: {
            arr: this.obj,
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
            this.btnLoader = false;
          });
      }
      if (!this.inputs[0].value) {
        this.inputs[0].err = "Необходимо указать ФИО нового сотрудника";
      }
      if (!this.inputs[1].value) {
        this.inputs[1].err = "Необходимо указать должность нового сотрудника";
      }
      if (!this.org_name) {
        this.org_err = "Необходимо выбрать организацию";
      }
      if (!this.dep) {
        this.org_dep = "Необходимо указать департамент(отдел)";
      }
      if (!this.mng) {
        this.mng_err = "Необходимо выбрать руководителя";
      }
      if (!this.tel) {
        this.tel_err =
          "Необходимо указать контактный телефон нового сотрудника";
      }
      if (!this.date_start) {
        this.date_start_err = "Необходимо указать дату выхода на работу";
      }
      if (!this.date_burn) {
        this.date_burn_err = "Необходимо указать дату рождения";
      }
      if (this.out_user) {
        //this.input[0].err = "rerer"
        this.checkInput();
      }
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
  },
};
</script>
<style>
[type="date"] {
  background: url('data:image/svg+xml;utf-8,<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24"><path fill="rgba(0, 0, 0, 0.54)" d="M19,4H18V2H16V4H8V2H6V4H5C3.89,4 3,4.9 3,6V20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V6A2,2 0 0,0 19,4M19,20H5V10H19V20M19,8H5V6H19V8M12,13H17V18H12V13Z"></path></svg>')
    100% 50% no-repeat;
}
[type="date"]::-webkit-inner-spin-button {
  display: none;
}
[type="date"]::-webkit-calendar-picker-indicator {
  opacity: 0;
}
</style>