<template>
  <v-container fluid>
    <div v-if="!getAccess"><Alert /></div>
    <div v-if="getAccess">
      <DataTables
        :title="$router.currentRoute.name"
        class="mx-auto"
        :headers="orderHeaders"
        :item="orderItems"
        :dialogFields="[]"
        :addButtonOnTitle="false"
        :addTableButtons="tableButtons"
      />
    </div>
    <div class="exportOrderItems">
      <table id="exportOrderItemsTableId">
        <th>Продукт</th>
        <th>Количество</th>
        <tr v-for="item in exportOrderItems" :key="item.name">
          <th>
            {{ item.name }}
          </th>
          <td>
            {{ item.count }}
          </td>
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
    userId: "",
    userGroup: [],
    orderHeaders: [
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
  }),
  created() {
    bus.$on("exportTableToFile", (data) => {
      this.exportToFile(data);
    });
  },
  computed: {
    getAccess() {
      if (this.userGroup.includes("49")) {
        return true;
      } else {
        return false;
      }
    },
  },
  methods: {
    getStoreOrders(infoblockID, sectionID) {
      axios
        .get(
          "https://portal.ahstep.ru/ahstep/services/ajax/marketing/store.php",
          {
            params: {
              type: "getStoreOrders",
              id: infoblockID,
              sectionID: sectionID,
            },
          }
        )
        .then((response) => (this.orderItems = response.data));
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
      let newarray = [];
      for (let i = 0; inputArray.filter((item) => item.ORDER_STATUS == 'Новый').length > i; i++) {
        let order = inputArray[i].ORDER;
        for (let y = 0; order.length > y; y++) {
          let product = order[y];
          let productObject = newarray.find(
            (item) => item.name === product.slice(0, product.indexOf("---"))
          );
          if (productObject) {
            productObject.count =
              Number(productObject.count) +
              Number(product.slice(product.indexOf("---") + 4));
          } else {
            var obj = Object.assign(
              { name: product.slice(0, product.indexOf("---")) },
              { count: product.slice(product.indexOf("---") + 4) }
            );
            newarray.push(Object.assign(obj));
          }
        }
      }
      this.exportOrderItems = newarray;
      setTimeout(() => this.downloadExportFile("exportOrderItemsTableId"), 1000);
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
  display: none;
}
</style>