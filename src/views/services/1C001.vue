<template>
  <v-container fluid>
    <TitleService />
    <form id="1c001" name="1c001" action="submit" novalidate>
      <v-card min-height="800px" class="py-12">
        <v-row>
            <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
              <RqCardTitle
                :title="$router.currentRoute.name"
                :sub_message="sub_message"
              ></RqCardTitle>
              <hr />

              <Input :arrInput="input.filter(item => item.title === 'user')" />
              <v-card outlined class="my-6 mx-6" color="grey lighten-2">
                <v-card-title class="mx-auto">
                  Куда предоставляется доступ
                </v-card-title>
                <hr />
                <Input :arrInput="input.filter(item => item.title != 'user')" />
                <v-card-text> * Поля обязательные для заполнения </v-card-text>
              </v-card>
              <hr />
              <Buttons
                :input="sendData()"
                :sendButtonDisable="sendButtonDisable"
                :ajax="source"
              />
            </v-card>
        </v-row>
      </v-card>
      </form>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import RqCardTitle from "@/components/RqCardTitle.vue";
import Input from "@/components/Input.vue";
import axios from "axios";
import TitleService from "@/components/TitleService.vue";
import Buttons from "@/components/Buttons.vue";
export default {
  components: {
    RqCardTitle,
    Input,
    TitleService,
    Buttons
  },
  data: () => ({
    sub_message:
      "В рамках исполнения данной заявки будет запущено согласование запрошенных доступов. При изменении статуса заявки, вы будете получать уведомления на электронный почтовый ящик, указанный у вас в профиле. Также вы можете отслеживать статус заявки в разделе.",
    btnLoader: false,
    btnStatus: false,
    sendButtonDisable: true,
    input: [
      {
        id: 0,
        name: "Организация*",
        title: "org",
        value: [],
        visible: true,
        items: [],
        err: "",
        multiple: true,
        cs: "12",
        sm: "6",
        md: "6",
        type: "autocomplete",
        outlined: true,
        dense: true,
        solo: true,
        required: true,
      },
      {
        id: 11,
        name: "Временный доступ:",
        value: false,
        cs: "12",
        sm: "6",
        md: "6",
        range: true,
        type: "switch",
        class: "mt-2",
      },
      {
        id: 12,
        title: "timeInterwall",
        name: "Период временного доступа:",
        value: [],
        cs: "12",
        sm: "6",
        md: "6",
        type: "date",
        outlined: true,
        dense: true,
        solo: true,
        range: true,
        visible: false,
      },
      {
        id: 2,
        title: "db",
        name: "Список БД 1С:*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "autocomplete",
        items: [],
        outlined: true,
        dense: true,
        solo: true,
        multiple: true,
        err: "",
        required: true
      },
      {
        id: 3,        
        title: "accessLevel",
        name: "Права как у кого:*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "selectUsr",
        class: "mt-n2",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
        required: true
      },
      {
        id: 4,
        name: "Описать уровень доступа:*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
        required: true
      },
      {
        id: 5,
        name: "Комментарий:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },
      {
        id: 7,
        title: "user",
        name: "ФИО",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "selectUsr",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
        required: true
      },
    ],
    cmnt: "",
    cmnt_err: "",
    db: "",
    org: [],
    file: [],
    source: "./ajax/ajax_1c001.php",
  }),
  created() {
    bus.$on("SelectUsr", (data) => {
      this.checkForm();
      if (data.input_id == "7") {
        this.input.find((item) => {
          if (item.title === "user") {
            item.err = "";
            item.value = "user_" + data.userId;
          }
        });
      }
      if (data.input_id == "3") {
        this.input.find((item) => {
          if (item.title === "accessLevel") {
            item.err = "";
            item.value = "user_" + data.userId;
          }
        });
      }
    });    
    bus.$on("resultArray", () => {
      this.checkForm();
    });
  },
  mounted() {
    this.getDB();
    this.getOrg();
  },
  methods: {
    getDB() {
      axios
        .get(this.source, {
          params: {
            type: "getDB",
          },
        })
        .then((response) =>
          this.input.find((item) => {
            if (item.title === "db") {
              response.data.forEach(element => {
                item.items.push({NAME: element})
              });
            }
          })
        );
    },

    getOrg() {
      axios
        .get("./ajax/GetOrg.php", {})
        .then((response) => this.findItemArray("org", response));
    },

    checkArr(arr) {
      return arr.value != "";
    },

    checkForm() {
      const input = this.input.filter((item) => (item.required === true));
      const valid = !input.some((item) => item.value == "");
      if (valid) {
        this.sendButtonDisable = false;
      } else {
        this.sendButtonDisable = true;
      }
    },

    findItemArray(elementName, response) {
      this.input.find((item) => {
        if (item.title === elementName) {
          item.items = response.data;
        }
      });
    },
    
    sendData(){
      const formData = new FormData();
      formData.append('type', '1c001')
      this.input.forEach((element) => {
        if(element.value != null){          
          formData.append(element.id, element.value);
        }
      });
      return formData;    
    },

  },
  computed: {
    getValueSwitch() {
      const inputSwitch = this.input.find((item) => item.type === "switch");
      return inputSwitch.value;
    },
  },
  watch: {
    getValueSwitch(newValue) {
      const inputTimeInterval = this.input.find(
        (item) => item.title === "timeInterwall"
      );
      inputTimeInterval.visible = newValue;
    },
  },
};
</script>
