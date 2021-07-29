<template>
  <v-container fluid class="grey lighten-4">
    <v-dialog v-model="dialog" max-width="75%" class="mx-auto">
      <v-card v-for="task in taskInfo" :key="task.NAME">
        <v-card-title class="headline grey lighten-2">
          {{ task.NAME }} от {{ task.DATE }}
        </v-card-title>

        <v-card-text class="mx-auto">
          <v-row class="mb-n8">
            <v-col cols="2">
              <v-subheader class="mt-2">Инициатор:</v-subheader>
            </v-col>
            <v-col cols="10">
              <v-text-field :value="task.CREATED_BY" readonly></v-text-field>
            </v-col>
          </v-row>
          <v-row class="mb-n8">
            <v-col cols="2">
              <v-subheader dense class="mt-2"
                >Название организации:</v-subheader
              >
            </v-col>
            <v-col cols="4">
              <v-text-field
                :value="task.NAZVANIE_ORGANIZATSII"
                readonly
              ></v-text-field>
            </v-col>
            <v-col cols="2">
              <v-subheader dense class="mt-2">Контактное лицо:</v-subheader>
            </v-col>
            <v-col cols="4">
              <v-text-field
                :value="task.KONTAKTNOE_LITSO"
                readonly
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row class="mb-n8">
            <v-col cols="2">
              <v-subheader dense class="mt-2">Тема опыта:</v-subheader>
            </v-col>
            <v-col cols="10">
              <v-text-field :value="task.TEMA_OPYTA" readonly></v-text-field>
            </v-col>
          </v-row>
          <v-row class="mb-n8">
            <v-col cols="2">
              <v-subheader dense class="mt-2">Цели опыта:</v-subheader>
            </v-col>
            <v-col cols="10">
              <v-text-field :value="task.TSELI_OPYTA" readonly></v-text-field>
            </v-col>
          </v-row>
          <v-row class="mb-n8">
            <v-col cols="2">
              <v-subheader dense class="mt-2">Задачи опыта:</v-subheader>
            </v-col>
            <v-col cols="10">
              <v-text-field :value="task.ZADACHI_OPYTA" readonly></v-text-field>
            </v-col>
          </v-row>
          <v-row class="mb-n8">
            <v-col cols="2">
              <v-subheader dense class="mt-2">Объект опыта:</v-subheader>
            </v-col>
            <v-col cols="10">
              <v-text-field :value="task.OBEKT_OPYTA" readonly></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="2">
              <v-subheader class="mt-2"
                >Экономическая эффективность:</v-subheader
              >
            </v-col>
            <v-col cols="10">
              <v-text-field
                :value="task.EKONOMICHESKAYA_EFFEKTIVNOST"
                readonly
              ></v-text-field>
            </v-col>
          </v-row>
          <v-container fluid>
            <v-row>
              <v-btn
                color="blue-grey lighten-4"
                class="ml-2 my-1"
                small
                @click="(addInfo = !addInfo), (log = false)"
                ><v-icon color="indigo" left>mdi-format-list-bulleted</v-icon
                >Дополнительная информация</v-btn
              >
              <v-btn
                color="blue-grey lighten-4"
                class="ml-2 my-1"
                small
                @click="(log = !log), (addInfo = false)"
                ><v-icon color="info" left>mdi-history</v-icon>История
                заявки</v-btn
              >
              <v-btn
                v-for="item in task.FILE"
                :key="item"
                color="blue-grey lighten-4"
                class="ml-2 my-1"
                :href="item.path"
                small
                ><v-icon color="deep-orange" left>mdi-file-link-outline</v-icon
                >{{ item.name }}</v-btn
              >
              <v-expand-transition>
                <v-container fluid v-if="addInfo" class="mb-n6">
                  <v-row class="mb-n6">
                    <v-col>
                      <v-textarea
                        label="Контактная информация"
                        rows="1"
                        auto-grow
                        outlined
                        filled
                        :value="task.KONTAKTNAYA_INFORMATSIYA"
                        readonly
                      />
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col>
                      <v-textarea
                        label="Реквизиты организации"
                        rows="1"
                        auto-grow
                        outlined
                        filled
                        :value="task.REKVIZITY_ORGANIZATSII"
                        readonly
                      />
                    </v-col>
                  </v-row>
                </v-container>
              </v-expand-transition>
              <v-expand-transition>
                <v-container v-if="log" fluid class="mb-n12">
                  <v-row>
                    <v-col>
                      <v-textarea
                        label="История заявки"
                        auto-grow
                        outlined
                        filled
                        :value="task.LOG"
                        readonly
                      />
                    </v-col>
                  </v-row>
                </v-container>
              </v-expand-transition>
            </v-row>
          </v-container>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="dialog = false"> Понятно </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <h1>{{ $router.currentRoute.name }}</h1>
    <v-divider class="mb-2" />
    <v-row>
      <v-col cols="6">
        <v-row>
          <v-col>
            <v-card
              v-for="card in mainCard"
              :key="card.name"
              class="mt-1"
              elevation="1"
              outlined
            >
              <v-row>
                <v-col cols="4">
                  <v-list-item three-line>
                    <v-list-item-content>
                      <v-list-item-title class="headline mb-1">
                        {{ card.name }}
                      </v-list-item-title>
                      <v-list-item-subtitle>
                        {{ card.name }} опытов:
                        <span class="title ml-1">{{ card.value }}</span>
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-col>
                <v-col cols="8">
                  <v-card tile height="432px" id="chartdiv"> </v-card>
                </v-col>
              </v-row>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
      <v-col cols="6">
        <v-row>
          <v-col cols="6" v-for="card in addCard" :key="card.name">
            <v-card class="mt-1" elevation="1" outlined>
              <v-row>
                <v-col cols="8">
                  <v-list-item three-line>
                    <v-list-item-content>
                      <v-list-item-title class="headline mb-1">
                        {{ card.name }}
                      </v-list-item-title>
                      <v-list-item-subtitle>
                        {{ card.name }} опытов:
                        <span class="title ml-1">{{ card.value }}</span>
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-col>
                <v-col cols="4">
                  <v-container class="px-0">
                    <v-progress-circular
                      :rotate="-90"
                      :size="100"
                      :width="15"
                      :value="Math.round((card.value / items.length) * 100) + '%'"
                      :color="card.color"
                    >
                      <v-card-title class="headline mb-1">
                        {{
                          (Math.round((card.value / items.length)) * 100 + "").split(
                            "."
                          )[0]
                        }}%</v-card-title
                      >
                    </v-progress-circular>
                  </v-container>
                </v-col>
              </v-row>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <v-divider class="my-4" />
    <v-card class="mx-auto" width="100%">
      <v-card-title>
        <v-toolbar-title>{{ titleDataTable }}</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-tooltip v-for="item in tableButtons" :key="item.name" bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              v-bind="attrs"
              v-on="on"
              @click="exportTableToFile()"
              :color="item.color"
              class="mr-2"
              fab
              x-small
              ><v-icon>{{ item.icon }}</v-icon></v-btn
            >
          </template>
          <span>{{ item.tooltip }}</span>
        </v-tooltip>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Поиск"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        id="datatableagro"
        :headers="
          headers.filter((getHeader) => getHeader.visibleInTable == true)
        "
        :items="items"
        :search="search"
      >
        <template v-slot:[`item.NAME`]="{ item }">
          <v-card-text @click="openTask(item.ID)">{{ item.NAME }}
          <v-tooltip right>
            <template v-slot:activator="{ }">
            <v-icon @mouseenter="console.log('test')" color="info" small> mdi-information-outline</v-icon>
            </template>
            <span>test</span>
          </v-tooltip>
         </v-card-text>
        </template>
        <template v-slot:[`item.STATUS`]="{ item }">
          <v-chip :color="getStatus(item.STATUS)" dark>
            {{ item.STATUS }}
          </v-chip>
        </template>
        <template v-slot:[`item.RESPONSIBLE`]="{ item }">
          <v-chip
            :href="'../../company/personal/user/' + item.RESPONSIBLEID + '/'"
            v-if="item.RESPONSIBLE != ' '"
            color="grey"
            outlined
          >
            <v-avatar left>
              <img v-if="item.PHOTO" :src="item.PHOTO" />
              <v-icon v-else>mdi-account-circle</v-icon> </v-avatar
            ><span>{{ item.RESPONSIBLE }}</span>
          </v-chip>
        </template>
        <template v-slot:[`item.TASK`]="{ item }">
          <v-chip :href="'../../company/personal/user/' + item.RESPONSIBLEID + '/tasks/task/view/' + item.TASK + '/'" v-if="item.TASK">
            {{ item.TASK }}
          </v-chip>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script>
