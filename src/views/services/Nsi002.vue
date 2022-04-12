<template>
  <v-container fluid>
    <TitleService />
    <form id="nsi002" action="" novalidate>
      <v-card min-height="800px" class="py-12">
        <v-row>
          <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
            <RqCardTitle
              :title="$router.currentRoute.name"
              :sub_message="sub_message"
            ></RqCardTitle>
            <hr />
            <Input :arrInput="inputs" />
            <v-card-text> * Поля обязательные для заполнения </v-card-text>
            <hr />
            <Buttons
              :input="null"
              :sendButtonDisable="sendButtonDisable"
              ajax="./ajax/ajax_nsi.php"
            />
          </v-card>
        </v-row>
      </v-card>
    </form>
  </v-container>
</template>

<script>
//import axios from "axios";
import RqCardTitle from "@/components/RqCardTitle";
import Input from "@/components/Input.vue";
import TitleService from "@/components/TitleService.vue";
import Buttons from "@/components/Buttons.vue";
export default {
  components: {
    RqCardTitle,
    TitleService,
    Input,
    Buttons,
  },
  data: () => ({
    sub_message: "Вы сможете отслеживать статус заявки в разделе",
    sendButtonDisable: true,
    inputs: [
      {
        title: "type",
        name: "Тип контрагента",
        type: "autocomplete",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        items: [
          {
            NAME: "Юридическое лицо",
            ID: [
              0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
              19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33,
            ],
          },
          { NAME: "Физическое лицо", ID: [0, 1, 2, 3] },
          {
            NAME: "Обособленное подразделение",
            ID: [
              0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
              19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33,
            ],
          },
          { NAME: "Государственный орган", ID: [0, 1, 2, 3, 5, 6, 7] },
        ],
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        title: "resident",
        name: "Тип контрагента",
        type: "autocomplete",
        value: "Резидент РФ",
        cs: "12",
        sm: "6",
        md: "6",
        items: [
          { NAME: "Резидент РФ", ID: [0, 1, 2, 3] },
          { NAME: "Нерезидент", ID: [0, 1, 2, 3] },
        ],
        outlined: true,
        dense: true,
        solo: true,
      },
    ],
    items: [
      {
        id: 0,
        title: "db",
        name: "Список БД 1С:",
        type: "autocomplete",
        items: [
          { NAME: "Агрокомплекс" },
          { NAME: "Управление холдингом" },
          { NAME: "Документооборот" },
        ],
        multiple: true,
      },
      {
        id: 1,
        name: "Внутригрупповой",
        type: "switch",
      },
      {
        id: 2,
        name: "Сегмент",
        type: "autocomplete",
        items: [
          { NAME: "Бакалея" },
          { NAME: "Животноводство" },
          { NAME: "Кондитерское производство" },
          { NAME: "МолЗавод" },
          { NAME: "Овощеводство" },
          { NAME: "Садоводство" },
          { NAME: "ПИР" },
          { NAME: "Растениеводство" },
          { NAME: "Трейдинг" },
          { NAME: "Трейдинг(нишевые)" },
          { NAME: "Овощеводство" },
          { NAME: "Садоводство" },
          { NAME: "Фреш" },
          { NAME: "Элеваторы" },
          { NAME: "УК" },
          { NAME: "Прочее" },
        ],
      },
      {
        id: 3,
        name: "Кластер",
        type: "autocomplete",
        items: [
          { NAME: "Краснодарский кластер" },
          { NAME: "Ростовский кластер" },
          { NAME: "Ставропольский кластер" },
          { NAME: "ПИР" },
          { NAME: "УК" },
          { NAME: "Прочее" },
        ],
      },
      {
        id: 4,
        name: "Краткое наименование",
        type: "string",
      },
      {
        id: 5,
        name: "Полное наименование",
        type: "string",
      },
      {
        id: 6,
        name: "Сети/оптовик",
        type: "select",
        select_arr: ["Сети", "Оптовик"],
      },
      {
        id: 7,
        name: "Вид государственого органа",
        type: "select",
        select_arr: ["Налоговый орган", "Орган ФСС", "Орган ПФР", "Прочий"],
      },
      {
        id: 8,
        name: "Код государственого органа",
        type: "string",
      },
      {
        id: 9,
        name: "ИНН",
        type: "string",
      },
      {
        id: 10,
        name: "КПП",
        type: "string",
      },
      {
        id: 11,
        name: "Бюджетная организация",
        type: "switch",
      },
      {
        id: 12,
        name: "Лицевой счёт",
        type: "string",
      },
      {
        id: 13,
        name: "Расчетный счёт",
        type: "string",
      },
      {
        id: 14,
        name: "БИК",
        type: "string",
      },
      {
        id: 15,
        name: "ИНН головного контрагента",
        type: "string",
      },
      {
        id: 16,
        name: "Головной",
        type: "switch",
      },
      {
        id: 17,
        name: "ОКПО",
        type: "string",
      },
      {
        id: 18,
        name: "ОГРН",
        type: "string",
      },
      {
        id: 19,
        name: "Юридический адрес",
        type: "textarea",
      },
      {
        id: 20,
        name: "Фактический адрес",
        type: "textarea",
      },
      {
        id: 21,
        name: "Почтовый адрес",
        type: "textarea",
      },
      {
        id: 22,
        name: "Отвественный",
        type: "selectUsr",
      },
      {
        id: 23,
        name: "Телефон",
        type: "string",
      },
      {
        id: 24,
        name: "Email",
        type: "string",
      },
      {
        id: 25,
        name: "Контактное лицо",
        type: "string",
      },
      {
        id: 26,
        name: "Руководитель",
        type: "string",
      },
      {
        id: 27,
        name: "Должность руководителя",
        type: "string",
      },
      {
        id: 28,
        name: "Документ основание",
        type: "string",
      },
      {
        id: 29,
        name: "Система налогооблажения",
        type: "select",
        select_arr: ["пусто", "УСН", "ПСН", "ЕНВД", "ОСНО"],
      },
      {
        id: 30,
        name: "В Узел ПИР",
        type: "select",
        select_arr: ["Да", "Нет"],
      },
      {
        id: 31,
        name: "В весовую",
        type: "select",
        select_arr: ["Да", "Нет"],
      },
      {
        id: 32,
        name: "Описание",
        type: "textarea",
      },
    ],
  }),
  mounted() {
    this.enableValidation();
  },
  computed: {
    computedInputs() {
      const selectedInput = this.inputs.find((item) => item.title === "type");
      return selectedInput.value;
    },
  },
  watch: {
    computedInputs(val) {
      this.addInput(val);
    },
  },
  methods: {
    addInput(type) {
      if (type) {
        const itemsID = this.inputs[0].items.find(
          (item) => item.NAME === type
        ).ID;
        itemsID.forEach((element) => {
          let item = this.items.find((item) => item.id === element);
          const obj = {
            value: "",
            cs: "12",
            sm: "6",
            md: "6",
            outlined: true,
            dense: true,
            solo: true,
            err: "",
          };
          item = Object.assign(obj, item);
          this.inputs.push(item);
        });
      } else {
        this.inputs.splice(2);
      }
    },
    enableValidation() {
      const form = document.querySelector("#nsi002");
      form.addEventListener("submit", (event) => {
        event.preventDefault();
      });
    },
    sendData() {
      alert("SendData");
    },
  },
};
</script>

<style>
</style>