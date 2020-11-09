<template>
  <v-container class="headline grey lighten-3" primary-title>
    <v-row justify="center">
      <v-dialog v-model="dialog" persistent max-width="80%">
        <v-card>
          <v-card-title class="headline grey lighten-2" primary-title>
            <span class="headline">Добавление пуна</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col
                  :cols="item.cs"
                  :sm="item.sm"
                  :md="item.md"
                  v-for="item in arrPunAdd"
                  :key="item.name"
                >
                  <v-text-field
                    v-if="item.type == 'string'"
                    v-model="item.value"
                    :label="item.name"
                    required
                  ></v-text-field>
                  <v-menu
                    v-if="item.type == 'date'"
                    v-model="menu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="item.value"
                        :label="item.name"
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="item.value"
                      @input="menu = false"
                    ></v-date-picker>
                  </v-menu>
                  <v-select
                    v-if="item.type == 'select'"
                    v-model="item.value"
                    :items="[
                      'План АФК',
                      'План Степь',
                      'Внеплан АФК',
                      'Внеплан Степь',
                    ]"
                    :label="item.name"
                    required
                  ></v-select>
                </v-col>
              </v-row>
            </v-container>
            <small>{{ msgRequiredFiled }}</small>
          </v-card-text>
          <v-divider />
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="dialog = false"
              >Отмена</v-btn
            >
            <v-btn
              color="blue darken-1"
              text
              @click="
                dialog = false;
                saveNewPun();
              "
              >Сохранить</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="dialogSub" persistent max-width="80%">
        <v-card>
          <v-card-title class="headline grey lighten-2" primary-title>
            <span class="headline">Добавление пункта отчета</span>
          </v-card-title>
          <v-divider />
          <v-card-text>
            <v-container>
              <v-row>
                <v-col
                  v-for="item in arrPunItemAdd"
                  :key="item.name"
                  :cols="item.cs"
                  :sm="item.sm"
                  :md="item.md"
                >
                  <v-text-field
                    v-model="item.value"
                    :label="item.name"
                    required
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-container>
            <small>{{ msgRequiredFiled }}</small>
          </v-card-text>
          <v-divider />
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="dialogSub = false"
              >Отмена</v-btn
            >
            <v-btn
              color="blue darken-1"
              text
              @click="
                dialogSub = false;
                savePunItem();
              "
              >Сохранить</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
    <v-card>
      <v-card-title>
        {{ title }}
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Поиск"
          single-line
          hide-details
        ></v-text-field>
        <v-btn color="primary" @click="addPun()" dark class="mb-2 ml-2"
          >Добавить ПУН</v-btn
        >
      </v-card-title>
      <v-divider />
      <v-data-table
        :headers="headersPuns1"
        :items="itemPuns"
        :search="search"
        sortable
        :loading="loading"
        :expanded.sync="expanded"
        item-key="ID"
        show-expand
      >
        <template
          v-slot:expanded-item="{ headers, item }"
          @click="console.log('test')"
        >
          <td :colspan="headers.length">
            <v-card outlined color="#DCEDC8">
              <v-card-subtitle
                ><strong>{{ item.NAME }}</strong></v-card-subtitle
              >
              <v-data-table
                :headers="headersPuns2"
                :items="
                  itemPunsList.filter(
                    (getItem) => getItem.PROPERTY_722_VALUE == item.ID
                  )
                "
                hide-default-footer
                ><template v-slot:item.PROPERTY_744_VALUE="{ item }">
                  <v-chip v-if="item.PROPERTY_744_VALUE" small>{{
                    item.PROPERTY_744_VALUE
                  }}</v-chip>
                </template>
                <template v-slot:item.PROPERTY_723_VALUE="{ item }">
                  <v-chip
                    :href="
                      'https://portal.ahstep.ru/company/personal/user/1/tasks/task/view/' +
                      item.PROPERTY_723_VALUE +
                      '/'
                    "
                    v-if="item.PROPERTY_723_VALUE"
                    small
                    >{{ item.PROPERTY_723_VALUE }}</v-chip
                  >
                </template>
              </v-data-table>
            </v-card>
          </td>
        </template>
        <template v-slot:item.NAME="{ item }">
          <a href="#" class="text-decoration-none" @click="addPun()">{{
            item.NAME
          }}</a>
        </template>
        <template v-slot:item.PROPERTY_719_VALUE="{ item }">
          <v-chip small>{{ item.PROPERTY_719_VALUE }}</v-chip>
        </template>
        <template v-slot:item.ACTIONS="{ item }">
          <v-row>
            <v-col cols="12" md="3">
              <v-icon
                class="mr-2"
                @click="
                  dialogSub = true;
                  addPunItem(item.ID);
                "
                color="blue"
                >mdi-plus</v-icon
              >
            </v-col>
            <v-col cols="12" md="3">
              <v-icon class="mr-2" @click="editItem(item)" color="green"
                >mdi-pencil</v-icon
              >
            </v-col>
            <v-col cols="12" md="3">
              <v-icon @click="deletePun(item.ID)" color="red"
                >mdi-delete</v-icon
              >
            </v-col>
          </v-row>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script>
