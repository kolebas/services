<template>
  <v-container fluid>
    <div v-if="!getAccess"><Alert /></div>
    <div v-if="getAccess">
      <v-card>
        <DataTables
          :title="$router.currentRoute.name"
          class="mx-auto"
          :headers="orderHeaders"
          :item="orderItems"
          :dialogFields="[]"
          :addButtonOnTitle="false"
          :addTableButtons="tableButtons"
        />
        <v-overlay :absolute="absolute" :value="overlay">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
      </v-card>
    </div>
    <div class="exportOrderItems">
      <table id="exportOrderItemsTableId">
        <tr>
          <th>Сотрудники</th>
          <th v-for="item in exportOrderItems" :key="item.name">
            {{ item.name }}
          </th>
        </tr>
        <tr v-for="row in newRowTableToExport" :key="row.index">
          <td>
            {{ row.user }}
          </td>
        </tr>
        <tr>
          <th>Общее количество</th>
          <th v-for="item in exportOrderItems" :key="item.name">
            {{ item.count }}
          </th>
        </tr>
      </table>
    </div>
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
    absolute: true,
    overlay: false,
    userId: "",
    userGroup: [],
    orderHeaders: [
      {
        text: "ID",
        value: "ID",
        visibleInTable: false,
      },
      {
        text: "Номер заказа",
        value: "NUMBER",
        visibleInTable: true,
        type: "string",
        width: "15%",
      },
      {
        text: "Дата",
        value: "DATE",
        visibleInTable: true,
        type: "string",
        width: "15%",
      },
      {
        text: "ФИО",
        value: "USER",
        visibleInTable: true,
        type: "string",
        width: "15%",
      },
      {
        text: "Заказ",
        value: "ORDER",
        visibleInTable: true,
        type: "string",
      },
      {
        text: "Статус",
        value: "ORDER_STATUS",
        visibleInTable: true,
        width: "10%",
        size: "",
      },
    ],
    orderItems: [],
    tableButtons: [
      {
        text: "Эспорт в Excel",
        icon: "mdi-file-export-outline",
        color: "success",
        tooltip: "Эспорт в Excel",
      },
    ],
    exportOrderItems: [],
    newHeaderTableToExport: [],
    newRowTableToExport: [],
    //sourceUrl: "./ajax/marketing/store.php",
    sourceUrl:
      "https://test-portal.ahstep.ru/ahstep/services/ajax/marketing/store.php",
  }),
  created() {
    bus.$on("exportTableToFile", (data) => {
      this.exportToFile(data);
    });
    bus.$on("updItem", (data) => {
      this.updateStoreOrders(data);
    });
  },
  computed: {
    getAccess() {
      if (this.userGroup.includes("49")) {
        return true;
      } else {
        return true;
      }
    },
  },
  methods: {
    getStoreOrders(infoblockID, sectionID) {
      this.overlay = true;
      axios
        .get(this.sourceUrl, {
          auth: {
            username: "zaikin.ni",
            password: "Vbuhfwbz75",
          },
          params: {
            type: "getStoreOrders",
            id: infoblockID,
            sectionID: sectionID,
          },
        })
        .then(
          (response) => (
            (this.orderItems = response.data), (this.overlay = false)
          )
        );
    },
    updateStoreOrders(orderData) {
      this.overlay = true;
      axios
        .post(this.sourceUrl, {
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          data: {
            type: "updateStoreOrders",
            order: orderData,
          },
        })
        .then((response) => {
          if (response.status == 200) {
            this.overlay = true;
            setTimeout(
              () => (this.getStoreOrders(95, 857), (this.overlay = false)),
              3000
            );
          }
        });
    },
    getUserInfo() {
      axios
        .get("./ajax/ajax_usr.php", {})
        .then(
          (response) => (
            (this.userId = response.data[0].ID),
            (this.userGroup = response.data[0].GROUP)
          )
        );
    },
    exportToFile(inputArray) {
      let orderItem = inputArray.filter((item) => item.ORDER_STATUS == "Новый");
      for (let i = 0; orderItem.length > i; i++) {
        let order = orderItem[i].ORDER;
        let rowObject = Object.assign(
          { user: orderItem[i].USER },
          { order: orderItem[i].ORDER },
          { count: "null" }
        );
        this.newRowTableToExport.push(rowObject);
        for (let y = 0; order.length > y; y++) {
          let product = order[y];
          let productObject = this.newHeaderTableToExport.find(
            (item) => item.name === product.slice(0, product.indexOf("---"))
          );
          if (productObject) {
            productObject.count =
              Number(productObject.count) +
              Number(product.slice(product.indexOf("---") + 4));
          } else if (!productObject) {
            var obj = Object.assign(
              { name: product.slice(0, product.indexOf("---")) },
              { count: product.slice(product.indexOf("---") + 4) }
            );
            this.newHeaderTableToExport.push(obj);
          }
        }
        if (i) {
          //console.log(product.slice(product.indexOf("---") + 4));
          //this.newRowTableToExport[i].order[i].count = "11111";
          console.log(i);
        }
      }
      this.exportOrderItems = this.newHeaderTableToExport;
      setTimeout(
        () => this.downloadExportFile("exportOrderItemsTableId"),
        1000
      );
    },
    downloadExportFile(tableId) {
      let htmltable = document.getElementById(tableId);
      let html = htmltable.outerHTML;
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
      downloadLink.download = "Выгрузка заказов на молочную продукцию" + ".xls";

      document.body.appendChild(downloadLink);
      downloadLink.click();
      document.body.removeChild(downloadLink);
      //this.newHeaderTableToExport = [];
      //this.newRowTableToExport = [];
    },
  },
  mounted() {
    this.getUserInfo();
    this.getStoreOrders(95, 857);
  },
};
</script>

<style type="text/css">
.exportOrderItems {
  display: none1;
}
</style>