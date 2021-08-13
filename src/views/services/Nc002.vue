<template>
  <v-container fluid>
    <TitleService />
    <v-card min-height="800px" class="py-12">
      <v-row>
        <DialogAfterSendForm
          :dialog="dialog"
          :warnMessage="dialogMessage"
          :subMessage="subDialogMessage"
        />
        <v-card max-width="65%" raised class="mx-auto" color="grey lighten-4">
          <RqCardTitle :title="title" :sub_message="subtitle"></RqCardTitle>
          <hr />
          <SelectOrg
            :cols_title="5"
            :cols_input="6"
            title="Организация:"
            :org_err="org_err"
          />
          <InputCard
            v-for="item in inputs"
            :key="item.id"
            :id="item.id"
            :title="item.title"
            :input_err="item.err"
            :label="item.label"
            :suffix="item.suffix"
            :cols_title="item.cols_title"
            :cols_input="item.cols_input"
          ></InputCard>
          <v-row class="mb-n6">
            <v-col cols="5">
              <v-card-text class="subtitle-1 text-right pt-2">{{
                fldNameText
              }}</v-card-text>
            </v-col>
            <v-col cols="6">
              <v-text-field
                v-model="fldName"
                readonly
                outlined
                solo
                dense
              ></v-text-field>
            </v-col>
          </v-row>
          <SelectUsr
            :cols_title="5"
            v-for="item in users"
            :multiple="item.multiple"
            :key="item.title"
            :id="item.id"
            :cols_input="6"
            :title="item.title"
            :userId_err="item.err"
          />
          <v-row class="mb-n6">
            <v-col cols="5">
              <v-card-text class="subtitle-1 text-right pt-2"
                >Описание каталога:</v-card-text
              >
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
          <hr />
          <v-card-actions class="py-4">
            <div class="mx-auto">
              <v-btn
                class="mx-1"
                :loading="btnLoader"
                color="green lighten-2 white--text"
                @click="formSend()"
                >Отправить</v-btn
              >
              <v-btn class="mx-1" @click="formCancl()">Отмена</v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-row>
    </v-card>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import axios from "axios";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import RqCardTitle from "@/components/RqCardTitle";
import SelectUsr from "@/components/SelectUsr";
import SelectOrg from "@/components/SelectOrg";
import InputCard from "@/components/InputCard";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    DialogAfterSendForm,
    RqCardTitle,
    SelectUsr,
    SelectOrg,
    InputCard,
    TitleService,
  },
  data: () => ({
    title: "Заявка на создание сетевого каталога",
    subtitle:
      'Данная услуга позволяет создать сетевой каталог (сетевую папку). Название каталога складывается из аббревиатуры организации, далее название департамента, затем название папки. Пример: «AHS-Бухгалтерия-Оперативный учет», где «AHS» – аббревиатура АО Агрохолдинг "СТЕПЬ", «Бухгалтерия» – наименование департамента, «Оперативный учет» - название папки. Также вы сможете отслеживать статус заявки в разделе',
    org_name: "",
    org_err: "",
    inputs: [
      {
        id: "1",
        title: "Название структурного подразделения:",
        err: "",
        label: "Например: Отдел кадров",
        value: "",
        cols_title: "5",
        cols_input: "6",
      },
      {
        id: "2",
        title: "Название каталога:",
        err: "",
        label: "Например: График отпусков",
        value: "",
        cols_title: "5",
        cols_input: "6",
      },
    ],
    users: [
      {
        id: 1,
        title: "Бизнес-владелец:",
        value: "",
        multiple: false,
        err: "",
      },
      {
        id: 2,
        title: "Пользователи с возможность записи:",
        value: "",
        multiple: true,
        err: "",
      },
      {
        id: 3,
        title: "Пользователи с возможность просмотра:",
        value: "",
        multiple: true,
        err: "",
      },
    ],
    fldName: "",
    fldNameText: "Название каталога для диска L:",
    btnLoader: false,
    cmnt: "",
    dialog: false,
    dialogMessage: "",
    subDialogMessage: "",
  }),
  created() {
    bus.$on("SelectUsr", (data) => {
      this.users[data.input_id - 1].value = data.userId;
      this.users[data.input_id - 1].err = "";
    });
    bus.$on("inputCard", (data) => {
      this.inputs[data.input_id - 1].value = data.value;
      this.inputs[data.input_id - 1].err = "";
      let dep = this.inputs[1].value
        ? "-" + this.inputs[1].value
        : this.inputs[1].value;
      this.fldName = this.org_name.rdt + "-" + this.inputs[0].value + dep;
    }),
      bus.$on("selectOrg", (data) => {
        this.org_err = "";
        this.org_name = data;
      });
  },
  methods: {
    //Показ предупреждения
    showSoftModal: function () {
      if (this.soft == "Консультант +") {
        this.softModal = true;
      }
    },
    //Закрыть предупреждение
    closeSoftModal: function () {
      this.softModal = false;
    },
    formSend: function () {
      //Проверка полей тип
      if (this.users[0].value && this.users[1].value) {
        this.btnLoader = true;
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url: "./ajax/ajax_nc.php",
          data: {
            type: "nc002",
            org: this.org_name.name,
            dep: this.inputs[0].value,
            fld_name: this.fldName,
            bp_usr: this.users[0].value,
            rw_usr_all: this.users[1].value,
            ro_usr_all: this.users[2].value,
            cmnt: this.cmnt,
          },
        })
          .then((response) => {
            if (response.status == 200) {
              this.dialog = true;
              this.dialogMessage =
                "Успешно. Номер вашей заявки: " + response.data;
              this.subDialogMessage =
                "Важно: после выполнения заявик необходимо выполнить перезагрузку";
              this.btnLoader = false;
            }
          })
          .catch((error) => {
            console.log(error);
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка";
          });
      }
      if (!this.users[0].value && !this.users[1].value) {
        this.users[0].err = "Необходимо выбрать сотрудника";
        this.users[1].err = "Необходимо выбрать сотрудника";
      }
      if (!this.org_name) {
        this.org_err = "Необходимо выбрать организацию";
      }
      if (!this.inputs[0].value) {
        this.inputs[0].err =
          "Необходимо указать название структурного подразделения";
      }
      if (!this.inputs[1].value) {
        this.inputs[1].err = "Необходимо указать название каталога";
      }
    },
    formCancl: function () {
      this.$router.go(-1);
    },
  },
};
</script>

<style>
</style>