<template>
  <v-container fluid>
    <DialogAfterSendFrom
      :dialog="dialog"
      :warnMessage="dialogMessage"
      route="test1"
    />
    <v-card class="py-12">
      <v-card class="form">
        <h1 class="form__title">{{ title }}</h1>
        <p class="form__subtitle">{{ subtitle }}</p>
        <div class="form__user-info">
          <div class="ui-ctl ui-ctl-textbox ui-ctl-underline ui-ctl-w33 ui-ml-0">
            <input type="text" class="ui-ctl-element" v-model="user" placeholder="ФИО" />            
          </div>
          <div class="ui-ctl ui-ctl-textbox ui-ctl-underline ui-ctl-w33 ui-ml-0">
            <input type="text" class="ui-ctl-element" v-model="job" placeholder="Должность" />
          </div>
          <div
            class="
              ui-ctl
              ui-ctl-after-icon
              ui-ctl-dropdown
              ui-ctl-underline
              ui-ctl-w33
              ui-ml-0
            "
          >
            <div class="ui-ctl-after ui-ctl-icon-angle"></div>
            <select class="ui-ctl-element" v-model="slectedOrg">
              <option value="" disabled selected>Организация</option>
              <option v-for="(item, index) in org" :key="index" value="">
                {{ item.NAME }}
              </option>
            </select>
          </div>
        </div>
        <hr class="form__line" />
        <ul class="question">
          <li v-for="(item, index) in questions" :key="index">
            <div class="question__title">
              <div class="ui-icon ui-icon-common-info ui-icon-xs">
                <i style="background-color: #81d4fa !important"></i>
              </div>
              <p>{{ item.title }}</p>
            </div>
            <div
              v-for="(answer, index) in item.answers"
              :key="index"
              class="question__answer"
            >
              <input
                :id="
                  'answer' +
                  questions.findIndex((el) => el.title === item.title) +
                  index
                "
                type="checkbox"
                :value="answer.title"
                v-model="item.value"
              />
              <label
                :for="
                  'answer' +
                  questions.findIndex((el) => el.title === item.title) +
                  index
                "
                class="question__label"
                ><p>{{ answer.title }}</p></label
              >
            </div>
          </li>
        </ul>
        <hr class="form__line" />
        <div class="ui-btn-container ui-btn-container-center form__button">
          <input
            type="submit"
            class="ui-btn ui-btn-success"
            name="submit"
            value="Отправить"
            id="sendData"
            title="Отправить и закончить работу с формой"
            @click="sendData()"
          />
          <input
            type="button"
            class="ui-btn ui-btn-link"
            name="cancel"
            value="Отменить"
            id="formCancl"
            title="Не сохранять и вернуться"
            @click="formCancl()"
          />
        </div>
      </v-card>
    </v-card>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import axios from "axios";
