<template>
  <v-container fluid>
    <v-card min-height="800px" class="py-2">
      <Alert v-if="!access" :text="accessText" />
      <v-row v-if="access">
        <v-col cols="12">
          <v-tabs>
            <v-tab
              v-for="tab in getTabs"
              :key="tab.text"
              @click="showData(tab.source, tab.param, tab.result_ar)"
              >{{ tab.text }}
            </v-tab>
            <v-tab-item>
              <v-divider />
              <v-container fluid>
                <v-btn
                  class="mr-2"
                  @click="
                    showTable(btn),
                      getData1C(btn.type, btn.sectionId),
                      getDialogFields(btn.tableHeaders)
                  "
                  v-for="btn in tabs[0].cards"
                  :key="btn.i"
                  ><v-icon left>{{ btn.icon }}</v-icon
                  >{{ btn.title }}</v-btn
                >
              </v-container>
              <v-container
                fluid
                v-for="table in tabs[0].cards.filter(
                  (getTable) => getTable.visible == true
                )"
                :key="table.i"
              >
                <DataTables
                  class="mx-auto"
                  :headers="getTableHeaders(table.tableHeaders)"
                  :item="table.tableItems"
                  :title="table.title"
                  :img="table.img"
                  :actions="table.actions"
                  :loading="loading"
                  :dialogTitle="'Добавление ' + table.title.toLowerCase()"
                  :dialogFields="fieldArray"
                  :dialogFieldsCols="3"
                  :dialogMaxWidth="table.dialogMaxWidth"
                />
              </v-container>
            </v-tab-item>
          </v-tabs>
        </v-col>
      </v-row>
    </v-card>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import axios from "axios";