import axios from "axios";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);
export default {
  data: () => ({
    titleDataTable: "Агрономические опыты",
    cards: [
      {
        name: "Общее количество",
        value: 0,
        item: 0,
        color: "blue",
        main: true,
      },
      { name: "В работе", value: 0, item: 0, color: "green" },
      { name: "На согласовании", value: 0, item: 0, color: "orange" },
      { name: "Просрочено", value: 0, item: 0, color: "red" },
      { name: "Проведено", value: 0, item: 0, color: "grey" },      
      { name: "Назначение", value: 0, item: 0, color: "info" },
      { name: "Отклонено", value: 0, item: 0, color: "red" },
    ],
    headers: [
      {
        text: "Название",
        value: "NAME",
        visibleInTable: true,
      },
      {
        text: "Автор",
        value: "CREATED_BY",
        visibleInTable: true,
      },
      {
        text: "Дата",
        value: "DATE",
        visibleInTable: true,
      },
      {
        text: "Организация",
        value: "NAZVANIE_ORGANIZATSII",
        visibleInTable: true,
      },
      {
        text: "Статус",
        value: "STATUS",
        visibleInTable: true,
      },
      {
        text: "Отвественный",
        value: "RESPONSIBLE",
        visibleInTable: true,
      },      
      {
        text: "Задача",
        value: "TASK",
        visibleInTable: true,
      },
    ],
    items: [],
    search: "",
    dialog: false,
    taskInfo: [],
    tableButtons: [
      {
        text: "Настройка полей",
        icon: "mdi-cog-transfer-outline",
        color: "info",
        tooltip: "Настройка полей",
      },
      {
        text: "Эспорт в Excel",
        icon: "mdi-file-export-outline",
        color: "success",
        tooltip: "Эспорт в Excel",
      },
    ],
    taskInfoNew: [
      {
        text: "Отвественый:",
        value: "1",
      },
      {
        text: "Название организации:",
        value: "1",
      },
    ],
    addInfo: false,
    log: false,
    source: "./ajax/ajax_op002.php",
    //source: "https://portal.ahstep.ru/ahstep/services/ajax/ajax_op002.php",
    mainChart: [],
  }),
  created() {
    axios
      .get(this.source, {
        headers: {
          "Content-Type": "application/json; charset=utf-8",
        },
        /* auth: {
          username: "zaikin.ni",
          password: "Vbuhfwbz75"
        }, */
        params: {
          getItems: "getItems",
        },
      })
      .then((response) => ((this.items = response.data), this.getValue()));
  },
  computed: {
    mainCard() {
      return this.cards.filter((getMain) => getMain.main == true);
    },
    addCard() {
      return this.cards.filter((getMain) => getMain.main != true);
    },
  },
  methods: {
    getValue() {
      this.cards[0].value = this.items.length;
      let newTask = this.items.filter(
        (getTask) =>
          getTask.STATUS.includes("Зарегистрирована заявка на опыт") ||
          getTask.STATUS.includes("Согласование")
      );
      let workTask = this.items.filter(
        (getTask) =>
          getTask.STATUS.includes("В работе") ||
          getTask.STATUS.includes("Исполнение")
      );
      let badTask = this.items.filter((getTask) =>
        getTask.STATUS.includes("Просроче")
      );
      let doneTask = this.items.filter((getTask) =>
        getTask.STATUS.includes("Проведён")
      );
      let rejectedTask = this.items.filter((getTask) =>
        getTask.STATUS.includes("Отклонен")
      );
      let assignmentTask = this.items.filter((getTask) =>
        getTask.STATUS.includes("Назначение")
      );
      this.cards[1].value = workTask.length;
      this.mainChart.push(
        Object.assign(
          { taskLenght: workTask.length },
          { taskStatus: "В работе" },
          { color: am4core.color("#4CAF50") }
        )
      );
      this.cards[2].value = newTask.length;
      this.mainChart.push(
        Object.assign(
          { taskLenght: newTask.length },
          { taskStatus: "Согласование" },
          { color: am4core.color("#FF9800") }
        )
      );
      this.cards[3].value = badTask.length;
      this.mainChart.push(
        Object.assign(
          { taskLenght: badTask.length },
          { taskStatus: "Просрочено" }
        )
      );
      this.cards[4].value = doneTask.length;
      this.mainChart.push(
        Object.assign(
          { taskLenght: doneTask.length },
          { taskStatus: "Проведено" },
          { color: am4core.color("#9E9E9E") }
        )
      );
      this.cards[5].value = assignmentTask.length;
      this.mainChart.push(
        Object.assign(
          { taskLenght: assignmentTask.length },
          { taskStatus: "Назначение" },
          { color: am4core.color("#2196f3") }
        )
      );
      this.cards[6].value = rejectedTask.length;
      this.mainChart.push(
        Object.assign(
          { taskLenght: rejectedTask.length },
          { taskStatus: "Назначение" },
          { color: am4core.color("#2196f3") }
        )
      );
      this.mainChart.push(
        Object.assign(
          { taskLenght: rejectedTask.length },
          { taskStatus: "Отклонено" },
          { color: am4core.color("#F44336") }
        )
      );

      this.addCharts();
    },
    getStatus(status) {
      if (
        status == "Зарегистрирована заявка на опыт" ||
        status == "Согласование"
      )
        return "orange";
      else if (status == "Просрочен" || status == "Отклонен" || status == "Отклонено") return "red";
      else if (status == "Проведён") return "grey";
      else if (status == "Назначение") return "info";
      else return "green";
    },
    openTask(id) {
      this.dialog = true;
      axios
        .get(this.source, {
          headers: {
            "Content-Type": "application/json; charset=utf-8",
          },
          
        /* auth: {
          username: "zaikin.ni",
          password: "Vbuhfwbz75"
        }, */
          params: {
            getTask: id,
          },
        })
        .then(
          (response) => (
            (this.taskInfo = response.data),
            (this.taskInfoNew[0].value = response.data[0]["CREATED_BY"]),
            (this.taskInfoNew[1].value =
              response.data[0]["NAZVANIE_ORGANIZATSII"])
          )
        );
    },
    addCharts() {
      {
        let chart = am4core.create("chartdiv", am4charts.PieChart);
        chart.data = this.mainChart;
        let label = chart.seriesContainer.createChild(am4core.Label);
        label.text = this.items.length;
        label.horizontalCenter = "middle";
        label.verticalCenter = "middle";
        label.fontSize = 50;
        chart.innerRadius = am4core.percent(50);

        let pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "taskLenght";
        pieSeries.dataFields.category = "taskStatus";
        pieSeries.slices.template.propertyFields.fill = "color";
        pieSeries.slices.template.stroke = am4core.color("#fff");
        pieSeries.slices.template.strokeWidth = 2;
        pieSeries.slices.template.strokeOpacity = 1;

        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;
      }
    },
    exportTableToFile() {
      let htmltable = document.getElementsByClassName("v-data-table__wrapper");
      let html = htmltable[0].outerHTML;
      let downloadLink = document.createElement("a");
      let uri = "data:application/vnd.ms-excel;base64,",
        template =
          '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body>' +
          html +
          "</body></html>",
        base64 = function (s) {
          return window.btoa(unescape(encodeURIComponent(s)));
        },
        format = function (s, c) {
          return s.replace(/{(\w+)}/g, function (m, p) {
            return c[p];
          });
        };

      let ctx = { worksheet: "Worksheet", table: html };
      downloadLink.href = uri + base64(format(template, ctx));
      downloadLink.download = this.titleDataTable + ".xls";

      document.body.appendChild(downloadLink);
      downloadLink.click();
      document.body.removeChild(downloadLink);
    },
  },
  mounted() {},
};
</script>

<style>
</style>