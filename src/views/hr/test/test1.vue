<template>
  <v-container fluid>
    <DialogAfterSendFrom
      :dialog="dialog"
      :warnMessage="dialogMessage"
      route="test1"
    />
    <v-row>
      <v-col cols="12">
        <v-card
          max-width="85%"
          raised
          class="mx-auto"
          color="grey lighten-4"
          v-if="card"
        >
          <v-card-text class="pa-0">
            <p class="text-center pt-4 headline text--primary">
              {{ title }}
            </p>
          </v-card-text>
          <v-row>
            <v-col cols="4">
              <v-text-field
                class="pl-6"
                v-model="org"
                dense
                label="Организация"
                :error-messages="name_err"
              ></v-text-field>
              <v-text-field
                class="pl-6"
                v-model="user"
                dense
                label="ФИО"
                :error-messages="name_err"
              ></v-text-field>
              <v-text-field
                class="pl-6"
                v-model="prof"
                dense
                label="Должность"
                :error-messages="name_err"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-alert
                v-if="testStatus == 'Выполняется...'"
                border="top"
                colored-border
                color="info"
                elevation="2"
              >
                <span class="d-flex justify-center"
                  >До завершения теста осталось:</span
                >
                <span class="d-flex justify-center display-2">{{
                  setTimer
                }}</span>
              </v-alert>
              <v-row class="mt-6 d-flex justify-center">
                <!-- <span class="display-2" v-if="testStatus == 'Выполняется...'">{{
                  setTimer
                }}</span> -->
                <v-btn
                  v-if="testStatus != 'Выполняется...'"
                  :disabled="startTimerBtn"
                  @click="startTimer()"
                >
                  {{ testStatus }}</v-btn
                >
              </v-row>
            </v-col>
            <v-col cols="4" class="d-flex justify-center">
              <v-card outlined class="mr-4" color="grey lighten-4">
                <v-list-item>
                  <v-list-item-content>
                    <span class="subtitle-1 mr-2"
                      >Прогресс выполнения теста</span
                    >
                  </v-list-item-content>
                  <v-progress-circular
                    :rotate="360"
                    :size="100"
                    :width="15"
                    :value="getProgressCount"
                    color="green"
                  >
                    <span class="title">{{ getProgressCount }}% </span>
                  </v-progress-circular>
                </v-list-item>
              </v-card>
            </v-col>
          </v-row>
          <v-expand-transition>
            <div v-if="startTest">
              <v-container fluid>
                <v-banner single-line>
                  <v-icon slot="icon" color="info" size="36">
                    mdi-information-outline
                  </v-icon>
                  <span class="title"
                    >Блок №1 Требования Российский стандартов</span
                  >
                </v-banner>
              </v-container>
              <Input :arrInput="quest" />
              <v-container fluid>
                <v-banner single-line>
                  <v-icon slot="icon" color="info" size="36">
                    mdi-information-outline
                  </v-icon>
                  <span class="title">Блок №2 Технологический процесс</span>
                </v-banner>
              </v-container>
              <Input :arrInput="quest2" />
              <hr />
              <v-card-actions class="py-4">
                <div class="mx-auto">
                  <v-btn
                    class="mx-1"
                    color="green lighten-2 white--text"
                    :loading="btnLoader"
                    @click="formSend()"
                  >
                    Отправить
                  </v-btn>
                  <v-btn class="mx-1" @click="formCancl()"> Отмена </v-btn>
                </div>
              </v-card-actions>
            </div>
          </v-expand-transition>
        </v-card>
      </v-col>
      <v-overlay :value="overlay">
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </v-row>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import axios from "axios";
import DialogAfterSendFrom from "@/components/DialogAfterSendForm.vue";
import Input from "@/components/Input.vue";
export default {
  components: {
    DialogAfterSendFrom,
    Input,
  },
  data: () => ({
    title: "Тестовые вопросы к инструкциям по уборке нишевых культур",
    org: "",
    user: "",
    prof: "",
    overlay: false,
    startTest: false,
    testStatus: "Начать тестирование",
    currentMinutes: "25",
    currentSeconds: "00",
    startTimerBtn: false,
    timer: null,
    btnLoader: false,
    quest: [
      {
        id: 0,
        name:
          "ВОПРОС №1.1: В течении, которого времени (мин), должен быть просеян образец, для определения зараженности в явной форме, согласно ГОСТ 13586.6-93 «Зерно. Методы определения зараженности вредителями.»",
        value: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "radio",
        radioGroup: ["1 минута", "2 минуты", "3 минуты"],
        radioClass: "mt-2",
        solo: true,
        outlined: true,
        err: "",
        label: "ОТВЕТ",
      },
      {
        id: 1,
        name:
          "ВОПРОС №1.2: Время сушки измельченного зерна зерновых и зернобобовых культур, кроме кукурузы, с момента установления нормируемой температуры, согласно ГОСТ 13586.5-2015 «Зерно. Методы определения влажности»",
        value: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "radio",
        radioGroup: ["45 минут", "60 минут", "40 минут"],
        radioClass: "mt-2",
        solo: true,
        outlined: true,
        err: "",
      },
      {
        id: 2,
        name:
          "ВОПРОС №1.3: Опишите явные признаки повреждения зерен вредным клопом-черепашкой, согласно ГОСТ 33538-2015 «Защита растений. Методы выявления и учета поврежденных зерен злаковых культур клопами-черепашками».",
        value: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        err: "",
      },
      {
        id: 4,
        name:
          "ВОПРОС №1.4: Опишите отличительные признаки фузариозных зерен от здорового зерна, согласно ГОСТ 31646-2012 «Зерновые культуры. Методы определения содержания фузариозных зерен».",
        value: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        err: "",
      },
      {
        id: 5,
        name:
          "ВОПРОС №1.5: В каких единицах измерения выражается показатель «качество клейковины», согласно ГОСТ Р 54478-2011 «Зерно. Методы определения количества и качества клейковины в пшенице»?",
        value: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        err: "",
      },
      {
        id: 6,
        name:
          "ВОПРОС №1.6: По какому показателю определяется класс пшеницы, согласно ГОСТ 9353-2016 «Пшеница. Технические условия.»? массовая доля белка, %, (на сухое вещество); массовая доля сырой клейковины, %; по наихудшему значению одного из показателей качества, указанных в стандарте; натура, г/л; содержание сорной примеси, %. ",
        value: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        err: "",
      },
      {
        id: 7,
        name:
          "ВОПРОС №1.7: Определите степень зараженности зерна при суммарной плотности заражения от 1,1 до 3,0 экз./кг., согласно ГОСТ 13586.6-93.",
        value: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        err: "",
      },
      {
        id: 9,
        name:
          "ВОПРОС №1.8: Навеску зерна при определении количества и качества клейковины очищают от, согласно ГОСТ Р 54478-2011:",
        value: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "radio",
        radioGroup: [
          "Сорная примесь",
          "Сорной и зерновой примеси",
          "Сорной примеси, за исключением испорченных зерен пшеницы, ржи, ячмень, фузариозных зерен",
        ],
        radioClass: "mt-2",
        solo: true,
        outlined: true,
        err: "",
      },
      {
        id: 10,
        name:
          "ВОПРОС №1.9: Какая навеска нормируется для проведения анализа на содержание спорыньи, согласно ГОСТ 30483-97:",
        value: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "radio",
        radioGroup: ["200 грамм", "500 грамм", "2000 грамм"],
        radioClass: "mt-2",
        solo: true,
        outlined: true,
        err: "",
      },
    ],
    quest2: [
      {
        id: 0,
        name:
          "ВОПРОС №2.1: Укажите периодичность измерений температуры в зерновой массе при хранении и какие отраслевые журналы заполняются. От чего она зависит?",
        value: "",
        err: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        label: "ОТВЕТ",
      },
      {
        id: 1,
        name:
          "ВОПРОС №2.2: Какие показатели качества влияют на стойкость продукции при хранении.",
        value: "",
        err: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        label: "ОТВЕТ",
      },
      {
        id: 2,
        name:
          "ВОПРОС №2.3: Дайте определение термину «самосогревание зерновой массы».",
        value: "",
        err: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        label: "ОТВЕТ",
      },
      {
        id: 3,
        name:
          "ВОПРОС №2.4: Отпишите ситуации, в которых необходимо инициировать включение установок активного вентилирования зерна в силосе/ или охлаждения путем пропуска через зерносушилку. Какие факторы (условия) необходимо?",
        value: "",
        err: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        label: "ОТВЕТ",
      },
      {
        id: 4,
        name:
          "ВОПРОС №2.5: Укажите перечень показателей, по которым производятся испытания на объединенные пробы с каждой автомашины при приеме продукции?",
        value: "",
        err: "",
        textPosition: "subtitle-1 font-weight-bold text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        label: "ОТВЕТ",
      },
    ],
    table_name: "Результаты тестов",
    headers_items: [
      { text: "Дата", value: "DATE" },
      { text: "ФИО", value: "CREATED_BY" },
      { text: "Результат", value: "RESULT" },
    ],
    arrayItemCount: [],
    item: [],
    search: "",
    dialog: false,
    name_err: "",
    dialogMessage: "",
    progValue: "0",
    card: true,
    table: false,
  }),
  created() {
    bus.$on("resultArray", (data) => {
      this.loadData(data);
    });
  },
  computed: {
    sendData() {
      return true;
    },
    setTimer() {
      let minutes = this.currentMinutes;
      let seconds = this.currentSeconds;
      if (minutes <= 9 && seconds <= 9) {
        seconds = "0" + this.currentSeconds;
        minutes = "0" + this.currentMinutes;
      }
      if (this.currentSeconds <= 9) {
        seconds = "0" + this.currentSeconds;
      }
      if (this.currentMinutes <= 9) {
        minutes = "0" + this.currentMinutes;
      }
      return minutes + ":" + seconds;
    },
    getProgressCount() {
      let getArrayValueCount = this.arrayItemCount.filter(
        (elem) => elem.value != ""
      ).length;
      if (!getArrayValueCount) {
        return 0;
      }
      return (getArrayValueCount / this.arrayItemCount.length) * 100;
    },
  },
  watch: {
    currentSeconds(time) {
      if (time === 0) {
        if (this.currentMinutes == "0") {
          this.stopTimer();
        } else {
          this.currentSeconds = "59";
          this.currentMinutes--;
        }
      }
    },
  },
  methods: {
    startTimer() {
      if (this.currentSeconds == "00") {
        this.currentSeconds = "59";
        this.currentMinutes--;
      }
      this.startTimerBtn = true;
      this.startTest = true;
      this.testStatus = "Выполняется...";
      this.timer = setInterval(() => {
        this.currentSeconds--;
      }, 1000);
    },
    stopTimer() {
      clearTimeout(this.timer);
      this.dialog = true;
      this.dialogMessage = "Время истекло, ваш тест отправлен на проверку.";
      this.formSend();
    },
    formCancl: function () {
      this.$router.go(-1);
    },
    loadData(data) {
      if (this.quest.length == data.length) {
        this.quest = data;
      }
      if (this.quest2.length == data.length) {
        this.quest2 = data;
      }
      this.arrayItemCount = this.quest.concat(this.quest2);
    },
    formSend() {
      this.btnLoader = true;
      /* for (let i = 0; i < this.quest.length; i++) {
        if (!this.quest[i].value) {
          this.quest[i].err = "Необходимо заполнить данное поле";
        }
      }
      for (let i = 0; i < this.quest2.length; i++) {
        if (!this.quest2[i].value) {
          this.quest2[i].err = "Необходимо заполнить данное поле";
        }
      } */

      if (this.sendData) {
        var questAll = this.quest.concat(this.quest2);
        var quest = [];
        for (let i = 0; questAll.length > i; i++) {
          var obj = Object.assign(
            { name: questAll[i].name },
            { value: questAll[i].value }
          );
          quest.push(Object.assign(obj));
        }
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url:
            "https://portal.ahstep.ru/ahstep/services/ajax/hr/tests/ajax_test.php",
          data: {
            user: this.user,
            org: this.org,
            prof: this.prof,
            quest: quest,
          },
        })
          .then((response) => {
            if (response.status == 200) {
              this.dialog = true;
              this.dialogMessage = "Ваш тест отправлен на проверку";
              this.btnLoader = false;
            }
          })
          .catch((error) => {
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка: " + error;
            this.btnLoader = false;
          });
      }
    },
  },
};
</script>
