<template>
  <v-app class="root">
    <v-main>
      <v-container fluid v-if="getRouteService == '/'" class="mt-n2">
        <v-card>
          <v-tabs>
            <v-tab
              v-for="tab in getTabs"
              :key="tab.text"
              @click="itemclk(tab.lnk, tab.route)"
              >{{ tab.text }}</v-tab
            >
          </v-tabs>
        </v-card>
        <v-divider />
      </v-container>
      <v-alert
        v-if="top_bar"
        class="ml-4 mr-4"
        border="left"
        colored-border
        color="primary"
        elevation="2"
        dense
      >
        <v-btn
          v-for="item in items"
          :key="item.text"
          text
          @click="itemclk(item.lnk, item.route)"
        >
          <v-icon left color="green">{{ item.icon }}</v-icon>
          {{ item.text }}
        </v-btn>
        <v-btn text disabled>
          <v-icon left>mdi-file-cog</v-icon>
          {{ this.$router.currentRoute.name }}
        </v-btn>
      </v-alert>
      <router-view />
    </v-main>
  </v-app>
</template>

<script>
import axios from "axios";
export default {
  name: "App",

  data: () => ({
    usrId: "",
    usrGroup: "",
    top_bar: "",
    tabs: [
      {
        text: "Отправить заявку",
        route: "/",
      },
      {
        text: "Мои заявки",
        lnk: "../../it-uslugi/helpdesk/my_ticket.php",
      },
      {
        text: "Мои инциденты",
        lnk: "../../it-uslugi/helpdesk/",
      },
      {
        text: "Заявки НСИ",
        lnk: "../../it-uslugi/vse-zayavki.php",
      },
      {
        text: "Панель управления 1С",
        route: "/dashboard",
        permissionGroup: ["1", "48"],
      },
      {
        text: "AdminPanel",
        lnk: "../../it-uslugi/adminpanel.php",
        permissionGroup: ["1", "18", "19", "20", "21", "26"],
      },      
      {
        text: "RFC",
        lnk: "../../it-uslugi/uslugi/soglasovanie-izmeneniy.php",
        permissionGroup: ["1", "36"],
      },
      {
        text: "Отчеты по задачам",
        lnk: "../../ahstep/reports/dashboard/",
        permissionGroup: ["1", "53"],
      },
      
      /*{
        text: "1С Поддержка",
        lnk: "../../it-uslugi/1c-support.php",
        permissionGroup: ["1"],
      },*/
    ],
  }),
  mounted() {
    axios
      .get("./ajax/ajax_usr.php", {
        auth: {},
      })
      .then(
        (response) => (
          (this.usrId = response.data[0]["ID"]),
          (this.usrGroup = response.data[0]["GROUP"])
        )
      );
  },
  computed: {
    getTabs() {
      let arrTabs = [];
      this.tabs.forEach((element) => {
        if (element.permissionGroup == null) {
          arrTabs.push(element);
        } else {
          element.permissionGroup.forEach((elementPG) => {
            for (let i = 0; i < this.usrGroup.length; i++) {
              if (elementPG == this.usrGroup[i]) {
                arrTabs.push(element);
              }
            }
          });
        }
      });
      return arrTabs;
    },
    getRouteService() {
      return this.$route.path;
    },
  },
  methods: {
    itemclk: function (lnk, route) {
      if (route != null) {
        this.$router.push(route);
      } else {
        document.location.href = lnk;
      }
    },
  },
};
</script>

<style>
body {
  overflow: unset !important;
}
/*.bx-layout-inner-inner-cont {
    display: none;
}
*/
.workarea-content-paddings {
  padding: 0px 0px 0px;
}
#uiToolbarContainer {
  display: none;
}
.root {
  min-height: 85vh;
}
.v-breadcrumbs {
  background: #f5f5f5;
  margin-left: 1% !important;
  margin-right: 1% !important;
  margin-top: 10px !important;
  margin-bottom: 10px !important;
  border-radius: 10px !important;
}
.bx-layout-inner-inner-top-row {
  display: none;
}
#workarea-content {
  background-color: rgba(0, 0, 0, 0);
}
.theme--light.v-application {
  background: none !important;
  color: rgba(0, 0, 0, 0.87);
}
</style>