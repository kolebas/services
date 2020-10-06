<template>
  <v-container>
    <v-row justify="center">
      <v-dialog v-model="dialog" persistent max-width="80%">
        <v-card>
          <v-card-title class="headline grey lighten-2" primary-title>
            <span class="headline">Добавление элемента</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col
                  :cols="item.cs"
                  :sm="item.sm"
                  :md="item.md"
                  v-for="item in itemsFormFileds"
                  :key="item.name"
                >
                  <v-text-field
                    v-if="item.type=='string'"
                    v-model="item.value"
                    :label="item.name"
                    required
                  ></v-text-field>
                  <v-select
                    v-if="item.type=='select'"
                    v-model="item.value"
                    :items="['План АФК', 'План Степь', 'Внеплан АФК', 'Внеплан Степь']"
                    :label="item.name"
                    required
                  ></v-select>
                  <v-file-input
                    v-if="item.type=='file'"
                    v-model="item.value"
                    chips
                    show-size
                    multiple
                    label="Файл"
                  ></v-file-input>
                  <v-combobox
                    v-if="item.type=='link'"
                    v-model="item.value"
                    clearable
                    label="Ссылки"
                    multiple
                  >
                    <template v-slot:selection="{ attrs, item, select, selected }">
                      <v-chip
                        v-bind="attrs"
                        :input-value="selected"
                        close
                        @click="select"
                        @click:close="remove(item)"
                      >{{ item }}&nbsp;</v-chip>
                    </template>
                  </v-combobox>
                </v-col>
              </v-row>
            </v-container>
            <small>{{msgRequiredFiled}}</small>
          </v-card-text>
          <v-divider />
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="dialog = false">Отмена</v-btn>
            <v-btn color="blue darken-1" text @click="dialog = false;saveElement()">Сохранить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
    <v-card>
      <v-card-title>
        {{title}}
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Поиск"
          single-line
          hide-details
        ></v-text-field>
        <v-btn color="primary" @click="addElement()" dark class="mb-2 ml-2">Добавить</v-btn>
      </v-card-title>
      <v-divider />
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
        sortable
        :loading="loading"
        item-key="ID"
      >
       <template v-slot:item.PROPERTY_731_VALUE="{ item }">
          <v-chip
            class="ma-1 blue lighten-4"
            outlined
            v-for="link in item.PROPERTY_731_VALUE"
            :key="link.ID"
            :href="link.PATH"
            target="_blank"
            small
          >
              <v-icon color="green" small left>mdi-file-document-outline</v-icon>
            {{link.NAME}}
          </v-chip>
        </template>
        <template v-slot:item.PROPERTY_729_VALUE="{ item }">
          <v-chip
            class="ma-1 blue lighten-4"
            outlined
            v-for="link in item.PROPERTY_729_VALUE"
            :key="link"
            :href="link"
            target="_blank"
            small
          >
              <v-icon color="primary" small left>mdi-microsoft-internet-explorer</v-icon>
            {{link}}
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
    title: "Раздел презентаций и докладов",
    headers: [
      { text: "Тема", value: "NAME", class: "blue-grey lighten-4"},
      { text: "Партнёр", value: "PROPERTY_728_VALUE", class: "blue-grey lighten-4", sortable: false },
      { text: "Контактное лицо партёра", value: "PROPERTY_724_VALUE", class: "blue-grey lighten-4", align: "center"  },
      { text: "Анотация", value: "PROPERTY_725_VALUE", class: "blue-grey lighten-4", width: "150px" },
      { text: "Решение/Выводы", value: "PROPERTY_726_VALUE", class: "blue-grey lighten-4",sortable: false  },
      { text: "Файл", value: "PROPERTY_731_VALUE", class: "blue-grey lighten-4", width: "150px"  },
      { text: "Отвественный", value: "PROPERTY_727_VALUE", class: "blue-grey lighten-4", align: "center", sortable: false  },
      { text: "Ссылка", value: "PROPERTY_729_VALUE", class: "blue-grey lighten-4", width: "150px"  },
    ],
    items: [],
    search: "",
    loading: false,
    source:
      "https://portal.ahstep.ru/ahstep/services/ajax/docs/ajax_learning.php",
    dialog: false,
    itemsFormFileds: [
      {
        name: "Тема*",
        value: "",
        cs: "12",
        sm: "6",
        md: "12",
        type: "string",
      },
      {
        name: "Партнёр*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
      },
      {
        name: "Контатное лицо(ФИО, телефон, e-mail)*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
      },
      {
        name: "Аннотация*",
        value: "",
        cs: "12",
        sm: "6",
        md: "9",
        type: "string",
      },
      {
        name: "Решение*",
        value: "",
        cs: "12",
        sm: "6",
        md: "3",
        type: "string",
      },
      {
        name: "Отвественный*",
        value: "",
        cs: "12",
        sm: "6",
        md: "3",
        type: "string",
      },
      {
        name: "Комментарий",
        value: "",
        cs: "12",
        sm: "6",
        md: "9",
        type: "string",
      },
      {
        name: "Файл",
        value: [],
        cs: "12",
        sm: "6",
        md: "6",
        type: "file",
      },
      {
        name: "Ссылки",
        value: [],
        cs: "12",
        sm: "6",
        md: "6",
        type: "link",
      },
    ],
    msgRequiredFiled: "*поля обязательные для заполнения ",
    arrElement: [],
  }),
  methods: {
    getData() {
      axios
        .get(this.source, {
          params: {
            type: "get",
          },
          headers: {
            "Content-Type": "application/json; charset=utf-8",
          },
        })
        .then((response) => {
          this.items = response.data;
        });
    },
    addElement() {
      this.dialog = true;
    },
    saveElement() {
      this.sendData("NewElement", this.itemsFormFileds);
    },
    sendData(type, data) {
        console.log(this.itemsFormFileds);
      axios({
        method: "post",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        url: this.source,
        data: {
          type: type,
          data: data,
        },
      }).then((response) => {
        if (response.status == 200) {
          this.getData();
          console.log(data);
        }
      });
    },
  },
  mounted() {
    this.getData();
  },
};
</script>

<style>
</style>