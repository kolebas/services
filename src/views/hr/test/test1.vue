<template>
  <v-container fluid>
    <DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage" />
    <v-row>
      <v-col cols="11">
        <v-card
          max-width="75%"
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
                v-model="name"
                dense
                label="Организация"
                :error-messages="name_err"
              ></v-text-field>
              <v-text-field
                class="pl-6"
                v-model="name"
                dense
                label="ФИО"
                :error-messages="name_err"
              ></v-text-field>
              <v-text-field
                class="pl-6"
                v-model="name"
                dense
                label="Должность"
                :error-messages="name_err"
              ></v-text-field>
            </v-col>
            <v-col cols="8" class="d-flex flex-row-reverse">
              <v-card outlined class="mr-4" color="grey lighten-4">
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-subtitle class="text-right mr-4"
                      >Прогресс выполнения теста:</v-list-item-subtitle
                    >
                  </v-list-item-content>
                  <v-progress-circular
                    :rotate="360"
                    :size="100"
                    :width="15"
                    :value="progValue"
                    color="green"
                  >
                    <b>{{ progValue }} % </b>
                  </v-progress-circular>
                </v-list-item>
              </v-card>
            </v-col>
          </v-row>
          <Input :arrInput="quest" />
          <v-row v-for="item in quest" :key="item.id" class="mb-n6">
            <v-col cols="6">
              <v-card-text class="subtitle-1 pt-2">
                <b>Вопрос №{{ item.id + 1 }}:</b>
                {{ item.text }}:
              </v-card-text>
            </v-col>
            <v-col cols="6">
              <v-textarea
                height="90"
                class="mr-4"
                outlined
                solo
                dense
                v-model="item.value"
                @change="progSts()"
                @input="item.err = ''"
                label="Введите свой ответ"
                :error-messages="item.err"
              >
              </v-textarea>
            </v-col>
          </v-row>
          <v-card-actions class="py-4">
            <div class="mx-auto">
              <v-btn
                class="mx-1"
                color="green lighten-2 white--text"
                @click="formSend()"
              >
                Отправить
              </v-btn>
              <v-btn class="mx-1" @click="formCancl()"> Отмена </v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="1" v-if="table">
        <v-btn @click="(card = false), (table = true)"> Проверка тестов </v-btn>
      </v-col>
      <v-col cols="12" v-if="table">
        <v-card>
          <v-card-title>
            {{ table_name }}
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Поиск"
              single-line
              hide-details
            ></v-text-field>
            <v-btn
              @click="(dialog = true), (dialog_title = 'Добавление позиции')"
              color="primary"
              dark
              class="mb-2 ml-2"
              >Добавить
            </v-btn>
          </v-card-title>
          <v-data-table
            :headers="headers_items"
            :items="item"
            :search="search"
            calculate-widths
          >
            <template v-slot:item.RESULT="{ item }">
              <v-chip class="mr-2" @click="editItem(item.ID)">
                Не проверено
              </v-chip>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";
