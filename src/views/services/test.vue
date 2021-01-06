<template>
  <v-container>
    <h1>test</h1>
    <v-btn @click="sendData()">112313</v-btn>
  </v-container>
</template>

<script>
import axios from "axios";
export default {
  data: () => ({
    array: [
      {
        ДатаУвольнения: "16.01.1986",
        Сотрудник: "Заикин Николай Игоревич",
        ДатаРождения: "16.01.1986",
        Организация: "Ставропольское Руно ООО",
        Должность: "Подсобный рабочий (вредн)*",
        ВидЗанятости: "Основное место работы",
      },
      {
        ДатаУвольнения: "16.01.1986",
        Сотрудник: "Глухова Валентина Николаевна",
        ДатаРождения: "16.01.1990",
        Организация: "Новожуковский ООО",
        Должность: "Сторож*",
        ВидЗанятости: "Основное место работы",
      },
      {
        ДатаУвольнения: "16.01.1990",
        Сотрудник: "Тутиков Алексей Викторович",
        ДатаРождения: "16.01.1990",
        Организация: "Бешпагир ООО",
        Должность: "Электрик*",
        ВидЗанятости: "Внутреннее совместительство",
      },
    ],
  }),
  methods: {
    sendData() {
      console.log("send");
      axios({
        method: "post",
        withCredentials: true,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        url: "https://portal.ahstep.ru/ahstep/services/ajax/ajax_ar002.php",
        auth: {
          username: "bp_service",
          password: "VGmjWK",
        },
        data: this.array,
      });
    },
  },
};
</script>

<style>
</style>