<template>
  <v-container>
    <Alert v-if="!access" :text="accessText" />
    <!-- <v-dialog v-model="dialog" transition="slide-x-reverse-transition">
      <v-data-table
        :headers="headers_inc"
        :items="getPreviewTask"
        :search="search"
        sortable
        :loading="loading"
      ></v-data-table>
    </v-dialog> -->
    <v-row v-if="access">
      <v-col cols="12">
        <v-tabs>
          <v-tab
            v-for="tab in getTabs"
            :key="tab.text"
            @click="showData(tab.source, tab.param, tab.result_ar)"
          >
            <!-- <v-badge
              color="green"
              :value="b_value"
              :content="tab.new_count"
              bordered
              >{{ tab.text }}</v-badge
            > -->{{ tab.text }}
          </v-tab>
          <v-tab-item>
            <v-divider />
            <v-container>
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
              <v-btn @click="getGlpiData()"></v-btn
            ></v-container>
            <v-container
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
          <!-- <v-tab-item>
            <v-row>
              <v-col cols="3" v-for="report in reports" :key="report.id">
                <v-card outlined>
                  <v-card-text>
                    <v-row align="center">
                      <v-card-text>{{ report.text }}</v-card-text>
                      <v-col class="display-3" cols="12">{{
                        report.value
                      }}</v-col>
                    </v-row>
                  </v-card-text>
                  <v-divider></v-divider>
                  <v-card-text @click="showPreview(report.id)"
                    >Подробнее</v-card-text
                  >
                </v-card>
              </v-col>
            </v-row>
          </v-tab-item> <v-tab-item>
            <DataTables
              :headers="headers_items"
              :item="item"
              title="Заявки"
              :loading="loading"
            />
          </v-tab-item>
          <v-tab-item>
            <DataTables
              :headers="headers_inc"
              :item="inc_item"
              title="Инциденты"
              :loading="loading"
            />
          </v-tab-item>
          <v-tab-item>
            <DataTables
              :headers="headers_comp"
              :item="glpi_data.data"
              title="Компьютеры"
              :loading="loading"
            />
          </v-tab-item> -->
        </v-tabs>
      </v-col>
    </v-row>
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
        source: "https://portal.ahstep.ru/ahstep/services/ajax/1c001.php",
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
            img:
              "https://portal.ahstep.ru/upload/resize_cache/iblock/20a/36_30_1/db.png",
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
    /*  progSts() {
      let y = 0;
      for (let i = 0; i < this.quest.length; i++) {
        if (this.quest[i].value) {
          y = y + 10;
        }
      }
      this.progValue = y;
    },
    time() {
      setInterval(() => {
        this.getGlpiData();
      }, 30000);
    },
    getData(source, param, result_ar) {
      this.loading = true;
      if (result_ar != "comp") {
        axios
          .get(source, {
            headers: {
              "Content-Type": "application/json; charset=utf-8",
            },
            params: {
              result: param,
            },
          })
          .then((response) => {
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, "0");
            let mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
            let yyyy = today.getFullYear();
            this.today = dd + "." + mm + "." + yyyy;
            this.new_count = response.data.length - this.item.length;
            if (param == "req") {
              this.item = response.data;
              this.newReq = response.data.filter((getDate) =>
                getDate.DATE.includes(this.today)
              );
              this.reports[0].value =
                this.item.length + " + " + this.newReq.length;
              this.reports[1].value = this.newReq.length;
            }
            if (param == "inc") {
              this.inc_item = response.data;
              this.getNewInc = response.data.filter((getDate) =>
                getDate.DATE.includes(this.today)
              );
              this.getIncNotApproved = response.data.filter(
                (getResponsible) =>
                  getResponsible.RESPONSIBLE.includes("ИТ Поддержка") &&
                  !getResponsible.STATUS.includes("5")
              );
              this.reports[2].value = this.getNewInc.length;
              this.reports[3].value = this.getIncNotApproved.length;
            }

            this.loading = false;
            this.value = this.item.length;
            this.indeterminate = false;
            if (this.new_count != 0 && this.new_count != response.data.length) {
              this.b_value = true;
            }
          });
      } else {
        axios
          .get("https://support.ahstep.ru/apirest.php/initSession", {
            headers: {
              "Content-Type": "application/json;",
            },
            params: {
              app_token: "TL0qYrS1zTK48QYMJD7N6yAvmO1WRx2BbsqB9iMu",
              user_token: "vnF70gCwnlSpMnWwXqWafIzT0bWlPI2Lm7QJVXpg",
            },
          })
          .then((response) => {
            var s_token = response.data.session_token;
            axios
              .get(source, {
                headers: {
                  "Content-Type": "application/json;",
                  "Session-Token": s_token,
                },
                params: {
                  app_token: "TL0qYrS1zTK48QYMJD7N6yAvmO1WRx2BbsqB9iMu",
                  range: "0-2000",
                  forcedisplay: [3, 4, 70, 113],
                },
              })
              .then((response) => {
                this.glpi_data = response.data;
                this.loading = false;
              });
          });
      }
    },
    getGlpiData() {
      axios
        .get("https://support.ahstep.ru/apirest.php/initSession", {
          headers: {
            "Content-Type": "application/json;",
          },
          params: {
            app_token: "TL0qYrS1zTK48QYMJD7N6yAvmO1WRx2BbsqB9iMu",
            user_token: "vnF70gCwnlSpMnWwXqWafIzT0bWlPI2Lm7QJVXpg",
          },
        })
        .then((response) => {
          var s_token = response.data.session_token;
          //Параметры для поиска комьютера
          const params1 = new URLSearchParams();
          params1.append(
            "app_token",
            "TL0qYrS1zTK48QYMJD7N6yAvmO1WRx2BbsqB9iMu"
          );
          params1.append("criteria[0][field]", "70");
          params1.append("criteria[0][searchtype]", "contains");
          params1.append("criteria[0][value]", "zaikin.ni");
          axios
            .get("https://support.ahstep.ru/apirest.php/search/Computer", {
              headers: {
                "Session-Token": s_token,
              },
              params: params1,
            })
            .then((response) => {
              this.glpi_data = response.data;
            });
        });
    }, */
    getData1C(type, sectionId) {
      this.loading = true;
      axios
        .get("https://portal.ahstep.ru/ahstep/services/ajax/ajax_1c001.php", {
          headers: {
            "Content-Type": "application/json; charset=utf-8",
          },
          /*  auth: {
            username: "zaikin.ni",
            password: "Vbuhfwbz75",
          }, */
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
          }
          setTimeout((this.loading = false), 1000);
        });
    },
    addData1C(item, type) {
      axios({
        method: "post",
        headers: { "Content-Type": "multipart/form-data" },
        url: "https://portal.ahstep.ru/ahstep/services/ajax/ajax_1c001.php",
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
  },
  mounted() {
    this.getUserGroup();
  },
};
</script>
