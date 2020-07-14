<template>
  <v-container>
    <v-row>
      <v-card max-width="65%" raised class="mx-auto" color="grey lighten-4">
        <RqCardTitle :title="title" :sub_message="subtitle"></RqCardTitle>
        <hr />
        <SelectOrg :cols_title="5" :cols_input="6" title="ФИО:" :userId_err="userId_err" />
        <InputCard
          v-for="item in inputs"
          :key="item.id"
          :id="item.id"
          :title="item.title"
          :input_err="item.err"
          :label="item.label"
          :suffix="item.suffix"
          :cols_title="item.cols_title"
          :cols_input="item.cols_input"
        ></InputCard>
        <SelectUsr
          :cols_title="5"
          v-for="item in users"
          :key="item.title"
          :cols_input="6"
          :title="item.title"
          :userId_err="userId_err"
        />
        <v-row class="mb-n6">
          <v-col cols="5">
            <v-card-text class="subtitle-1 text-right pt-2">Описание каталога:</v-card-text>
          </v-col>
          <v-col cols="6">
            <v-textarea
              v-model="cmnt"
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
            >Отправить</v-btn>
            <v-btn class="mx-1" @click="formCancl()">Отмена</v-btn>
            <v-btn class="mx-1" @click="CatalogName()">Отмена111</v-btn>
          </div>
        </v-card-actions>
      </v-card>
    </v-row>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import axios from "axios";
import RqCardTitle from "@/components/RqCardTitle";
import SelectUsr from "@/components/SelectUsr";
import SelectOrg from "@/components/SelectOrg";
import InputCard from "@/components/InputCard";
export default {
  components: {
    RqCardTitle,
    SelectUsr,
    SelectOrg,
    InputCard
  },
  data: () => ({
    title: "Заявка на создание сетевого каталога",
    subtitle:
      'Данная услуга позволяет создать сетевой каталог (сетевую папку). Название каталога складывается из аббревиатуры организации, далее название департамента, затем название папки. Пример: «AHS-Бухгалтерия-Оперативный учет», где «AHS» – аббревиатура АО Агрохолдинг "СТЕПЬ", «Бухгалтерия» – наименование департамента, «Оперативный учет» - название папки. Также вы сможете отслеживать статус заявки в разделе',
    inputs: [
      {
        id: "1",
        title: "Название структурного подразделения:",
        err: "",
        label: "Например: Отдел кадров",
        value: null,
        cols_title: "5",
        cols_input: "6"
      },
      {
        id: "2",
        title: "Название каталога:",
        err: "",
        label: "Например: График отпусков",
        value: null,
        cols_title: "5",
        cols_input: "6"
      },
      {
        id: "3",
        title: "Итоговое название каталога:",
        err: "",
        label: "",
        value: "",
        cols_title: "5",
        cols_input: "6"
      }
    ],
    users: [
      { id: "1", title: "Бизнес-владелец", value: ""},
      { id: "2", title: "Пользователи с возможность записи:", value: "" },
      { id: "3", title: "Пользователи с возможность просмотра:", value: "" }
    ],
    userId_err: "",
    btnLoader: false,
    cmnt: ""
  }),
  created() {
    bus.$on("SelectUsr", data => {
        for(let i = 0; i < this.users.length; i++){
            console.log(data)
        }
      //this.users[].value = data;
    });
    bus.$on("inputCard", data => {
      this.inputs[data.input_id - 1].value = data.value;
      this.inputs[data.input_id - 1].err = "";
    }),
      bus.$on("selectOrg", data => {
        this.org_err = "";
        this.org_name = data;
      });
  },
  methods: {
    //Показ предупреждения
    showSoftModal: function() {
      if (this.soft == "Консультант +") {
        this.softModal = true;
      }
    },
    //Закрыть предупреждение
    closeSoftModal: function() {
      this.softModal = false;
    },
    formSend: function() {
      //Проверка полей тип
      if (this.userId && this.soft) {
        this.btnLoader = true;
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url: "./ajax/ajax_nc001.php",
          data: {
            userId: this.userId,
            soft: this.soft,
            cmnt: this.cmnt
          }
        })
          .then(response => {
            if (response.status == 200) {
              this.dialog = true;
              this.dialogMessage =
                "Успешно. Номер вашей заявки: " + response.data;
              this.btnLoader = false;
            }
          })
          .catch(error => {
            console.log(error);
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка";
          });
      }
      if (!this.userId) {
        this.userId_err = "Необходимо выбрать сотрудника";
      }
      if (!this.soft) {
        this.type_err = "Необходимо выбрать программное обеспечение";
      }
    },
    formCancl: function() {
      this.$router.go(-1);
    },
    CatalogName() {        
      this.inputs[2].value = this.inputs[0].value + '-' + this.inputs[1].value;
      console.log(this.inputs[2].value)
    }
  }
};
</script>

<style>
</style>