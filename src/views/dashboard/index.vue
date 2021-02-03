<template>
  <v-container>
    <v-dialog v-model="dialog" transition="slide-x-reverse-transition">
      <v-data-table
        :headers="headers_inc"
        :items="getPreviewTask"
        :search="search"
        sortable
        :loading="loading"
      ></v-data-table>
    </v-dialog>
    <v-row>
      <v-col cols="12">
        <v-tabs>
          <v-tab
            v-for="tab in getTabs"
            :key="tab.text"
            @click="showData(tab.source, tab.param, tab.result_ar)"
          >
            <v-badge
              color="green"
              :value="b_value"
              :content="tab.new_count"
              bordered
              >{{ tab.text }}</v-badge
            >
          </v-tab>
          <v-tab-item>
            <v-divider />
            <v-container>
              <v-btn
                class="mr-2"
                @click="showTable(btn)"
                v-for="btn in tabs[0].cards"
                :key="btn.i"
                ><v-icon left>{{ btn.icon }}</v-icon
                >{{ btn.title }}</v-btn
              ></v-container
            >
            <v-container v-for="table in tabs[0].cards.filter(
                  (getTable) => getTable.visible == true
                )" :key="table.i">
                <DataTables
                  class="mx-auto"
                  :headers="getTableHeaders(table.tableHeaders)"
                  :item="table.tableItems"
                  :title="table.title"
                  :img="table.img"
                  :actions="table.actions"
                  :loading="loading"
                  :dialogTitle="'Добавление ИБ'"
                  :dialogFields="table.tableHeaders"
                  :dialogFieldsCols="3"
                />
            </v-container>
          </v-tab-item>
          <v-tab-item>
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
          </v-tab-item>
          <v-tab-item>
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
          </v-tab-item>
        </v-tabs>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import axios from "axios";
