<template>
  <v-container>
    <!--<DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage"/>-->
    <!--<v-dialog v-model="dialog" max-width="65%">
            <v-card>
                <v-card-title class="headline grey lighten-2" primary-title>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                                v-for="v in headers_vesy"
                                :key="v.text"
                                cols="6"
                                sm="6"
                                md="8"
                            >
                                <v-text-field
                                    :v-model="v.value"
                                    :label="v.text"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" text @click="close()">Отмена</v-btn>
                    <v-btn color="primary" text @click="addItem(856)"
                        >Сохранить</v-btn
                    >
                </v-card-actions>
            </v-card>
    </v-dialog>-->
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
            v-for="tab in tabs"
            :key="tab.text"
            @click="getData(tab.source, tab.param, tab.result_ar)"
          >
            <v-badge color="green" :value="b_value" :content="tab.new_count" bordered>{{ tab.text }}</v-badge>
          </v-tab>
          <v-tab-item>
            <v-row>
              <v-col cols="3" v-for="report in reports" :key="report.id">
                <v-card outlined>
                  <v-card-text>
                    <v-row align="center">
                      <v-card-text>{{ report.text }}</v-card-text>
                      <v-col class="display-3" cols="12">{{ report.value }}</v-col>
                    </v-row>
                  </v-card-text>
                  <v-divider></v-divider>
                  <v-card-text @click="showPreview(report.id)">Подробнее</v-card-text>
                </v-card>
              </v-col>
            </v-row>
          </v-tab-item>
          <v-tab-item>
            <DataTables :headers="headers_items" :item="item" title="Заявки" :loading="loading" />
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
          <v-tab-item>
            <v-card>
              <v-card-title>
                Весовые
                <v-divider class="mx-4" inset vertical></v-divider>                
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Поиск"
                  single-line
                  hide-details
                ></v-text-field>
                <v-btn color="primary" dark class="mb-2 ml-2">Добавить</v-btn>
              </v-card-title>              
              <v-data-table
                :headers="headers_vesy"
                :items="vesy_item"
                :search="search"
                sortable
                :loading="loading"
                @click="dialog = true"
              >
                <template v-slot:item.NAME_UZ="{ item }">
                  <v-chip @click="editVesyItem(item.ID)">
                    {{ item.NAME_UZ }}<v-divider class="mx-4" color="warning" inset vertical></v-divider>
                    <v-edit-dialog :return-value.sync="item.NAME_UZ">                      
                      {{ item.NAME_UZ}}
                      <template v-slot:input>
                        <v-text-field v-model="item.NAME_UZ" label="Edit" single-line counter></v-text-field>
                      </template>
                    </v-edit-dialog>
                  </v-chip>
                </template>
              </v-data-table>
              <DataTables
                :headers="headers_vesy"
                :item="vesy_item"
                title="Весовые"
                :loading="loading"
              />
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <DataTables
              :headers="headers_vesy"
              :item="vesy_item"
              title="Пуны"
              :loading="loading"
              :expand="true"
            />
          </v-tab-item>
        </v-tabs>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
