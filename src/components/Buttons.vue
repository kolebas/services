<template>
  <v-container fluid>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage"/>  
    <v-card-actions class="py-4">
      <div class="mx-auto">
        <v-btn
          class="mx-1"
          :loading="btnLoader"
          color="green lighten-2 white--text"
          @click="formSend()"
        >
          Отправить
        </v-btn>
        <v-btn class="mx-1" @click="formCancl()"> Отмена </v-btn>
      </div>
    </v-card-actions>
  </v-container>
</template>

<script>
import axios from "axios";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
export default {
  components: {
    DialogAfterSendForm,
  },
  props: {
    input: { type: Array },
    ajax: {type: String},
    btnLoader: {type: Boolean, default: false},
  },
  
  data: () => ({
    btns: true,
    dialog: false,
    dialogMessage: "",
  }),
  methods: {
    formSend() {
      this.btnLoader = true;
      axios({
        method: "post",
        withCredentials: true,
        headers: { "Content-Type": "multipart/form-data" },
        url: this.ajax,
        data: this.input,
      })
        .then((response) => {
          if (response.status == 200) {
              this.dialog = true;
              this.dialogMessage =
                "Успешно. Номер вашей заявки: " + response.data;
              this.btnLoader = false;
            }
        })
        .catch((error) => {
          this.dialog = true;
          this.dialogMessage = "Произошла ошибка " + error;
        });
    },
  },
};
</script>