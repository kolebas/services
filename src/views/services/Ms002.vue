<template>
  <v-container fluid>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
    <TitleService />
    <v-card min-height="800px" class="py-12">
      <v-row>
        <v-card max-width="65%" raised class="mx-auto" color="grey lighten-4">
          <RqCardTitle
            :title="$router.currentRoute.name"
            :sub_message="sub_message"
          ></RqCardTitle>
          <hr />
          <SelectUsr
            cols_title="4"
            cols_input="6"
            title="ФИО:"
            :userId_err="userId_err"
          />
          <v-row class="mb-n6">
            <v-col cols="4">
              <v-card-text class="subtitle-1 text-right pt-2">
                Выбор программы:
              </v-card-text>
            </v-col>
            <v-col cols="6">
              <v-select
                @change="soft_err = ''"
                v-model="soft"
                :items="items_type"
                label="Выберите необходимую опцию"
                chips
                deletable-chips
                solo
                outlined
                dense
                :error-messages="soft_err"
                ><template v-slot:selection="{ item }">
                  <v-chip color="#bcedfc" close label @click:close="soft = ''"
                    >{{ item }}
                  </v-chip>
                </template>
              </v-select>
            </v-col>
          </v-row>
          <v-row class="mb-n6">
            <v-col cols="4">
              <v-card-text class="subtitle-1 text-right pt-2">
                Обоснование доступа :
              </v-card-text>
            </v-col>
            <v-col cols="6">
              <v-textarea
                v-model="cmnt"
                @input="cmnt_err = ''"
                outlined
                solo
                label="Для указания дополнительной информации используйте это поле"
              ></v-textarea>
            </v-col>
          </v-row>
          <hr />
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
        </v-card>
      </v-row>
    </v-card>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import RqCardTitle from "@/components/RqCardTitle";
import SelectUsr from "@/components/SelectUsr";
import axios from "axios";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    DialogAfterSendForm,
    RqCardTitle,
    SelectUsr,
    TitleService
  },
  data: () => ({
    sub_message:
      "Согласно действующей политике, данная заявка может заводиться только руководителями подразделений. Статус созданной заявки вы можете отслеживать в разделе ",
    items_type: ["История поля", "Wialon", "ГЕОМИКСЕР"],
    dialog: false,
    dialogMessage: "",
    users: [],
    userId: "",
    soft_err: "",
    soft: "",
    userId_err: "",
    cmnt: "",
    cmnt_err: "",
    btnLoader: false,
  }),
  created() {
    bus.$on("SelectUsr", (data) => {
      this.userId = data;
    });
  },
  methods: {
    //Отправка формы
    formSend: function () {
      //Проверка полей тип
      if (this.userId && this.soft) {
        this.btnLoader = true;
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url: "./ajax/ajax_ms002.php",
          data: {
            userId: this.userId,
            soft: this.soft,
            cmnt: this.cmnt,
          },
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
            console.log(error);
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка";
            this.btnLoader = false;
          });
      }
      if (!this.userId) {
        this.userId_err = "Необходимо выбрать сотрудника";
      }
      if (!this.soft) {
        this.soft_err = "Необходимо выбрать программное обеспечение";
      }
      if (!this.cmnt) {
        this.cmnt_err = "Укажите обоснование";
      }
    },
    //Действие кнопки "назад"
    formCancl: function () {
      this.$router.go(-1);
    },
  },
};
</script>