<template>
  <v-container>
    <v-row>
      <v-card max-width="55%" raised class="mx-auto" color="grey lighten-4">
        <v-card-text class="pa-0">
          <p class="text-center pt-4 headline text--primary">
            {{ title }}
          </p>
          <p class="subtitle-1 font-weight-medium mx-8">
            {{ sub_message }}
            <v-btn @click="clkmyreq()" color="green lighten-2 white--text" small
              ><v-icon left dark>mdi-format-list-bulleted</v-icon> Мои
              заявки</v-btn
            >
          </p>
        </v-card-text>

        <v-expansion-panels :inset="inset" :focusable="focusable" :tile="tile">
          <v-expansion-panel v-for="(cat, i) in cats" :key="i">
            <v-expansion-panel-header v-if="cat.status != 'dev'" class="py-0">
              <v-col cols="1" class="px-0">
                <img :src="require('../assets/img/' + cat.img)" />
              </v-col>
              <v-col cols="10" class="px-0">
                <p class="subtitle-1 font-weight-medium mb-0">
                  {{ cat.name }}
                </p>
              </v-col>
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-list>
                <v-list-item-group v-for="(item, i) in cat.items" :key="i">
                  <template>
                    <v-list-item
                      @click="itemclk(item.lnk, item.route)"
                      v-if="
                        (condition && item.status == 'dev') ||
                        item.status != 'dev'
                      "
                    >
                      <v-list-item-icon>
                        <img :src="require('../assets/img/' + item.img)" />
                      </v-list-item-icon>
                      <v-list-item-content>
                        <v-list-item-title v-text="item.name" />
                      </v-list-item-content>
                      <v-list-item-action>
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn icon v-bind="attrs" v-on="on">
                              <v-icon color="grey"
                                >mdi-information-outline</v-icon
                              >
                            </v-btn>
                          </template>
                          <span>{{ item.info }}</span>
                        </v-tooltip>
                      </v-list-item-action>
                    </v-list-item>
                  </template>
                  <v-divider />
                </v-list-item-group>
              </v-list>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-card>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";
export default {
  data: () => ({
    usrid: "",
    usrGroup: "",
    usrSocGroup: "",
    title: "Заявки на доступ к ИТ услугам",
    sub_message:
      "Для получения доступа к сервису или услуге, выберите нужную категорию, а затем услугу. После заполнения необходимых полей формы, заявка будет отправлена на согласование ответственным сотрудникам. Статус заявки вы можете отслеживать в разделе.",
    cats: [
      {
        name: "Нормативно-справочная информация",
        img: "list.png",
        items: [
          {
            name: "Редактирование справочника номенклатуры",
            img: "lists.png",
            lnk: "../../it-uslugi/uslugi/nsi-001.php",
          },
        ],
      },
      {
        name: "Заявки пользователя",
        img: "user.png",
        items: [
          {
            name: "Новый пользователь",
            img: "user_add.png",
            info:
              "Создание нового пользователь в информационных системах общества.",
            route: "/nu001",
          },
          {
            name: "Удаленный доступ (VPN)",
            img: "vpn.png",
            info:
              "Предоставление удаленного доступа с мобильного, лмчного или корпоративного устройства к информационным системам общества",
            route: "/ns001",
          },
          {
            name: "Служебная сотовая связь",
            img: "sim_add.png",
            info: "Выдача корпоративной SIM карты",
            route: "/ph001",
          },
          {
            name: "Установка программного обеспечения",
            img: "soft.png",
            info:
              "Установка програмнного обечпечения на корпоративный компьютер",
            route: "/sf001",
          },
          {
            name: "Отключение доступов",
            img: "user_del.png",
            info: "Блокировние пользователя в информационных систмах",
            route: "/ar001",
          },
          {
            name: "Запись онлайн мероприятий (вебинар, обучение)",
            img: "rc001.png",
            info: "Запись видео и аудио мероприятий",
            route: "/rc001",
          },
          {
            name: "Заявка на предоставление дополнительных доступов",
            img: "admin.png",
            route: "/adm001",
            info:
              "Предоставление высокопривеллегированнх прав к информационным системам",
            status: "dev",
          },
        ],
      },
      {
        name: "Заявки 1С",
        img: "1s.png",
        items: [
          {
            name: "Предоставление доступа к 1С",
            img: "1s.png",
            info:
              "Предоставление прав просмотра или редактрования в базах данных 1С",
            route: "/1c001",
          },
          {
            name: "Запрос на доработку 1С",
            img: "1s.png",
            info: "Запрос на доработку функционала в базах данных 1С",
            route: "/1c002",
          },
        ],
      },
      {
        name: "Заявки на закупку",
        img: "soft.png",
        items: [
          {
            name: "Закупка техники/оборудования/программного обеспечения",
            img: "computer_add.png",
            route: "/rb001",
          },
        ],
      },
      {
        name: "Заявки на каталог (папку)",
        img: "folders.png",
        items: [
          {
            name: "Создание нового каталога (папки)",
            img: "folder_add.png",
            route: "/nc002",
          },
          {
            name: "Доступ к каталогу (папке)",
            img: "folder_rep.png",
            route: "/nc003",
          },
          {
            name: "Создание/изменение сетевого каталога (внешние сотрудники)",
            img: "folder_add.png",
            lnk: "../request/nc-004",
          },
          {
            name: "Заявка на увеличение квоты для сетевого каталога",
            img: "folder_chg.png",
            route: "/nc005",
          },
        ],
      },
      {
        name: "Почта",
        img: "mails.png",
        items: [
          {
            name: "Создание/изменение адреса почтовой рассылки",
            img: "mails.png",
            lnk: "../../it-uslugi/uslugi/ml-001.php",
          },
        ],
      },
      {
        name: "Заявки в отдел мониторинга",
        status: "active",
        img: "mon.png",
        items: [
          {
            name: "Корректировка данных в путевых листах 1С",
            img: "mon_corr.png",
            route: "/ms001",
          },
          {
            name: "Предоставление доступа к программам мониторинга",
            img: "mon_accs.png",
            route: "/ms002",
          },
          {
            name: "Заявка о неисправности телематического оборудования",
            img: "mon_brkn.png",
            route: "/ms003",
          },
          {
            name:
              "Добавление основного средства в папку «Техника для выработки»",
            img: "ms004.png",
            route: "/ms004",
          },
        ],
      },
      {
        name: "Заявки в производственный комплекс",
        status: "active",
        img: "prod.png",
        items: [
          {
            name: "Заявка на проведение агрономического опыта",
            img: "op001.png",
            route: "/op001",
          },
        ],
      },
    ],
    inset: true,
    focusable: true,
    tile: true,
  }),
  methods: {
    itemclk: function (lnk, route) {
      if (route != null) {
        this.$router.push(route);
      } else {
        document.location.href = lnk;
      }
    },
    clkmyreq: function () {
      document.location.href = "/it-uslugi/helpdesk/my_ticket.php";
    },
  },
  mounted() {
    axios
      .get("./ajax/ajax_usr.php", {
        auth: {},
      })
      .then(
        (response) => (
          (this.usrid = response.data[0]["ID"]),
          (this.usrGroup = response.data[0]["GROUP"]),
          (this.usrSocGroup = response.data[0]["SocGROUP"])
        )
      );
  },
  computed: {
    condition() {
      return this.usrid == 1 || this.usrSocGroup != "";
    },
  },
};
</script>
