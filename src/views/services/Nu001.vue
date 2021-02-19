<template>
  <v-container>
    <DialogAfterSendFrom
      :dialog="dialog"
      :warnMessage="dialogMessage"
      :route="route"
    />
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
          :label="item.label"
          :icon_in_rt="item.icon"
          :cols_title="item.cols_title"
          :cols_input="item.cols_input"
        ></InputCard>
        <v-row class="mb-n6 mt-n8">
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
          <v-card v-if="out_user" outlined max-width="90%" class="mx-auto" elevation="1">
              <v-card-subtitle>
                  Настройки временного доступа
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
          title="ФИО руководителя:"
          :userId_err="mng_err"
        />
        <v-row class="mb-n6">
          <v-col cols="4">
            <v-card-text class="subtitle-1 text-right pt-2">
              Контактный телефон:
            </v-card-text>
          </v-col>
          <v-col cols="3">
            <v-text-field
              @input="tel_err = ''"
              type="number"
              v-model="tel"
              outlined
              solo
              dense
              :error-messages="tel_err"
              label="Например: +79008500254"
              append-icon="mdi-cellphone"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row class="mb-n6">
          <v-col cols="4">
            <v-card-text class="subtitle-1 text-right pt-2">
              Дата выхода:
            </v-card-text>
          </v-col>
          <v-col cols="3">
            <v-menu
              ref="menu"
              v-model="date1"
              :close-on-content-click="false"
              transition="scale-transition"
              offset-y
              max-width="290px"
              min-width="290px"
            >
              <template v-slot:activator="{ on }">
                <v-text-field
                  outlined
                  solo
                  dense
                  v-model="date_start"
                  label="Дата"
                  append-icon="mdi-calendar-outline"
                  v-on="on"
                  :error-messages="date_start_err"
                ></v-text-field>
              </template>
              <v-date-picker
                locale="ru"
                v-model="date_start"
                no-title
                @input="(menu = false), (date_start_err = '')"
              ></v-date-picker>
            </v-menu>
          </v-col>
        </v-row>
        <v-row class="mb-n4">
          <v-col cols="4">
            <v-card-text class="subtitle-1 text-right pt-2">
              Дата рождения:
            </v-card-text>
          </v-col>
          <v-col cols="3">
            <v-menu
              ref="menu"
              v-model="date2"
              :close-on-content-click="false"
              transition="scale-transition"
              offset-y
              max-width="290px"
              min-width="290px"
            >
              <template v-slot:activator="{ on }">
                <v-text-field
                  outlined
                  solo
                  dense
                  v-model="date_burn"
                  label="Дата"
                  append-icon="mdi-calendar-outline"
                  v-on="on"
                  :error-messages="date_burn_err"
                ></v-text-field>
              </template>
              <v-date-picker
                locale="ru"
                v-model="date_burn"
                no-title
                @input="(menu = false), (date_burn_err = '')"
              ></v-date-picker>
            </v-menu>
          </v-col>
        </v-row>
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
    </v-row>
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
export default {
  components: {
    DialogAfterSendFrom,
    RqCardTitle,
    SelectOrg,
    SelectUsr,
    Select,
    InputCard,
    Input,
  },
  data: () => ({
    title: "Заявка на нового сотрудника",
    sub_message:
      'Данная заявка в первую очередь согласовывается сотрудниками "Департамента управления персоналом", если выбрана опция "Внешний сотрудник", то заявка дополнительно будет отправлена на согласование директору по информационной безопасности и директору департамента ИТ также вы сможете отслеживать статус заявки в разделе',
    dialog: false,
    dialogMessage: "",
    arr: [],
    inputs: [
      {
        id: "1",
        title: "ФИО:",
        err: "",
        label: "Например: Крючков Иван Петрович",
        value: null,
        icon: "mdi-account",
        cols_title: "4",
        cols_input: "6",
      },
      {
        id: "2",
        title: "Должность:",
        err: "",
        label: "Например: Весовщик",
        value: null,
        icon: "mdi-briefcase-account",
        cols_title: "4",
        cols_input: "6",
      },
    ],
    input: [
      {
        id: 0,
        name: "Электронная почта (внешнего сотрудника):",
        label: "Например: user@contoso.com",
        icon: "mdi-email",
        value: [],
        cs: "12",
        sm: "4",
        md: "4",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },
      {
        id: 1,
        name: "Период временного доступа:",
        value: [],
        cs: "12",
        sm: "4",
        md: "4",
        type: "date",
        outlined: true,
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
    obj: {},
    route: "",
  }),
  created() {
    bus.$on("selectOrg", (data) => {
      this.org_err = "";
      this.org_name = data.name;
    });
    bus.$on("resultArray", (data) => {
      this.input = data;
      console.log(data);
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
            console.log(error);
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка";
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