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
              0, 1, 2, 3, 9, 10, 4, 5, 13, 14, 19, 20, 21, 
              23, 24, 25, 49, 32, 42
            ],
          },
          {
            NAME: "Физическое лицо",
            ID: [
              0, 9, 33, 34, 35, 41, 4, 5,  17, 13, 14, 19, 20, 21, 
              23, 24, 25, 49, 32, 43, 44, 45, 46, 47, 48, 42
            ],
          },
          {
            NAME: "Самозанятый",
            ID: [
              0, 9, 33, 34, 35, 41, 4, 5,  17, 13, 14, 19, 20, 21, 
              23, 24, 25, 49, 32, 43, 44, 45, 46, 47, 48, 42
            ],
          },
          {
            NAME: "Получатель алиментов, охотник",
            ID: [
              0, 9, 33, 34, 35, 41, 4, 5,  17, 13, 14, 19, 20, 21, 
              23, 24, 25, 49, 32, 43, 44, 45, 46, 47, 48, 42
            ],
          },
          {
            NAME: "Обособленное подразделение",
            ID: [
              0, 1, 2, 3, 9, 10, 4, 5, 13, 14, 19, 20, 21, 
              23, 24, 25, 49, 32, 42
            ],
          },
          {
            NAME: "Государственный орган",
            ID: [
              0, 1, 2, 3, 9, 10, 4, 5, 7, 8, 12, 13, 14, 19, 20, 21,
              23, 24, 25, 49, 32, 42
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
        title: "shortTitle",
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
        ],                
        icon: "mdi-briefcase-search-outline",
        messages: "Для автоматического заполнения реквизитов нажмите иконку лупы с чемоданом (работает для юрлиц и ИП/ГКФХ)"
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
        title: "schet",
        type: "string",
      },
      {
        id: 14,
        name: "БИК",
        title: "bik",
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
        id: 23,
        name: "Телефон контрагента",
        title: "telephone",
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
        title: "fiz",
        type: "string",
        visible: false,
      },
      {
        id: 35,
        name: "Код физического лица",
        title: "fizCode",
        type: "string",
        visible: false,
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
      {
        id: 41,
        name: "Дата рождения",
        type: "date",
      },
      {
        id: 42,
        name: "Файл:",
        type: "file",
      },      
      {
        id: 43,
        name: "СНИЛС",
        type: "string",
      },            
      {
        id: 44,
        name: "Паспорт",
        type: "string",
      },      
      {
        id: 45,
        name: "Кем выдан паспорт",
        type: "string",
      },     
      {
        id: 46,
        name: "Код подразделения",
        type: "string",
      },     
      {
        id: 47,
        name: "Дата выдачи паспорта",
        type: "date",
      },     
      {
        id: 48,
        name: "Место рождения",
        type: "string",
      },
      {
        id: 49,
        name: "Канал сбыта",
        title: "channel",
        type: "autocomplete",
        items: [
          { NAME: "Федеральные сети" },
          { NAME: "Региональные сети" },
          { NAME: "HoReCa" },
          { NAME: "Дистрибьютор" },
          { NAME: "Экспорт" },
          { NAME: "Опт" },
          { NAME: "Розница" },
          { NAME: "АЗС" },
          { NAME: "E-com" },
          { NAME: "Внутригрупповые" },
          { NAME: "Прочие" },
        ],
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
    bus.$on("resultArray", (data) => {
      this.setPaishik(data.find(item => item.title === "paishik"))
      this.checkForm();
    });
    bus.$on("appendIconCallback", () => {
      this.getDadata();
    });
    bus.$on("inputFile", (data) => {
      this.inputs.find((item) => item.type === "file").value = data;
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
    setPaishik(paihik){
      const fiz = this.inputs.find((item) => item.title === "fiz");
      const fizCode = this.inputs.find((item) => item.title === "fizCode");
      if(paihik.value === "Да"){
        fiz.visible = true;
        fizCode.visible = true;
      } else {
        fiz.visible = false;
        fizCode.visible = false;
      }
    },
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
        this.setInputRule([inputFullTitle, inputUrAddress]);
      } else if (type === "Получатель алиментов"){
        let inputBik = this.inputs.find(item => item.title === "bik"); 
        let inputSchet = this.inputs.find(item => item.title === "schet");
        let inputInn = this.inputs.find(item => item.title === "inn"); 
        inputInn.required = false;
        inputInn.rule = [];
        inputInn.name = 'ИНН';
        this.setInputRule([inputBik, inputSchet]);
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
      const fileInput = this.inputs.find(item => item.type === "file");
      this.inputs.forEach((element) => {
        if(element.value != null){          
          formData.append(element.id, element.value);
        }
      });
      if(fileInput) {
        const files = fileInput.value;
        for (var i = 0; i < files.length; i++) {
          let file = files[i];
          formData.append("file[" + i + "]", file);
        }
      }    
      return formData;   
    },
    setInputRule(inputs){
      inputs.forEach((item) => {
        const data = {
          name: `${item.name}*`,
          required: true,
          rule: [
            (value) => !!value || "Обязательное поле",
          ]
        };
        item = this.addInputObjects(item, data);
      })
    },
    getDadata(){
      var url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party";
      var token = "f504593845086a6ff8765db38660efbe829b65ba";
      var query = this.inputs.find(item => item.title === 'inn').value;

      var options = {
          method: "POST",
          mode: "cors",
          headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "Authorization": "Token " + token
          },
          body: JSON.stringify({query: query, branch_type: 'MAIN'})
      }

    fetch(url, options)
      .then(response => response.json())
      .then((result) => {
        this.inputs.find(item => item.title === "shortTitle").value = result.suggestions[0].data.name.short_with_opf;
        this.inputs.find(item => item.title === "fullTitle").value = result.suggestions[0].data.name.full_with_opf;
        this.inputs.find(item => item.title === "urAddress").value = result.suggestions[0].data.address.unrestricted_value;
        this.inputs.find(item => item.title === "telephone").value = result.suggestions[0].data.address.phones;
        this.inputs.find(item => item.id === 10).value = result.suggestions[0].data.kpp;
        this.checkForm();
      })
      .catch(error => console.log("error", error));
    }
  },
};
</script>

<style>
</style>