<template>
  <v-container fluid>
    <TitleService />
    <form id="nsi002" name="nsi002" action="submit" novalidate>
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
              :input="sendData()"
              :sendButtonDisable="sendButtonDisable"
              ajax="./ajax/ajax_nsi002.php"
            />
          </v-card>
        </v-row>
      </v-card>
    </form>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
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
        id: 100,
        title: "type",
        name: "Вид контрагента*",
        type: "autocomplete",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        required: true,
        rule: [(value) => !!value || "Обязательное поле"],
        items: [
          {
            NAME: "Юридическое лицо",
            ID: [
              0, 1, 2, 3, 4, 5, 9, 10, 13, 14, 19, 20, 21, 22,
              23, 24, 25, 32,
            ],
          },
          {
            NAME: "Физическое лицо",
            ID: [
              0, 33, 34, 35, 4, 5, 9, 17, 13, 14, 19, 20, 21, 22,
              23, 24, 25, 32,
            ],
          },
          {
            NAME: "Обособленное подразделение",
            ID: [
              0, 1, 2, 3, 4, 5, 9, 10, 13, 14, 19, 20, 21, 22,
              23, 24, 25, 32,
            ],
          },
          {
            NAME: "Государственный орган",
            ID: [
              0, 1, 2, 3, 4, 5, 7, 8, 9, 10, 12, 13, 14, 19, 20, 21,
              22, 23, 24, 25, 32,
            ],
          },
        ],
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 101,
        title: "resident",
        name: "Резидент РФ",
        type: "switch",
        value: true,
        cs: "12",
        sm: "6",
        md: "6",
        outlined: true,
        dense: true,
        solo: true,
        class: "mt-1",
      },
    ],
    items: [
      {
        id: 0,
        title: "db",
        name: "База/базы, где необходим контрагент*",
        type: "autocomplete",
        items: [
          { NAME: "Агрокомплекс" },
          { NAME: "Управление холдингом" },
          { NAME: "Документооборот" },
          { NAME: "Агрокомплекс (Узел ПИР)" },
          { NAME: "Весовая" },
        ],
        rule: [(value) => !!value || "Обязательное поле"],
        multiple: true,
      },
      {
        id: 1,
        value: false,
        title: "insidegroup",
        name: "Входит в АО Агрохолдинг СТЕПЬ",
        type: "switch",
        class: "mt-1",
      },
      {
        id: 2,
        title: "segment",
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
        visible: false,
        rule: [(value) => !!value || "Обязательное поле"],
      },
      {
        id: 3,
        name: "Кластер",
        title: "cluster",
        type: "autocomplete",
        items: [
          { NAME: "Краснодарский кластер" },
          { NAME: "Ростовский кластер" },
          { NAME: "Ставропольский кластер" },
          { NAME: "ПИР" },
          { NAME: "УК" },
          { NAME: "Прочее" },
        ],
        rule: [(value) => !!value || "Обязательное поле"],        
        visible: false,
      },
      {
        id: 4,
        name: "Наименование*",
        type: "string",
        required: true,
         rule: [
            (value) => !!value || "Обязательное поле",
          ]
      },
      {
        id: 5,
        name: "Полное наименование",
        title: "fullTitle",
        type: "string",
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
        name: "ИНН*",
        title: "inn",
        type: "string",
        required: true,
        rule: [
          (value) => !!value || "Обязательное поле",
          (value) => (value && value.length == 10 ) || "Количество символов 10",
        ]
      },
      {
        id: 10,
        name: "КПП*",
        type: "string",
        required: true,
        rule: [
          (value) => !!value || "Обязательное поле",
          (value) => (value && value.length == 9) || "Количество символов 9",
        ],
      },
      {
        id: 12,
        name: "Получатель платежа УФК",
        title: "recipient",
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
        id: 19,
        name: "Юридический адрес",
        title: "urAddress",
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
        name: "Телефон контрагента",
        type: "string",
      },
      {
        id: 24,
        name: "Email контрагента",
        type: "string",
      },
      {
        id: 25,
        name: "Контактное лицо",
        type: "string",
      },      
      {
        id: 32,
        name: "Описание",
        type: "textarea",
      },
      {
        id: 33,
        name: "Пайщик",
        title: "paishik",
        type: "select",
        select_arr: ["Да", "Нет"],
      },
      {
        id: 34,
        name: "Физическое лицо",
        type: "string",
      },
      {
        id: 35,
        name: "Код физического лица",
        type: "string",
      },
      {
        id: 36,
        name: "Банк",
        type: "string",
      },
      {
        id: 37,
        name: "Номер счета",
        type: "string",
      },
      {
        id: 38,
        name: "Валюта счета",
        type: "select",
        select_arr: ["CAD", "CHF", "CNY", "EUR", "GPB", "USD", "РУБ"],
      },
      {
        id: 39,
        name: "Страна регистрации",
        type: "string",
        rule: [(value) => !!value || "Обязательное поле"],
      },
      {
        id: 40,
        name: "Налоговый номер в стране регистрации (УНН/УНП/БИН/VAT и т.п.)",
        type: "string",
        rule: [(value) => !!value || "Обязательное поле"],
      },
    ],
    type: null,
    resident: true,
  }),
  created() {
    bus.$on("SelectUsr", (data) => {
      const userInput = this.inputs.find((item) => item.type === "selectUsr");
      userInput.value = data.userId;
    });
    bus.$on("resultArray", () => {
      this.checkForm();
    });
  },
  mounted() {
    this.enableValidation({
      formSelector: "#nsi002",
    });
  },
  computed: {
    computedType() {
      const type = this.inputs.find((item) => item.title === "type").value;
      return type;
    },
    computedResident() {
      const resident = this.inputs.find(
        (item) => item.title === "resident"
      ).value;
      return resident;
    },
    computedInsideGroup() {
      const insidegroup = this.inputs.find(
        (item) => item.title === "insidegroup"
      );
      if(insidegroup){
        return insidegroup.value;
      } else {
        return false;
      }
    },
  },
  watch: {
    computedType(type) {
      this.inputs.splice(2);
      this.type = type;
      this.addInput(type, this.resident);
      this.addInputRule(type);
    },
    computedResident(resident) {
      this.resident = resident;
      this.addInput(this.type, resident);
    },
    computedInsideGroup(val) {
      const segment = this.inputs.find((item) => item.title === "segment");
      const cluster = this.inputs.find((item) => item.title === "cluster");
      segment.visible = val;
      segment.required = val;
      cluster.visible = val;
      cluster.required = val;
      if(val == false){
        segment.value = "";
        cluster.value = "";
      }
    },
  },
  methods: {
    addInput(type, resident) {
      if (type && resident === true) {
        let itemsID = this.inputs[0].items.find(
          (item) => item.NAME === type
        ).ID;
        this.renderInputs(itemsID);
      }
      if (type && resident === false) {
        let itemsID = [0, 39, 4, 40, 19, 36, 37, 38, 32];
        this.renderInputs(itemsID);
      }
    },
    addInputRule(type){
      if (type === "Физическое лицо"){
        let inputInn = this.inputs.find(item => item.title === "inn"); 
        let inputPaishik = this.inputs.find(item => item.title === "paishik");
        inputInn.rule = [
          (value) => !!value || "Обязательное поле",
          (value) => (value && value.length == 12) || "Количество символов 12",
        ]
        const data = {          
          name: `${inputPaishik.name}*`,
          required: true,
          rule: [
            (value) => !!value || "Обязательное поле",
          ]
        }
        inputPaishik = this.addInputObjects(inputPaishik, data);
      } else if (type === "Обособленное подразделение"){        
        let inputFullTitle = this.inputs.find(item => item.title === "fullTitle");
        let inputUrAddress = this.inputs.find(item => item.title === "urAddress");
        const arrInput = [inputFullTitle, inputUrAddress];
        arrInput.forEach((item) => {
          const data = {
            name: `${item.name}*`,
            required: true,
            rule: [
              (value) => !!value || "Обязательное поле",
            ]
          };
          item = this.addInputObjects(item, data);
        })
      } 
    },
    addInputObjects(input, object){
      const obj = Object.assign(input, object);
      return obj;
    },
    enableValidation(formData) {
      const forms = Array.from(
        document.querySelectorAll(formData.formSelector)
      );

      forms.forEach((form) => {
        form.addEventListener("submit", (event) =>
          this.handleFormSubmit(event)
        );
        form.addEventListener("input", () =>
          this.checkForm()
        );
      });
    },
    renderInputs(arrayInputs) {
      this.sendButtonDisable = true;
      this.inputs.splice(2);
      arrayInputs.forEach((element) => {
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
    },
    handleFormSubmit(event) {
      event.preventDefault();
    },
    checkForm() {
      const input = this.inputs.filter((item) => (item.required === true));
      const valid = !input.some((item) => item.value == "");
      if (valid) {
        this.sendButtonDisable = false;
      } else {
        this.sendButtonDisable = true;
      }
    },
    sendData(){
      const formData = new FormData();
      this.inputs.forEach((element) => {
        if(element.value != null){          
          formData.append(element.id, element.value);
        }
      });
      return formData;    
    },
  },
};
</script>

<style>
</style>