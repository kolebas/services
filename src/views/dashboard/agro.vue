<template>
  <div class="grey lighten-4">
    <v-container>
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
                <v-text-field
                  :value="task.ZADACHI_OPYTA"
                  readonly
                ></v-text-field>
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
            <v-row class="mb-n8">
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
            <br />
            <v-container>
              <v-btn
                color="blue-grey lighten-4"
                class="mx-auto"
                small
                @click="(addInfo = !addInfo), (log = false)"
                ><v-icon color="indigo" left>mdi-format-list-bulleted</v-icon
                >Дополнительная информация</v-btn
              >
              <v-btn
                color="blue-grey lighten-4"
                class="ml-2"
                small
                @click="(log = !log), (addInfo = false)"
                ><v-icon color="info" left>mdi-history</v-icon>История
                заявки</v-btn
              >
            </v-container>
            <v-expand-transition>
              <v-container v-if="addInfo" class="mb-n6">
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
              <v-container class="mb-n12">
                <v-row v-if="log">
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
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="dialog = false">
              Понятно
            </v-btn>
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
                height="215%"
                elevation="1"
                outlined
              >
                <v-row>
                  <v-col cols="10">
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
                  <v-col cols="2">
                    <v-container class="px-0">
                      <v-progress-circular
                        :rotate="-90"
                        :size="100"
                        :width="15"
                        :value="(card.value / items.length) * 100 + '%'"
                        :color="card.color"
                      >
                        <v-card-title class="headline mb-1">
                          {{
                            ((card.value / items.length) * 100 + "").split(
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
                        :value="(card.value / items.length) * 100 + '%'"
                        :color="card.color"
                      >
                        <v-card-title class="headline mb-1">
                          {{
                            ((card.value / items.length) * 100 + "").split(
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
          <v-toolbar-title>Агрономические опыты</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Поиск"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="items" :search="search">
          <template v-slot:[`item.NAME`]="{ item }">
            <v-card-text @click="openTask(item.ID)">{{
              item.NAME
            }}</v-card-text>
          </template>
          <template v-slot:[`item.STATUS`]="{ item }">
            <v-chip :color="getStatus(item.STATUS)" dark>
              {{ item.STATUS }}
            </v-chip>
          </template>
          <template v-slot:[`item.RESPONSIBLE`]="{ item }">
            <v-chip  :href="'../../company/personal/user/'+ item.RESPONSIBLEID + '/'" v-if="item.RESPONSIBLE != ' '"  color="grey" outlined>
              <v-avatar left>
                <img v-if="item.PHOTO" :src="item.PHOTO" />
                <v-icon v-else>mdi-account-circle</v-icon>
              </v-avatar><span>{{ item.RESPONSIBLE }}</span>
            </v-chip>
          </template>
        </v-data-table>
      </v-card>
    </v-container>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data: () => ({
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
    ],
    headers: [
      {
        text: "Название",
        value: "NAME",
      },
      {
        text: "Автор",
        value: "CREATED_BY",
      },
      {
        text: "Дата",
        value: "DATE",
      },
      {
        text: "Статус",
        value: "STATUS",
      },
      {
        text: "Отвественный",
        value: "RESPONSIBLE",
      },
    ],
    items: [],
    search: "",
    dialog: false,
    taskInfo: [],
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
  }),
  created() {
    axios
      .get(this.source, {
        headers: {
          "Content-Type": "application/json; charset=utf-8",
        },
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
      this.cards[1].value = workTask.length;
      this.cards[2].value = newTask.length;
      this.cards[3].value = badTask.length;
      this.cards[4].value = doneTask.length;
    },
    getStatus(status) {
      if (
        status == "Зарегистрирована заявка на опыт" ||
        status == "Согласование"
      )
        return "orange";
      else if (status == "Просрочен") return "red";
      else if (status == "Проведён") return "grey";
      else return "green";
    },
    openTask(id) {
      this.dialog = true;
      axios
        .get(this.source, {
          headers: {
            "Content-Type": "application/json; charset=utf-8",
          },
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
  },
};
</script>

<style>
</style>