import DataTables from "@/components/DataTables.vue";
import Alert from "@/components/Alert.vue";
export default {
  components: {
    DataTables,
    Alert,
  },
  data: () => ({
    name: "",
    accessText:
      "Доступ к данному разделу ограничен, для получения доступа вам необходимо обратиться к руководителю службы развития и поддержки ИТ-инфраструктуры",
    access: false,
    tabs: [
      {
        text: "Панель управления 1C",
        source: "./ajax/1c001.php",
        param: "req",
        aclGroup: true,
        cards: [
          {
            title: "Базы данных",
            sectionId: 877,
            dialogMaxWidth: "75%",
            subtitle: "Редактирование списка баз данных",
            visible: false,
            type: "getDB",
            tableHeaders: [
              {
                text: "ID",
                value: "NAME",
                visibleInTable: false,
                type: "string",
              },
              {
                text: "название ИБ",
                value: "NAME",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "вид базы",
                value: "VID",
                visibleInTable: true,
                type: "select",
                select_arr: ["архивная", "копия", "рабочая"],
              },
              {
                text: "тип базы",
                value: "TYPE",
                visibleInTable: true,
                type: "select",
                select_arr: ["серверная", "веб-сервер", "файловая"],
              },
              {
                text: "кластер серверов",
                value: "CLUSTER",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "Aдрес ИБ",
                value: "ADDRESS_DB",
                visibleInTable: true,
                type: "string",
              },

              {
                text: "каталог ИБ",
                value: "KATALOG_IB",
                visibleInTable: false,
                type: "string",
              },
              {
                text: "пользователь ",
                value: "USER",
                visibleInTable: false,
                type: "string",
              },
              {
                text: "логин ",
                value: "LOGIN",
                visibleInTable: false,
                type: "string",
              },
              {
                text: "конфигурация",
                value: "CONFIG",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "релиз",
                value: "RELEASE",
                visibleInTable: false,
                type: "string",
              },
              {
                text: "лицензия ",
                value: "LICENSE",
                visibleInTable: false,
                valueField: "",
                type: "select",
                select_arr: [],
              },
              {
                text: "комментарий ",
                value: "COMMENT",
                visibleInTable: false,
                type: "textarea",
              },
              {
                text: "Действия",
                value: "ACTIONS",
                width: "10%",
                visibleInTable: true,
              },
            ],
            actions: ["chg", "rem"],
            tableItems: [],
            img: "https://portal.ahstep.ru/upload/resize_cache/iblock/20a/36_30_1/db.png",
            icon: "mdi-database-cog-outline",
          },
          {
            title: "Лицензии",
            sectionId: 875,
            icon: "mdi-text-box-check-outline",
            visible: false,
            type: "getDB",
            tableHeaders: [
              {
                text: "ID",
                value: "NAME",
                visibleInTable: false,
                type: "string",
              },
              {
                text: "название лицензии",
                value: "NAME",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "тип лицензии",
                value: "TYPE",
                visibleInTable: true,
                type: "select",
                select_arr: ["проф", "базовая"],
              },
              {
                text: "вид лицензии",
                value: "VID",
                visibleInTable: true,
                type: "select",
                select_arr: [
                  "основная поставка",
                  "пользовательская",
                  "отраслевая",
                ],
              },
              {
                text: "основная лицензия",
                value: "LICENSE",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "количество рабочих мест",
                value: "NUMBER",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "юр.лицо - владелец",
                value: "ORG",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "дата приобретения",
                value: "DATE_BUY",
                visibleInTable: true,
                type: "date",
              },
              {
                text: "место активации",
                value: "LOCATION",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "действующий договор ИТС",
                value: "CONTRACT",
                visibleInTable: true,
                type: "select",
                select_arr: [],
              },
              {
                text: "Действия",
                value: "ACTIONS",
                width: "10%",
                visibleInTable: true,
              },
            ],
            actions: ["chg", "rem"],
            tableItems: [],
            img: "https://img.icons8.com/office/30/000000/diploma.png",
          },
          {
            title: "Договора ИТС",
            sectionId: 876,
            icon: "mdi-file-document-edit-outline",
            visible: false,
            type: "getDB",
            tableHeaders: [
              {
                text: "ID",
                value: "NAME",
                visibleInTable: false,
                type: "string",
              },
              {
                text: "название договора",
                value: "NAME",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "юр лицо",
                value: "ORG",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "контрагент",
                value: "AGENT",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "тип договора",
                value: "DOGOVOR_TYPE",
                visibleInTable: true,
                type: "select",
                select_arr: ["основной", "доп. соглашение"],
              },
              {
                text: "основной договор",
                value: "DOGOVOR",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "юр.лицо - владелец",
                value: "VLADELEC",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "дата начала",
                value: "DATE_START",
                visibleInTable: true,
                type: "date",
              },
              {
                text: "дата окончания",
                value: "DATE_END",
                visibleInTable: true,
                type: "date",
              },
              {
                text: "Действия",
                value: "ACTIONS",
                width: "10%",
                visibleInTable: true,
              },
            ],
            actions: ["chg", "rem"],
            tableItems: [],
            img: "https://img.icons8.com/office/30/000000/bill.png",
          },
          {
            title: "Пользователи баз 1С",
            sectionId: null,
            icon: "mdi-account-multiple-outline",
            visible: false,
            type: "getUsers",
            tableHeaders: [
              {
                text: "ID",
                value: "ID",
                visibleInTable: false,
                type: "string",
              },
              {
                text: "Имя пользователя",
                value: "USER",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "Дата подключения",
                value: "DATE_CONNECT",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "Задача на подключениe",
                value: "TASK_CONNECT",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "Дата отключения",
                value: "DATE_DISCONNECT",
                visibleInTable: true,
                type: "string",
              },
              {
                text: "Задача на отключение",
                value: "TASK_DISCONNECT",
                visibleInTable: true,
                type: "string",
              },
            ],
            tableItems: [],
            img: "https://img.icons8.com/office/30/000000/groups.png",
          },
        ],
      },
    ],
    loading: false,
    table_name: "Dashboard",
    item: [],
    search: "",
    dialog: false,
    name_err: "",
    dialogMessage: "",
    progValue: "0",
    indeterminate: true,
    value: "0",
    glpi_data: [],
    fieldArray: [],
  }),
  created() {
    bus.$on("newItem", (data) => {
      if (data[1].name == "название ИБ") {
        this.addData1C(data, "addDB");
      }
      if (data[1].name == "название лицензии") {
        this.addData1C(data, "addLic");
      }
      if (data[2].name == "юр лицо") {
        this.addData1C(data, "addContract");
      }
    });
    bus.$on("remItem", (data) => {
      this.addData1C(data, "remDB");
    });
    bus.$on("updItem", (data) => {
      this.addData1C(data, "updDB");
    });
  },
  computed: {
    getTabs() {
      return this.tabs.filter((getTab) => getTab.aclGroup == true);
    },
  },
  methods: {
    getTableHeaders(headers) {
      if (headers != "undefined") {
        return headers.filter(
          (getHeaders) => getHeaders.visibleInTable == true
        );
      }
    },
    getDialogFields(inputArray) {
      inputArray = inputArray.filter((el) => el.text !== "Действия");
      var fieldArray = [];
      if (inputArray.length > 0) {
        for (let i = 0; i < inputArray.length; i++) {
          var obj = Object.assign(
            { name: inputArray[i].text },
            { type: inputArray[i].type },
            { select_arr: inputArray[i].select_arr },
            { value: null }
          );
          fieldArray.push(Object.assign(obj));
        }
        this.fieldArray = fieldArray;
      }
    },
    formCancl: function () {
      this.$router.go(-1);
    },
    getData1C(type, sectionId) {
      this.loading = true;
      axios
        .get("./ajax/ajax_1c001.php", {
          headers: {
            "Content-Type": "application/json; charset=utf-8",
          },
          params: {
            type: type,
            sectionId: sectionId,
          },
        })
        .then((response) => {
          if (sectionId == 877) {
            this.tabs[0].cards[0].tableItems = response.data;
            this.getData1C("getDB", 875);
          }
          if (sectionId == 875) {
            this.getData1C("getDB", 876);
            this.tabs[0].cards[1].tableItems = response.data;
            for (let i = 0; i < response.data.length; i++) {
              this.tabs[0].cards[0].tableHeaders[11].select_arr.push(
                response.data[i].NAME
              );
            }
          }
          if (sectionId == 876) {
            this.tabs[0].cards[2].tableItems = response.data;
            let selectArr = this.tabs[0].cards[1].tableHeaders.filter(
              (el) => el.value == "CONTRACT"
            );
            for (let i = 0; i < response.data.length; i++) {
              selectArr[0].select_arr.push(response.data[i].NAME);
            }
          }
          if (type == "getUsers") {
            this.tabs[0].cards[3].tableItems = response.data;
            this.getTasksAdd1C();
          }
          setTimeout((this.loading = false), 1000);
        });
    },
    addData1C(item, type) {
      axios({
        method: "post",
        headers: { "Content-Type": "multipart/form-data" },
        url: "./ajax/ajax_1c001.php",
        data: {
          type: type,
          item: item,
        },
      }).then((response) => {
        if (response.status == 200) {
          this.getData1C("getDB", 877);
        }
      });
    },
    showTable(item) {
      for (let i = 0; i < this.tabs[0].cards.length; i++) {
        this.tabs[0].cards[i].visible = false;
      }
      item.visible = !item.visible;
    },
    showPreview(id) {
      this.dialog = true;
      this.loading = false;
      if (id == 1) {
        this.getPreviewTask = this.item.filter((getDate) =>
          getDate.DATE.includes(this.today)
        );
      }
      if (id == 2) {
        this.getPreviewTask = this.getNewInc;
      }
      if (id == 3) {
        this.getPreviewTask = this.getIncNotApproved;
      }
    },
    close() {
      setTimeout((this.dialog = false), 1000);
    },
    getUserGroup() {
      axios.get("./ajax/ajax_usr.php", {}).then((response) => {
        if (response.status == 200) {
          let groupArray = response.data[0]["GROUP"];
          if (groupArray.includes("48") == true) {
            this.access = true;
          }
        }
      });
    },
    getTasksAdd1C() {
      const regex = /Задача[\W]№[\w]{0,6}/g;
      let m;
      for (let i = 0; i < this.tabs[0].cards[3].tableItems.length; i++) {
        let str = this.tabs[0].cards[3].tableItems[i].TASK_CONNECT;
        while ((m = regex.exec(str)) !== null) {
          // This is necessary to avoid infinite loops with zero-width matches
          if (m.index === regex.lastIndex) {
            regex.lastIndex++;
          }
          // The result can be accessed through the `m`-variable.
          m.forEach((match) => {
            this.tabs[0].cards[3].tableItems[i].TASK_CONNECT = `${match}`;
          });
        }
      }
    },
  },
  mounted() {
    this.getUserGroup();
  },
};
</script>
