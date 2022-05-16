<template>
  <v-container fluid>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
    <v-dialog
        v-model="infoMessageTypeDialog"
        width="500"
        persistent
        >
        <v-card>
            <v-card-title
            class="headline red lighten-1"
            primary-title
            color="red"
            >
            Внимание!!!
            </v-card-title>

            <v-card-text class="subtitle-1 text-center mt-4" >
                {{ infoMessageText }}            
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                text
                @click="closeSoftModal()"
            >
                Понятно
            </v-btn>
            </v-card-actions>
        </v-card>
        </v-dialog>
        <TitleService />
    <v-card min-height="800px" class="py-12">
    <v-row>
      <v-card max-width="55%" raised class="mx-auto" color="grey lighten-4">
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
              Тип оборудования:
            </v-card-text>
          </v-col>
          <v-col cols="6">
            <v-select
              v-model="type"
              :items="items_type"
              @change="infoMessageType()"
              persistent-hint
              chips
              deletable-chips
              multiple
              solo
              outlined
              dense
              :error-messages="type_err"
            ></v-select>
          </v-col>
        </v-row>
        <v-row class="mb-n6">
          <v-col cols="4">
            <v-card-text class="subtitle-1 text-right pt-2">
              Комментарий:
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
    TitleService
  },
  data: () => ({
    title: "Заявка на приобретение техники/программного обеспечения",
    sub_message:
      "Заявка согласуется внутри департамента ИТ, при необходимости может быть дополнительно отправлена на согласование руководителям подразделений или компаний. Статус созданной заявки вы можете отслеживать в разделе ",
    dialogMessage: "",
    items_type: [
      "Стационарный компьютер",
      "Ноутбук",
      "Монитор",
      "МФУ",
      "Стационарный телефон",
      "Прочее оборудование и ПО",
    ],
    dialog: false,
    infoMessageTypeDialog: false,
    users: [],
    userId: "",
    type: "",
    cmnt: "",
    userId_err: "",
    type_err: "",
    infoMessageText: `При заказе программного обеспечения необходимо указывать для каких работ  будет использоваться ПО,
                                производственная или служебная необходимость, не является обоснованием для закупки.`,
    btnLoader: false,
  }),
  created() {
    bus.$on("SelectUsr", (data) => {
      this.userId = data;
    });
  },
  methods: {
    //Отправка формы
    formSend: function () {
      //Проверка полей тип
      if (this.type && this.userId) {
        this.btnLoader = true;
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url: "./ajax/ajax_rb001.php",
          data: {
            userId: this.userId,
            type: this.type,
            cmnt: this.cmnt,
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
      if (!this.type) {
        this.type_err = "Необходимо выбрать тип оборудование";
      }
      if (!this.userId) {
        this.userId_err = "Необходимо выбрать сотрудника";
      }
    },
    //Действие кнопки "назад"
    formCancl: function () {
      this.$router.go(-1);
    },
    //Закрыть предупреждение
        closeSoftModal: function(){
            this.infoMessageTypeDialog = false;
        },
    infoMessageType() {
      if (this.type == "Прочее оборудование и ПО") {
        this.infoMessageTypeDialog = true;
      }
    },
  },
};
</script>