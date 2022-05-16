<template>
  <v-container fluid>
    <DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage" />
    <TitleService />
    <v-card min-height="800px" class="py-12">
      <v-row>
        <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
          <RqCardTitle
            :title="$router.currentRoute.name"
            :sub_message="sub_message"
          ></RqCardTitle>
          <hr />
          <Input :arrInput="input" />
          <hr />
          <v-toolbar flat>
            <v-toolbar-title>Агрономические параметры</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <div>
              <v-btn
                color="primary"
                elevation="2"
                :disabled="disableBtn"
                class="mb-2"
                small
                @click="dialogParam = true"
                >Добавить параметры</v-btn
              >
            </div>
          </v-toolbar>
          <v-divider></v-divider>
          <v-dialog v-model="dialogParam" max-width="500px">
            <v-card>
              <v-card-title>
                <span class="headline">Новый параметр</span>
              </v-card-title>
              <v-divider />
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="12">
                      <v-text-field
                        v-model="editedItem.name"
                        label="Наименование"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="editedItem.amount"
                        label="Количество шт."
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="editedItem.dv"
                        label="ДВ"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="editedItem.phase"
                        label="Фаза внесения"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="12">
                      <v-text-field
                        v-model="editedItem.price"
                        label="Расход и стоимость л/кг на га"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">
                  Отмена
                </v-btn>
                <v-btn color="blue darken-1" text @click="save">
                  Сохранить
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogDelete" max-width="650px">
            <v-card>
              <v-card-title class="headline"
                >Вы действитель хотите удалить данный параметр?</v-card-title
              >
              <v-divider />
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDelete"
                  >Нет</v-btn
                >
                <v-btn color="blue darken-1" text @click="deleteItemConfirm"
                  >Да</v-btn
                >
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-data-table
            :headers="headers"
            :items="params"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.actions`]="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">
                mdi-pencil
              </v-icon>
              <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
            </template></v-data-table
          >
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
    </v-card>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import RqCardTitle from "@/components/RqCardTitle.vue";
import DialogAfterSendFrom from "@/components/DialogAfterSendForm.vue";
import Input from "@/components/Input.vue";
import axios from "axios";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    RqCardTitle,
    DialogAfterSendFrom,
    Input,
    TitleService,
  },
  data: () => ({
    sub_message:
      "Для создания заявки на опыт, инициатор должен обязательно прикрепить скан приказа на проведение опыта.",
    dialog: false,
    dialogMessage: "",
    btnLoader: false,
    btnStatus: false,
    input: [
      {
        name: "Название организации:",
        label: "Пример: ПАО Родина",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Категория:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        select_arr: [
          "Агротехнологии",
          "СЗР",
          "Биологические СЗР",
          "Минеральные удобрения",
          "Стимуляторы",
          "Регуляторы роста растений",
          "Семена",
          "Инновационные разработки",
        ],
        type: "select",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Контактное лицо:",
        label: "Пример: Кисс Николай Николаевич",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Контактная информация:",
        label:
          "Пример: Телефон: +7901001020, Электронная почта: agro@example.com",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Тема опыта:",
        labbel: "Пример: тестирование нового препарата",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Цели опыта:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Задачи опыта:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Объект опыта:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Экономическая эффективность: ",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Реквизиты организации:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        name: "Файл:",
        cs: "12",
        sm: "6",
        md: "6",
        type: "file",
        outlined: true,
        dense: true,
        solo: true,
      },
    ],
    headers: [
      { text: "Наименование", value: "name", sortable: false },
      { text: "Кол-во шт.", value: "amount", sortable: false },
      { text: "ДВ", value: "dv", sortable: false },
      { text: "Фаза внесения", value: "phase", sortable: false },
      {
        text: "Расход и стоимость л/кг на га",
        value: "price",
        sortable: false,
      },
      { text: "Действия", value: "actions", sortable: false },
    ],
    editedItem: {
      name: "",
      amount: "",
      dv: "",
      phase: "",
      price: "",
    },
    file: [],
    params: [],
    disableBtn: false,
    dialogParam: false,
    dialogDelete: false,
    editedIndex: -1,
    source: "./ajax/ajax_op001.php",
  }),
  created() {
    bus.$on("inputFile", (data) => {
      this.file = data;
    });
  },
  methods: {
    editItem(item) {
      this.editedIndex = this.params.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogParam = true;
    },

    deleteItem(item) {
      this.editedIndex = this.params.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.params.splice(this.editedIndex, 1);
      this.disableBtn = false;
      this.closeDelete();
    },

    close() {
      this.dialogParam = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.params[this.editedIndex], this.editedItem);
      } else {
        this.params.push(this.editedItem);
      }
      this.disableBtn = true;
      this.close();
    },
    formCancl() {
      this.$router.go(-1);
    },
    formSend() {
      this.btnLoader = true;
      if (this.input) {
        this.input = this.input.concat(this.params);

        //formData объект для отправки файлов и других данных на сервер
        var formData = new FormData();
        for (var i = 0; i < this.file.length; i++) {
          let file = this.file[i];
          formData.append("file[" + i + "]", file);
        }

        //Добавляем в объект fromData массив input
        formData.append("input", JSON.stringify(this.input));
        formData.append("params", JSON.stringify(this.params));
        axios({
          method: "post",
          headers: { "Content-Type": "multipart/form-data" },
          url: this.source,
          data: formData,
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
    },
  },
};
</script>

<style>
</style>