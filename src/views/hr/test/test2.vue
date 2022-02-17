<template>
  <v-card class="py-12">
    <v-card class="form">
      <h1 class="form__title">{{ title }}</h1>
      <p class="form__subtitle">{{ subtitle }}</p>
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
      <hr class="form_line" />
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
      <hr class="form_line" />
      <div class="ui-btn-container ui-btn-container-center form__button">
        <input
          type="submit"
          class="ui-btn ui-btn-success"
          name="submit"
          value="Отправить"
          id=""
          title="Отправить и закончить работу с формой"
        />
        <input
          type="button"
          class="ui-btn ui-btn-link"
          name="cancel"
          value="Отменить"
          id=""
          title="Не сохранять и вернуться"
        />
      </div>
    </v-card>
  </v-card>
</template>

<script>
export default {
  data: () => ({
    title: "Анкета увольняющегося сотрудника",
    subtitle: `Анкета составлена с целью анализа причин увольнения сотрудников и рабочих с предприятия. Данные, полученные в ходе опроса, будут использованы для улучшения условий работы персонала на предприятии. Ответьте на вопросы. Пожалуйста, будьте искренними.`,
    questions: [
      {
        title:
          "Соответствовала ли работа на нашем предприятии Вашим ожиданиям.",
        answers: [
          { title: "Да" },
          { title: "Нет" },
          { title: "Затрудняюсь ответить" },
        ],
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
      },
    ],
  }),
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
.form_line {
  margin: 0 2% 0 2%;
  border: none;
  border-top: 1px solid rgba(0, 0, 0, 0.12);
  overflow: visible;
}
.form__button {
  padding: 1% 0 1% 0;
  margin: 0 !important;
}
.question {
  margin: 2% 5% 0 5%;
  list-style-type: none;
}
.question__item {
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
}

@media screen and (max-width: 1380px) {
  .form {
    max-width: 95% !important;
  }
}
</style>