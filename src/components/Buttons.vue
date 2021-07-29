<template>
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
</template>

<script>
import axios from "axios";
export default {
  props: {
    input: { type: Array },
  },
  data: () => ({
    btns: true,
  }),
  methods: {
    formSend() {
      axios({
        method: "post",
        withCredentials: true,
        headers: { "Content-Type": "multipart/form-data" },
        url: "./ajax/ajax.php",
        auth: {
          username: "111",
          password: "1117",
        },
        data: {
          input: this.input,
        },
      })
        .then(function (response) {
          //this.warnMessage = "Ваша заявка успешно отправлена";
          console.log(response);
        })
        .catch(function (error) {
          //this.warnMessage = "Произошла ошибка";
          console.log(error);
        });
    },
  },
};
</script>