import DialogAfterSendFrom from "@/components/DialogAfterSendForm.vue";
//import Input from "@/components/Input.vue";
export default {
  components: {
    DialogAfterSendFrom,
    //Input,
  },
  data: () => ({
    user: "",
    job: "",
    title: "Анкета увольняющегося сотрудника",
    subtitle: `Анкета составлена с целью анализа причин увольнения сотрудников и рабочих с предприятия. Данные, полученные в ходе опроса, будут использованы для улучшения условий работы персонала на предприятии. Ответьте на вопросы. Пожалуйста, будьте искренними.`,
    inputs: [
      {
        name: "ФИО:",
        value: "",
        type: "string",
        class: "mt-2",
      },
      {
        name: "Должность:",
        value: "",
        type: "string",
        class: "mt-2",
      },
      {
        value: "",
        type: "selectOrg",
        class: "mt-2",
      },
    ],
    questions: [
      {
        title:
          "Соответствовала ли работа на нашем предприятии Вашим ожиданиям.",
        answers: [
          { title: "Да" },
          { title: "Нет" },
          { title: "Затрудняюсь ответить" },
        ],
        value: [],
      },
      {
        title:
          "Из нижеперечисленных факторов отметьте те, которые повлияли на Ваше решение уволиться (не более 3).",
        answers: [
          {
            title: "Размер заработной платы не соответствует объёму работ",
          },
          {
            title: "Смена места жительства",
          },
          {
            title: "Не устраивает режим работы",
          },
          {
            title: "Найдена работа ближе к дому",
          },
          {
            title: "Объем работы слишком высокий, тяжелые условия труда",
          },
          {
            title: "Неуважительное, грубое отношение со стороны руководителей",
          },
          {
            title:
              "Отсутствие условий для служебного и профессионального роста",
          },
          {
            title: "Работа не по специальности",
          },
          {
            title: "Другое (пожалуйста, укажите причину)",
          },
        ],
        value: [],
      },
      {
        title: "Как бы Вы оценили психологический климат в вашем коллективе.",
        answers: [
          {
            title:
              "Работники чувствуют себя комфортно, отношения в коллективе дружеские",
          },
          {
            title:
              "Отношения дружеские в коллективе, но руководитель вносит напряжение",
          },
          {
            title: "Работники постоянно конфликтуют между собой",
          },
          {
            title: "Затрудняюсь ответить",
          },
        ],
        value: [],
      },
      {
        title:
          "Что, на Ваш взгляд, необходимо изменить на Вашем рабочем месте.",
        answers: [
          {
            title: "Оборудование (технические средства труда)",
          },
          {
            title: "Бытовые условия",
          },
          {
            title: "Взаимоотношения в коллективе",
          },
          {
            title: "Взаимоотношения с руководством",
          },
          {
            title: "Иное (опишите)",
          },
        ],
        value: [],
      },
      {
        title:
          "Что, на Ваш взгляд, следует оставить на прежнем уровне на вашем рабочем месте.",
        answers: [
          {
            title: "Оборудование (технические средства труда)",
          },
          {
            title: "Бытовые условия",
          },
          {
            title: "Взаимоотношения в коллективе",
          },
          {
            title: "Взаимоотношения с руководством",
          },
          {
            title: "Иное (опишите)",
          },
        ],
        value: [],
      },
    ],
    org: [],
    slectedOrg: "",
    dialog: false,
    source: "./ajax/hr/tests/ajax_test2.php",
  }),
  created() {
    bus.$on("SelectUsr", (data) => {
      this.user = data;
    });
  },
  mounted() {
    axios
      .get("./ajax/GetOrg.php", {})
      .then((response) => (this.org = response.data))
      .catch((error) => console.log(error));
  },
  computed: {
    answer1() {
      return this.questions[0].value;
    },
    answer2() {
      return this.questions[1].value;
    }
  },
  watch: {
    answer1(val) {
      if(val.length > 1 ){
        this.questions[0].value.shift();
      }
    },
    answer2(val) {
      if(val.length > 3 ){
        this.questions[1].value.shift();
      }
    }
  },
  methods: {
    formCancl() {
      this.$router.go(-1);
    },
    sendData() {
      let btn = document.querySelector(".ui-btn-success");
      btn.classList.add("ui-btn-clock");
      axios({
        method: "post",
        withCredentials: true,
        headers: { "Content-Type": "multipart/form-data" },
        url: this.source,
        data: {
          user: this.user,
          questions: this.questions,
        },
      })
        .then((response) => {
          if (response.status == 200) {
            this.dialog = true;
            this.dialogMessage = "Спасибо за участие";
            this.btnLoader = false;
          }
        })
        .catch((error) => {
          this.dialog = true;
          this.dialogMessage = "Произошла ошибка: " + error;
          this.btnLoader = false;
        });
    },
  },
};
</script>

<style>
.form {
  max-width: 80% !important;
  margin: auto;
  background-color: #fafafa !important;
}
.form__title {
  text-align: center;
  padding-top: 1%;
}
.form__subtitle {
  text-align: center;
  max-width: 50%;
  margin: 0 auto 1% auto;
}
.form__user-info {
  margin: 0 2% 2% 2%;
}
.form__line {
  margin: 0 2% 0 2%;
  border: none;
  border-top: 1px solid rgba(0, 0, 0, 0.12);
  overflow: visible;
}
.form__button {
  padding: 1% 0 1% 0;
  margin: 0 !important;
}
.ui-ml-0 {
  margin-left: 0!important;
}
.question {
  margin: 2% 5% 0 5%;
  list-style-type: none;
}
.question__title {
  font-size: 1.1rem !important;
  font-weight: 500;
  align-items: center;
  display: flex;
  margin-bottom: 1%;
}
.question__title > p {
  margin-bottom: 0 !important;
  margin-left: 1%;
}
.question__title > i {
  background-color: #81d4fa !important;
}
.question__answer {
  display: flex;
  align-items: baseline;
  margin-left: 5%;
}
.question__answer:first-of-type {
  margin-top: 1%;
}
label.question__label {
  margin-left: 0.5%;
  padding: 0 0 7px;
  font: 13px/18px "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 14px;
  color: #525c69;
  line-height: 17px;
}

@media screen and (max-width: 1380px) {
  .form {
    max-width: 95% !important;
  }
}
</style>