import DialogAfterSendFrom from "@/components/DialogAfterSendForm.vue";
import Input from "@/components/Input.vue";
export default {
  components: {
    DialogAfterSendFrom,
    Input
  },
  data: () => ({
    title: "Тестовые вопросы к инструкциям по уборке нишевых культур",
    name: "",
    quest: [
      {
        id: 0,
        text:
          "В течении, которого времени (мин), должен быть просеян образец, для определения зараженности в явной форме, согласно ГОСТ 13586.6-93 «Зерно. Методы определения зараженности вредителями.»",
        value: "",
        name: "В течении, которого времени (мин), должен быть просеян образец, для определения зараженности в явной форме, согласно ГОСТ 13586.6-93 «Зерно. Методы определения зараженности вредителями.»",
        err: "",
        textPosition: "subtitle-1 text-left pt-2",
        type: "textarea",
        solo: true,
        outlined: true,
        label: "Введите привильный ответ"
      },
      {
        id: 1,
        text:
          "Время сушки измельченного зерна зерновых и зернобобовых культур, кроме кукурузы, с момента установления нормируемой температуры, согласно ГОСТ 13586.5-2015 «Зерно. Методы определения влажности»",
        value: "",
        err: "",
      },
      {
        id: 2,
        text:
          "Опишите явные признаки повреждения зерен вредным клопом-черепашкой, согласно ГОСТ 33538-2015 «Защита растений. Методы выявления и учета поврежденных зерен злаковых культур клопами-черепашками».",
        value: "",
        err: "",
      },
      {
        id: 3,
        text:
          "По какой схеме производится отбор проб из автомашины с длиной кузова до 3,5 м., согласно ГОСТ 13586.3-2015 «Зерно. Правила приемки и методы отбора проб»?",
        value: "",
        err: "",
      },
      {
        id: 4,
        text:
          "Опишите отличительные признаки фузариозных зерен от здорового зерна, согласно ГОСТ 31646-2012 «Зерновые культуры. Методы определения содержания фузариозных зерен».",
        value: "",
        err: "",
      },
      {
        id: 5,
        text:
          "Какие мероприятия необходимо предусмотреть для исключения перевалку продукции нишевых культур в склады и обеспечения её прямого подвоза на очистку",
        value: "",
        err: "",
      },
      {
        id: 6,
        text:
          "В каких единицах измерения выражается показатель «качество клейковины», согласно ГОСТ Р 54478-2011 «Зерно. Методы определения количества и качества клейковины в пшенице»?",
        value: "",
        err: "",
      },
      {
        id: 7,
        text:
          "По какому показателю определяется класс пшеницы, согласно ГОСТ 9353-2016 «Пшеница. Технические условия.»?массовая доля белка, %, (на сухое вещество);массовая доля сырой клейковины, %;по наихудшему значению одного из показателей качества, указанных в стандарте; натура, г/л; содержание сорной примеси, %.",
        value: "",
        err: "",
      },
      {
        id: 8,
        text:
          "Определите степень зараженности зерна при суммарной плотности заражения от 1,1 до 3,0 экз./кг., согласно ГОСТ 13586.6-93.",
        value: "",
        err: "",
      },
      {
        id: 9,
        text:
          "Навеску зерна при определении количества и качества клейковины очищают от, согласно ГОСТ Р 54478-2011:",
        value: "",
        err: "",
      },
      {
        id: 10,
        text:
          "Какая навеска нормируется для проведения анализа на содержание спорыньи, согласно ГОСТ 30483-97:",
        value: "",
        err: "",
      },
    ],
    table_name: "Результаты тестов",
    headers_items: [
      { text: "Дата", value: "DATE" },
      { text: "ФИО", value: "CREATED_BY" },
      { text: "Результат", value: "RESULT" },
    ],
    item: [],
    search: "",
    dialog: false,
    name_err: "",
    dialogMessage: "",
    progValue: "0",
    card: true,
    table: false,
  }),
  methods: {
    formCancl: function () {
      this.$router.go(-1);
    },
    progSts() {
      let y = 0;
      for (let i = 0; i < this.quest.length; i++) {
        if (this.quest[i].value) {
          y = y + 10;
        }
      }
      this.progValue = y;
    },
    formSend() {
      this.quest[0].name = this.name;
      this.btnLoader = true;

      for (let i = 0; i < this.quest.length; i++) {
        if (!this.quest[i].value) {
          this.quest[i].err = "Необходимо заполнить это поле";
        }
      }
      if (this.name) {
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "multipart/form-data" },
          url: "./ajax/hr/tests/ajax_test.php",
          data: this.quest,
        })
          .then((response) => {
            if (response.status == 200) {
              this.dialog = true;
              this.dialogMessage = "Ваш тест отправлен на проверку";
              this.btnLoader = false;
            }
          })
          .catch((error) => {
            console.log(error);
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка";
            this.btnLoader = false;
          });
      }
      if (!this.name) {
        this.name_err = "Вы забыли полнить свое ФИО";
      }
    },
  },
  mounted() {
    axios
      .get("./ajax/hr/tests/ajax_test.php", {
        headers: { "Content-Type": "application/json; charset=utf-8" },
        params: {
          Login: "VqFLDv9gY",
          Token: "VuivdzcaGvFtOACElaNdO?@-BKFMXy",
        },
      })
      .then((response) => (this.item = response.data));
  },
};
</script>
