<template>
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
            <v-col cols="4">
              <v-text-field :value="task.TEMA_OPYTA" readonly></v-text-field>
            </v-col>
            <v-col cols="2">
              <v-subheader dense class="mt-2">Цели опыта:</v-subheader>
            </v-col>
            <v-col cols="4">
              <v-text-field :value="task.TSELI_OPYTA" readonly></v-text-field>
            </v-col>
          </v-row>
          <v-row class="mb-n8">
            <v-col cols="2">
              <v-subheader dense class="mt-2">Задачи опыта:</v-subheader>
            </v-col>
            <v-col cols="4">
              <v-text-field :value="task.ZADACHI_OPYTA" readonly></v-text-field>
            </v-col>
            <v-col cols="2">
              <v-subheader dense class="mt-2">Объект опыта:</v-subheader>
            </v-col>
            <v-col cols="4">
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
          <v-row class="mb-n8">
            <v-col cols="2">
              <v-subheader class="mt-2">Контактная информация:</v-subheader>
            </v-col>
            <v-col cols="10">
              <v-textarea :value="task.KONTAKTNAYA_INFORMATSIYA" readonly />
            </v-col>
          </v-row>
          <v-row class="mb-n8">
            <v-col cols="2">
              <v-subheader class="mt-2">Реквизиты организации:</v-subheader>
            </v-col>
            <v-col cols="10">
              <v-textarea :value="task.REKVIZITY_ORGANIZATSII" readonly />
            </v-col>
          </v-row>


          <!--<v-row class="mb-n8" v-for="t in taskInfoNew" :key="t.text">
            <v-col cols="2">
              <v-subheader class="mt-2">{{t.text}}</v-subheader>
            </v-col>
            <v-col cols="10">
              <v-text-field :value="t.value" readonly />
            </v-col>
          </v-row>-->
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="dialog = false"> Понятно </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <h1>{{ $router.currentRoute.name }}</h1>
    <hr />
    <v-row>
      <v-col cols="3" v-for="card in cards" :key="card.name">
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
                    <v-text class="title ml-1">{{ card.value }}</v-text>
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col cols="4">
              <v-progress-circular
                :rotate="-90"
                :size="100"
                :width="15"
                :value="(card.value / items.length) * 100 + '%'"
                :color="card.color"
              >
                <v-card-title class="headline mb-1">
                  {{ (card.value / items.length) * 100 + "%" }}</v-card-title
                >
              </v-progress-circular>
            </v-col>
          </v-row>
        </v-card>
      </v-col>
    </v-row>
    <hr class="mb-6" />
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
        <template v-slot:item.NAME="{ item }">
          <v-card-text @click="openTask(item.ID)">{{ item.NAME }}</v-card-text>
        </template>
        <template v-slot:item.STATUS="{ item }">
          <v-chip :color="getStatus(item.STATUS)" dark>
            {{ item.STATUS }}
          </v-chip>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script>
import axios from "axios";
export default {
  data: () => ({
    cards: [
      { name: "Общее количество", value: 0, item: 0, color: "blue" },
      { name: "В работе", value: 0, item: 0, color: "green" },
      { name: "На согласовании", value: 0, item: 0, color: "orange" },
      { name: "Просрочено", value: 0, item: 0, color: "red" },
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
        value: "OTVESTVENNYY",
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
    source: "./ajax/ajax_op002.php",
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
  methods: {
    getValue() {
      this.cards[0].value = this.items.length;
      let newTask = this.items.filter((getTask) =>
        getTask.STATUS.includes("Зарегистрирована заявка на опыт")
      );
      let workTask = this.items.filter((getTask) =>
        getTask.STATUS.includes("В работе")
      );
      let badTask = this.items.filter((getTask) =>
        getTask.STATUS.includes("Просроче")
      );
      this.cards[1].value = workTask.length;
      this.cards[2].value = newTask.length;
      this.cards[3].value = badTask.length;
    },
    getStatus(status) {
      if (status == "Зарегистрирована заявка на опыт") return "orange";
      else if (status == "Просрочен") return "red";
      else return "green";
    },
    openTask(id) {
      this.dialog = true;
      //this.taskInfo = this.items.filter((getTask) => getTask.ID.includes(id));
      axios
        .get(this.source, {
          headers: {
            "Content-Type": "application/json; charset=utf-8",
          },
          params: {
            getTask: id,
          },
        })
        .then((response) => (this.taskInfo = response.data,
        this.taskInfoNew[0].value = response.data[0]["CREATED_BY"],
        this.taskInfoNew[1].value = response.data[0]["NAZVANIE_ORGANIZATSII"]
        ));
    },
  },
};
</script>

<style>
</style>