<template>
  <v-app>
    <v-main>
      <v-container v-if="usrId == '1'" class="mt-n2">
        <v-tabs>
          <v-tab
            v-for="tab in getTabs"
            :key="tab.text"
            @click="itemclk(tab.lnk, tab.route)"
            >{{ tab.text }}</v-tab
          >
        </v-tabs>
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
      { text: "Отправить заявку", route: "/" },
      {
        text: "Мои заявки",
        lnk: "../../it-uslugi/helpdesk/my_ticket.php",
      },
      {
        text: "Заявки НСИ",
        lnk: "../../it-uslugi/vse-zayavki.php",
      },
      {
        text: "AdminPanel",
        lnk: "../../it-uslugi/adminpanel.php",
        permissionGroup: ["1", "18", "19", "20", "21", "26"],
      },
      {
        text: "1С Поддержка",
        lnk: "../../it-uslugi/1c-support.php",
        permissionGroup: ["1"],
      },
      {
        text: "RFC",
        lnk: "../../it-uslugi/bp.php",
        permissionGroup: ["1", "36"],
      },
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
      return this.tabs.filter(
        (getTab) =>
          getTab.permissionGroup == null ||
          getTab.permissionGroup.includes("this.usrGroup")
      );
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
#uiToolbarContainer {
  display: none;
}
.v-breadcrumbs {
  background: #f5f5f5;
  margin-left: 1% !important;
  margin-right: 1% !important;
  margin-top: 10px !important;
  margin-bottom: 10px !important;
  border-radius: 10px !important;
}
</style>