import DataTables from "@/components/DataTables.vue";
export default {
  components: {
    DataTables,
  },
  data: () => ({
    name: "",
    tabs: [
      {
        text: "Панель управления 1C",
        source: "https://portal.ahstep.ru/ahstep/services/ajax/1c001.php",
        param: "req",
        aclGroup: true,
        cards: [
          {
            title: "Базы данных",
            subtitle: "Редактирование списка баз данных",
            tableHeaders: [
              { text: "название ИБ", value: "NAME", visibleInTable: true },
              { text: "вид базы", value: "VID", visibleInTable: true },
              { text: "тип базы", value: "TYPE", visibleInTable: true },
              {
                text: "кластер серверов",
                value: "CLUSTER",
                visibleInTable: true,
              },
              { text: "имя ИБ", value: "NAME_DB", visibleInTable: true },
              { text: "адрес ИБ", value: "ADDRESS_DB", visibleInTable: true },
              { text: "пользователь ", value: "USER", visibleInTable: false },
              { text: "логин ", value: "LOGIN", visibleInTable: false },
              { text: "конфигурация", value: "CONFIG", visibleInTable: false },
              { text: "релиз", value: "RELEASE", visibleInTable: false },
              { text: "лицензия ", value: "LICENSE", visibleInTable: false },
              { text: "комментарий ", value: "COMMENT", visibleInTable: false },
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
            visible: false,
          },
          {
            title: "Лицензии",
            icon: "mdi-text-box-check-outline",
            visible: false,
            tableHeaders: [
              { text: "тип лицензии", value: "TYPE", visibleInTable: true },
              { text: "вид лицензии", value: "VID", visibleInTable: true },
              { text: "основная лицензия", value: "LICENSE", visibleInTable: true },
              { text: "количество рабочих мест", value: "NUMBER", visibleInTable: true },
              { text: "юр.лицо - владелец", value: "ORG", visibleInTable: false },
              { text: "дата приобретения", value: "DATE_BUY", visibleInTable: true },
              { text: "место активации", value: "LOCATION", visibleInTable: false },
              { text: "действующий договор ИТС", value: "ORDER", visibleInTable: false },
            ],
            img: "https://img.icons8.com/office/30/000000/diploma.png",
          },
          {
            title: "Договора ИТС",
            icon: "mdi-file-document-edit-outline",
            visible: false,
            tableHeaders: [
              { text: "юр лицо", value: "ORG", visibleInTable: true },
              { text: "контрагент", value: "AGENT", visibleInTable: true },
              { text: "тип договора", value: "DOGOVOR_TYPE", visibleInTable: true },
              { text: "основной договор", value: "DOCOVOR", visibleInTable: true },
              { text: "юр.лицо - владелец", value: "VLADELEC", visibleInTable: true },
              { text: "дата начала", value: "DATE_START", visibleInTable: true },
              { text: "дата окончания", value: "DATE_END", visibleInTable: true },
            ],
            img: "https://img.icons8.com/office/30/000000/bill.png"

          },
        ],
      },
      {
        text: "Заявки",
        source: "./ajax/dashboard/ajax.php",
        param: "req",
        result_ar: "request",
        new_count: "",
        aclGroup: true,
      },
      {
        text: "Инциденты",
        source:
          "https://portal.ahstep.ru/ahstep/services/ajax/dashboard/ajax.php",
        param: "inc",
        result_ar: "request",
        new_count: "",
        aclGroup: "",
      },
      {
        text: "Компьютеры",
        source: "https://support.ahstep.ru/apirest.php/search/Computer",
        param: "comp",
        result_ar: "comp",
        new_count: "",
        aclGroup: "",
      },
      {
        text: "Весовые",
        source:
          "https://portal.ahstep.ru/ahstep/services/ajax/dashboard/ajax.php",
        param: "vesy",
        result_ar: "vesy_item",
        aclGroup: "",
      },
      {
        text: "Отчеты",
        aclGroup: true,
      },
    ],
    b_value: false,
    new_count: "",
    loading: false,
    table_name: "Dashboard",
    table_inc: true,
    headers_inc: [
      { text: "Тема", value: "NAME" },
      { text: "Дата", value: "DATE" },
      { text: "Автор", value: "CREATED_BY" },
      { text: "Отвественный", value: "RESPONSIBLE" },
      { text: "Задача", value: "TASK_ID" },
      { text: "Статус", value: "STATUS" },
    ],
    inc_item: [],
    newReq: [],
    getNewInc: [],
    getIncNotApproved: [],
    getPreviewTask: [],
    headers_items: [
      { text: "Наименование", value: "NAME" },
      { text: "Дата", value: "DATE" },
      { text: "Автор", value: "CREATED_BY" },
      { text: "Получатель", value: "POLUCHATEL" },
      { text: "Задача", value: "TASK_ID" },
      { text: "Статус", value: "STATUS" },
    ],
    headers_comp: [
      { text: "Имя компьютера", value: "1" },
      { text: "Местоположение", value: "3" },
      { text: "Тип", value: "4" },
      { text: "Пользователь", value: "70" },
      { text: "MAC", value: "113" },
    ],
    item: [],
    search: "",
    dialog: false,
    name_err: "",
    dialogMessage: "",
    progValue: "0",
    indeterminate: true,
    value: "0",
    glpi_data: [],
    reports: [
      { id: "0", text: "Общее количество заявок", value: "" },
      { id: "1", text: "Новые заявки за сегодня", value: "" },
      { id: "2", text: "Новые инциденты", value: "" },
      { id: "3", text: "Не принятые инциденты", value: "" },
    ],
    today: "",
  }),
  created() {
    bus.$on("newItem", (data) => {
      this.addDB(data, "addDB");
    });
    bus.$on("remItem", (data) => {
      this.addDB(data, "remDB");
    });
    bus.$on("updItem", (data) => {
      this.addDB(data, "updDB");
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
    formCancl: function () {
      this.$router.go(-1);
    },
    progSts() {
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
          axios
            .get("https://support.ahstep.ru/apirest.php/search/Computer", {
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
            });
        });
    },
    getDB() {
      this.loading = true;
      axios
        .get("https://portal.ahstep.ru/ahstep/services/ajax/ajax_1c001.php", {
          headers: {
            "Content-Type": "application/json; charset=utf-8",
          },
          auth: {
            username: "zaikin.ni",
            password: "Vbuhfwbz75",
          },
          params: {
            getDB: "getDbList",
          },
        })
        .then((response) => {
          this.tabs[0].cards[0].tableItems = response.data;
          this.loading = false;
        });
    },
    addDB(item, type) {
      axios({
        method: "post",
        headers: { "Content-Type": "multipart/form-data" },
        url: "https://portal.ahstep.ru/ahstep/services/ajax/ajax_1c001.php",
        data: {
          type: type,
          nameDB: item,
        },
        auth: {
          username: "zaikin.ni",
          password: "Vbuhfwbz75",
        },
      }).then((response) => {
        if (response.status == 200) {
          this.getDB();
        }
      });
    },
    showTable(item) {
      console.log(item.visible);
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
      this.dialog = false;
    },
  },
  mounted() {
    this.getDB();
    //this.getData("inc");
    //this.time();
    //this.getVesy();
    //this.getGlpiData();
  },
};
</script>