import axios from "axios";
export default {
  components: {},
  data: () => ({
    title: "Планы устранения недостатков",
    menu: "",
    arrayPuns: [],
    /*headersPuns1: [
      {
        text: "Год, квартал проведения проверки",
        value: "YEAR",
        align: "center",
        divider: true,
        width: "",
      },
      { text: "Название проверки", value: "NAME", align: "center" },
      { text: "Тип проверки, инициатор", value: "TYPE", align: "center" },
      { text: "Дата утверждения ПУН", value: "DATE", align: "center" },
      { text: "Комментарий", value: "CMNT", align: "center" },
      { text: "Положение", value: "STATUS", align: "center" },
      { text: "Действия", value: "ACTIONS", align: "center" },
    ],*/
    headersPuns1: [
      {
        text: "Год, квартал проведения проверки",
        value: "PROPERTY_717_VALUE",
        align: "center",
        divider: true,
        width: "",
      },
      { text: "Название проверки", value: "NAME", align: "center" },
      {
        text: "Тип проверки, инициатор",
        value: "PROPERTY_718_VALUE",
        align: "center",
      },
      {
        text: "Дата утверждения ПУН",
        value: "PROPERTY_719_VALUE",
        align: "center",
      },
      { text: "Комментарий", value: "PROPERTY_720_VALUE", align: "center" },
      { text: "Положение", value: "STATUS", align: "center" },
      { text: "Действия", value: "ACTIONS", align: "center" },
    ],
    /*headersPuns2: [
      { text: "Пункт отчета", value: "NAME" },
      { text: "Недостаток", value: "NEDOSTATOK" },
      { text: "Критерии контроля", value: "CRITERIA" },
      { text: "Причины недостатка", value: "REASONS" },
      { text: "Оценка последствий, млн.руб.", value: "RAITING" },
      { text: "Существенность", value: "MATERIALITY" },
      { text: "Мероприятие ПУН", value: "EVENTS" },
      { text: "Срок выполнения (Утвержденный)", value: "DATE_UTV" },
      { text: "Срок выполнения (Пересмотренный)", value: "DATE_PER" },
      { text: "Ответственный", value: "RESPONSIBLE" },
      { text: "Статус", value: "STATUS" },
      { text: "Задача", value: "TASK" },
    ],*/
    headersPuns2: [
      { text: "Пункт отчета", value: "NAME" },
      { text: "Недостаток", value: "PROPERTY_738_VALUE" },
      { text: "Критерии контроля", value: "PROPERTY_739_VALUE" },
      { text: "Причины недостатка", value: "PROPERTY_740_VALUE" },
      { text: "Оценка последствий, млн.руб.", value: "PROPERTY_741_VALUE" },
      { text: "Существенность", value: "PROPERTY_742_VALUE" },
      { text: "Мероприятие ПУН", value: "PROPERTY_743_VALUE" },
      { text: "Срок выполнения (Утвержденный)", value: "PROPERTY_744_VALUE" },
      { text: "Срок выполнения (Пересмотренный)", value: "PROPERTY_745_VALUE" },
      { text: "Ответственный", value: "PROPERTY_746_VALUE" },
      { text: "Статус", value: "PROPERTY_747_VALUE" },
      { text: "Задача", value: "PROPERTY_723_VALUE" },
    ],
    arrPunAdd: [
      {
        name: "Год, квартал проведения проверки**",
        value: "",
        cs: "12",
        sm: "6",
        md: "2",
        type: "string",
      },
      {
        name: "Название проверки*",
        value: "",
        cs: "12",
        sm: "6",
        md: "8",
        type: "string",
      },
      {
        name: "Тип проверки, инициатор*",
        value: "",
        cs: "12",
        sm: "6",
        md: "2",
        type: "select",
      },
      {
        name: "Дата утверждения ПУНа*",
        value: "",
        cs: "12",
        sm: "6",
        md: "2",
        type: "date",
      },
      {
        name: "Комментарий",
        value: "",
        cs: "12",
        sm: "6",
        md: "10",
        type: "string",
      },
    ],
    arrPunItemAdd: [
      { name: "Пункт отчета*", value: "", cs: "12", sm: "6", md: "2" },
      { name: "Недостаток*", value: [], cs: "12", sm: "6", md: "6" },
      { name: "Критерии контроля*", value: [], cs: "12", sm: "6", md: "4" },
      { name: "Причины недостатка*", value: [], cs: "12", sm: "6", md: "4" },
      {
        name: "Оценка последствий, млн.руб.*",
        value: "",
        cs: "12",
        sm: "6",
        md: "4",
      },
      { name: "Существенность*", value: "", cs: "12", sm: "6", md: "4" },
      { name: "Мероприятие ПУН*", value: "", cs: "12", sm: "6", md: "4" },
      {
        name: "Срок выполнения (Утвержденный)",
        value: "",
        cs: "12",
        sm: "6",
        md: "4",
      },
      {
        name: "Срок выполнения (Пересмотренный)",
        value: "",
        cs: "12",
        sm: "6",
        md: "4",
      },
      { name: "Ответственный", value: "", cs: "12", sm: "6", md: "4" },
      {
        name: "Комментарий",
        value: "",
        cs: "12",
        sm: "6",
        md: "10",
        type: "textarea",
      },
    ],
    itemPuns: [],
    punID: "",
    itemPunsList: [],
    search: "",
    loading: false,
    dialog: false,
    dialogSub: false,
    expanded: [],
    //source: "./ajax/dvka/ajax_pun.php",
    source: "https://portal.ahstep.ru/ahstep/services/ajax/dvka/ajax_pun.php",
    fab: "",
    msgRequiredFiled: "*поля обязательные для заполнения ",
    test: [],
  }),
  methods: {
    getData() {
      axios
        .get(this.source, {
          params: {
            type: "get",
          },

          auth: {
            username: "zaikin.ni",
            password: "Vbuhfwbz75",
          },
          headers: {
            "Content-Type": "application/json; charset=utf-8",
          },
        })
        .then((response) => {
          this.itemPuns = response.data.filter(
            (getItem) => getItem.PROPERTY_722_VALUE == null
          );
          this.itemPunsList = response.data.filter(
            (getItem) => getItem.PROPERTY_722_VALUE != null
          );
          console.log(this.itemPunsList);
        });
    },
    addPun() {
      this.dialog = true;
      console.log(this.arrPunAdd);
    },
    addPunItem(id) {
      this.punID = id;
    },
    savePunItem() {
      this.arrPunItemAdd[0].id = this.punID;
      this.sendData("NewItemPun", this.arrPunItemAdd);
      console.log(this.arrPunItemAdd);
    },
    deletePun(id) {
      this.sendData("DeletePun", id);
    },
    saveNewPun() {
      this.sendData("NewPun", this.arrPunAdd);
    },
    sendData(type, data) {
      axios({
        method: "post",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        url: this.source,
        /*auth: {
          username: "zaikin.ni",
          password: "Vbuhfwbz75",
        },*/
        data: {
          type: type,
          data: data,
        },
      }).then((response) => {
        if (response.status == 200) {
          this.getData();
        }
      });
    },
  },
  mounted() {
    this.getData();
    //this.getItemPuns();
  },
};
</script>

<style>
</style>