//import { bus } from "@/main.js";
import axios from "axios";
import DataTables from "@/components/DataTables.vue";
export default {
  components: {
    DataTables
  },
  data: () => ({
    title: "Тестовые вопросы к инструкциям по уборке нишевых культур",
    name: "",
    tabs: [
      {
        text: "Отчеты"
      },
      {
        text: "Заявки",
        source:
          "https://portal.ahstep.ru/ahstep/services/ajax/dashboard/ajax.php",
        param: "req",
        result_ar: "request",
        new_count: ""
      },
      {
        text: "Инциденты",
        source:
          "https://portal.ahstep.ru/ahstep/services/ajax/dashboard/ajax.php",
        param: "inc",
        result_ar: "request",
        new_count: ""
      },
      {
        text: "Компьютеры",
        source: "https://support.ahstep.ru/apirest.php/search/Computer",
        param: "comp",
        result_ar: "comp",
        new_count: ""
      },
      {
        text: "Весовые",
        source:
          "https://portal.ahstep.ru/ahstep/services/ajax/dashboard/ajax.php",
        param: "vesy",
        result_ar: "vesy_item"
      },
      {
        text: "Пуны",
        source:
          "https://portal.ahstep.ru/ahstep/services/ajax/dashboard/ajax.php",
        param: "pun"
      }
    ],
    b_value: false,
    new_count: "",
    loading: false,
    table_name: "Dashboard",
    table_inc: true,
    headers_vesy: [
      { text: "Название узла", value: "NAME_UZ" },
      { text: "Код узла", value: "CODE" },
      { text: "Юридическое лицо", value: "COMPANY" },
      { text: "Доп. Обозначение", value: "ADD_NAME" },
      { text: "Имя комп./домен", value: "PC_NAME" },
      { text: "Контакты отв. сотр. IT", value: "CONTACT_IT" },
      { text: "Контакты отв. Весовщика", value: "CONTACT_VESY" },
      { text: "Пароль windows", value: "PASS_WIN" },
      { text: "ID TeamViewer 13", value: "ID_TEAM" },
      { text: "Pass TeamViewer", value: "PASS_TEAM" },
      { text: "IP Win пом.", value: "IP_MSRA" },
      { text: "Zabbix", value: "ZBX_STATUS" },
      { text: "Фактический адрес", value: "ADDRESS" },
      { text: "Весы в 1С", value: "VESY_1C" },
      { text: "Модель терминала", value: "MODEL_TERM" },
      { text: "Заземление", value: "ZAZEML_STATUS" },
      { text: "Модель камеры/кол-во/зип", value: "MODEL_CAMERA" },
      { text: "Драйвер Весовая2020", value: "DRIVER" },
      { text: "Действия", value: "ACTIONS", sortable: false }
    ],
    vesy_item: [],
    headers_inc: [
      { text: "Тема", value: "NAME" },
      { text: "Дата", value: "DATE" },
      { text: "Автор", value: "CREATED_BY" },
      { text: "Отвественный", value: "RESPONSIBLE" },
      { text: "Задача", value: "TASK_ID" },
      { text: "Статус", value: "STATUS" }
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
      { text: "Статус", value: "STATUS" }
    ],
    headers_comp: [
      { text: "Имя компьютера", value: "1" },
      { text: "Местоположение", value: "3" },
      { text: "Тип", value: "4" },
      { text: "Пользователь", value: "70" },
      { text: "MAC", value: "113" }
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
      { id: "3", text: "Не принятые инциденты", value: "" }
    ],
    today: ""
  }),
  methods: {
    formCancl: function() {
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
        //this.getData();
        this.getGlpiData();
      }, 30000);
    },
    getData(source, param, result_ar) {
      this.loading = true;
      if (result_ar != "comp") {
        axios
          .get(source, {
            headers: {
              "Content-Type": "application/json; charset=utf-8"
            },
            auth: {
              username: "admin",
              password: "Htdjk.wbz17"
            },
            params: {
              result: param
            }
          })
          .then(response => {
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, "0");
            let mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
            let yyyy = today.getFullYear();
            this.today = dd + "." + mm + "." + yyyy;
            this.new_count = response.data.length - this.item.length;
            if (param == "vesy") {
              this.vesy_item = response.data;
            }
            if (param == "req") {
              this.item = response.data;
              this.newReq = response.data.filter(getDate =>
                getDate.DATE.includes(this.today)
              );
              this.reports[0].value =
                this.item.length + " + " + this.newReq.length;
              this.reports[1].value = this.newReq.length;
            }
            if (param == "inc") {
              this.inc_item = response.data;
              this.getNewInc = response.data.filter(getDate =>
                getDate.DATE.includes(this.today)
              );
              this.getIncNotApproved = response.data.filter(
                getResponsible =>
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
              "Content-Type": "application/json;"
            },
            params: {
              app_token: "TL0qYrS1zTK48QYMJD7N6yAvmO1WRx2BbsqB9iMu",
              user_token: "vnF70gCwnlSpMnWwXqWafIzT0bWlPI2Lm7QJVXpg"
            }
          })
          .then(response => {
            var s_token = response.data.session_token;
            axios
              .get(source, {
                headers: {
                  "Content-Type": "application/json;",
                  "Session-Token": s_token
                },
                params: {
                  app_token: "TL0qYrS1zTK48QYMJD7N6yAvmO1WRx2BbsqB9iMu",
                  range: "0-2000",
                  forcedisplay: [3, 4, 70, 113]
                }
              })
              .then(response => {
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
            "Content-Type": "application/json;"
          },
          params: {
            app_token: "TL0qYrS1zTK48QYMJD7N6yAvmO1WRx2BbsqB9iMu",
            user_token: "vnF70gCwnlSpMnWwXqWafIzT0bWlPI2Lm7QJVXpg"
          }
        })
        .then(response => {
          var s_token = response.data.session_token;
          axios
            .get("https://support.ahstep.ru/apirest.php/search/Computer", {
              headers: {
                "Content-Type": "application/json;",
                "Session-Token": s_token
              },
              params: {
                app_token: "TL0qYrS1zTK48QYMJD7N6yAvmO1WRx2BbsqB9iMu",
                range: "0-2000",
                forcedisplay: [3, 4, 70, 113]
              }
            })
            .then(response => {
              this.glpi_data = response.data;
            });
        });
    },
    getVesy() {
      axios
        .get(
          "https://portal.ahstep.ru/ahstep/services/ajax/dashboard/ajax.php",
          {
            headers: {
              "Content-Type": "application/json; charset=utf-8"
            },
            auth: {
              username: "admin",
              password: "Htdjk.wbz17"
            },
            params: {
              getVesy: "getVesy"
            }
          }
        )
        .then(response => {
          (this.vesy_item = response.data), console.log(this.vesy_item);
          this.loading = false;
        });
    },
    editVesyItem(id) {
      this.dialog = true;
      this.dialog_title = "Редактирование позиции";
      console.log(id);
    },
    getTest() {
      axios
        .get("https://b2btestservice.ocs.ru/b2bjson.asmx/GetCatalog", {
          headers: {
            "Content-Type": "application/json;"
          },
          params: {
            Login: "VqFLDv9gY",
            Token: "VuivdzcaGvFtOACElaNdO?@-BKFMXy"
          }
        })
        .then(response => {
          console.log(response.data);
        });
    },
    showPreview(id) {
      this.dialog = true;
      this.loading = false;
      if (id == 1) {
        this.getPreviewTask = this.item.filter(getDate =>
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
    test1(param1) {
      console.log(param1);
      let res = param1.filter(test => test.text.includes("в"));
      console.log(res);
    },
    close() {
      this.dialog = false;
    }
  },
  mounted() {
    //this.getTest()
    //this.loading = true
    //this.getData("vesy");
    //this.getData("inc");
    //this.time();
    this.getVesy();
    this.getGlpiData();
  }
};
</script>
