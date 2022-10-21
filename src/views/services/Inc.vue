<template>
  <v-container fluid>
    <TitleService />
    <form id="inc" name="inc" action="submit" novalidate>
      <v-card min-height="800px" class="py-12">
        <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
        <v-card width="55%" raised class="mx-auto" color="grey lighten-4">
          <v-card-text class="pa-0">
            <p class="text-center pt-4 headline text--primary">{{ title }}</p>
            <p class="subtitle-1 font-weight-medium mx-8">{{ sub_title }}</p>
          </v-card-text>
          <v-divider />
          <br />
          <v-row>
            <v-col cols="11" class="mx-auto">
              <Input :arrInput="inputs" />
            </v-col>
          </v-row>
          <v-divider />
          <v-card-actions class="py-4">
            <div class="mx-auto">
              <v-btn
                class="mx-1"
                :loading="btnLoader"
                :disabled="sendButtonDisable"
                color="green lighten-2 white--text"
                @click="formSend()"
              >
                Отправить
              </v-btn>
              <v-btn class="mx-1" @click="formCancl()"> Отмена </v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-card>
    </form>    
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import Api from "@/components/Api.js"
import Validate from "@/components/Validate.js"
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import axios from "axios";
import TitleService from "@/components/TitleService.vue";
import Input from "@/components/Input.vue";
export default {
  components: {
    DialogAfterSendForm,
    TitleService,
    Input,
  },
  data: () => ({
    title: "Новое обращение",
    sendButtonDisable: true,
    sub_title:
      'Если у вас возникли проблемы с используемым оборудованием, программным обеспечением или вы нуждаетесь в консультации специалистов, заполните предлагаемую форму и нажмите кнопку "ОТПРАВИТЬ".',
    dialog: false,
    dialogMessage: "",
    btnLoader: false,
    inputs: [],
    fields: [
      {
        label: "Краткое описание",
        title: "title_inc",
        value: "",
        required: true,
        type: "string",
        rule: [
          (value) => !!value || "Обязательное поле",
          (value) => (value && value.length > 8) || "Минимальное количество символов 8",
          ],
      },
      {
        label: "Кто обратился",
        title: "responsible",
        value: "",
        class: "mt-n2",
        type: "selectUsr",
        visible: false,
      },
      {
        label: "Проблема связана с 1С",
        title: "switch_1c",
        value: false,
        type: "switch",
        class: "mt-n2",
      },      
      {
        label: "Проблема связана с НСИ",
        title: "switch_nsi",
        value: false,
        type: "switch",
        class: "mt-n2",
      },
      {
        label: "Направление 1С",
        title: "type_1c_slct",
        value: "",
        type: "select",
        select_arr: [        ],
        rule: [],
        visible: false,
      },
      {
        label: "Справочник",
        title: "type_nsi_slct",
        value: "",
        type: "select",
        select_arr: [
          "Агрегаты",
          "Банки",
          "Виды работ",
          "Договоры",
          "Классификатор автотранспорта и сх техники",
          "Классификатор единиц измерения",
          "Классификатор оборудования (прицепы и навесное оборудование)",
          "Контрагенты",
          "Номенклатура",
          "Номенклатурные группы",
          "Основные средства",
          "Прочие доходы и расходы",
          "Склады",
          "Статьи движения денежных средств",
          "Статьи затрат",
          "Физические лица",
          "Отсутствует в списке",
        ],
        rule: [],
        visible: false,
      },
      {
        id: 0,
        title: "db",
        label: "Список БД 1С",
        type: "autocomplete",
        items: [],
        rule: [(value) => !!value || "Обязательное поле"],
        visible: false,
        multiple: true,
      },
      {
        label: "Подробное описание",
        title: "cmnt",
        value: "",
        class: "mt-n2",
        type: "textarea",
      },
      {
        label: "",
        value: "",
        type: "file",
      },
    ],
    userData: {},
    usrid: null,
    usrGroup: [],
    valid: null,
    api: new Api()
  }),
  created() {
    this.createInputs();    
    this.getUserData();
    this.valid = new Validate(this.checkTitle);
    this.getDb();
    this.get1cDirections();
    bus.$on("inputFile", (data) => {
      this.file = data;
      this.inputs.find((item) => item.type === "file").value = data;
    });
    bus.$on("SelectUsr", (data) => {
      const userInput = this.inputs.find((item) => item.type === "selectUsr");
      userInput.value = data;
    });
    bus.$on("resultArray", (data) => {
      this.sendButtonDisable = !this.valid.checkForm(data);
    });
    bus.$on("valueAutocomplete", () => {      
      this.sendButtonDisable = !this.valid.checkForm(this.inputs);
    });
  },
   computed: {
    condition() {
      return this.usrid == 1 || this.usrGroup.includes("22");
    },
    getSwitch1C(){
      return this.computedInput("switch_1c");
    },
    getSwitchNsi(){
      return this.computedInput("switch_nsi");
    },
  },
  watch:{
    getSwitch1C(value){
      const inputs = ["type_1c_slct"];
      if(value){        
        this.toggleSwitchs("switch_1c", inputs, ["type_nsi_slct", "db"], value);
      } else { 
        this.removeInput(inputs);
      }
    },
    getSwitchNsi(value){
      const inputs = ["type_nsi_slct", "db"];
      if(value){        
        this.toggleSwitchs("switch_nsi", inputs, ["type_1c_slct"], value);
      } else {
        this.removeInput(inputs);
      } 
    },
    condition(value){
      const userField = this.inputs.find(item => item.title === "responsible");
      userField.visible = value;
    }
  },
  methods: {
    toggleSwitchs(sw, inputAdd, inputRem, val){
      const switchArr = this.inputs.filter(item => item.type === "switch" && item.title != sw);
      switchArr.forEach(item => item.value = false);
      if(inputAdd){
        inputAdd.forEach(elem => {
          const selectInput = this.inputs.find(item => item.title === elem);        
          selectInput.visible = val;
          selectInput.required = val;
          selectInput.rule = [
              (value) => !!value || "Обязательное поле",
          ];
        })
      }
      this.removeInput(inputRem);
      this.sendButtonDisable = !this.valid.checkForm(this.inputs);  
    },
    computedInput(title){
      if(this.inputs.length > 0){
        const sw = this.inputs.find(item => item.title === title);
        return sw.value;
      } else {
        return false;
      }
    },
    removeInput(inputs){              
        inputs.forEach(elem => {
          const selectInput = this.inputs.find(item => item.title === elem);        
          selectInput.visible = false;
          selectInput.required = false;
          selectInput.value = "";
        })      
      this.sendButtonDisable = !this.valid.checkForm(this.inputs);   
    },
    checkTitle() {
      const title = this.inputs.find(item => item.title === "title_inc");
      if(title.value.length > 8) {
        return true; 
      } else {
        return false;
      }
    }, 
    formSend() {
      this.btnLoader = true;
      var formData = new FormData();
      this.inputs.forEach((element) => {
        formData.append(element.title, element.value);
      });
      const fileInput = this.inputs.find(item => item.type === "file").value
      for (var i = 0; i < fileInput.length; i++) {
        let file = fileInput[i];
        formData.append("file[" + i + "]", file);
      }
      axios({
        method: "post",
        withCredentials: true,
        headers: { "Content-Type": "multipart/form-data" },
        url: "./ajax/ajax_inc.php",
        data: formData,
      })
        .then((response) => {
          if (response.status == 200) {
            this.dialog = true;
            this.dialogMessage = "Ваша заявка успешно зарегистрирована.";
            this.btnLoader = false;
          }
        })
        .catch((error) => {
          this.dialog = true;
          this.dialogMessage = `Произошла ошибка ${error}`;
        });
    },
    formCancl() {
      this.$router.go(-1);
    },
    getDb(){      
      this.api.getData({
        url: "./ajax/ajax_1c001.php",
        headers: {
          'Content-Type': 'application/json'
        },
        method: "GET",
        params: {
          type: "getDB"
        }
      })
      .then(response => {
        const inputDb = this.inputs.find(item => item.title === "db");
        const db = [];
        response.data.forEach(item => {
          db.push({"NAME": item});
        })
        inputDb.items = db;
      })
      .catch((error) => {
        this.dialog = true;
        this.dialogMessage = `Произошла ошибка ${error}`;
      }); 
    },
    get1cDirections(){      
      this.api.getData({
        url: "./ajax/ajax_services.php",
        //url: "https://portal.ahstep.ru/ahstep/services/ajax/ajax_services.php",
        headers: {
          'Content-Type': 'application/json'
        },
        method: "GET",
        params: {
          type: "get1cReposibles",
          ib_id: 125
        }
      })
      .then(response => {
        const directios = this.fields.find(item => item.title === "type_1c_slct");
        response.data.forEach(item => {
          directios.select_arr.push(item.TITLE);
        })
      })
      .catch((error) => {
        this.dialog = true;
        this.dialogMessage = `Произошла ошибка ${error}`;
      });
    },
    getUserData() {
      axios
        .get("./ajax/ajax_usr.php", {
          auth: {},
        })
        .then(
          (response) => (
            (this.usrid = response.data[0]["ID"]),
            (this.usrGroup = response.data[0]["GROUP"])
          )
        );
    },
    createInputs(){
        this.fields.forEach((element) => {
          const obj = {
            value: "",
            cs: "12",
            sm: "12",
            md: "12",
            outlined: true,
            dense: true,
            solo: true,
            err: "",
          };
        let item = Object.assign(obj, element);
        this.inputs.push(item);
      });
    },
  },
};
</script>