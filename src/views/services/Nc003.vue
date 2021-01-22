<template>
  <v-container>
    <v-row>
      <DialogAfterSendForm
        :dialog="dialog"
        :warnMessage="dialogMessage"
        :subMessage="subDialogMessage"
      />
      <v-card max-width="65%" raised class="mx-auto" color="grey lighten-4">
        <RqCardTitle :title="title" :sub_message="subtitle"></RqCardTitle>
        <hr />
        <br>
        <v-row>
          <v-col cols="5">
            <v-card-text class="subtitle-1 text-right pt-2"
              >Каталог:</v-card-text
            >
          </v-col>
          <v-col cols="6">
            <v-autocomplete
              :items="fldrs"
              v-model="fld_name"
              outlined
              solo
              dense
              clearable
              cache-items
              autofocus
              label="Начните набирать название каталога"
              item-text="NAME"
              item-value="NAME"
              :error-messages="fld_err"
            >
              <template v-slot:selection="data">
                <v-chip
                  color="#bcedfc"
                  label
                  close
                  @click:close="fld_name = ''"
                >
                  <v-icon color="orange lighten-3" left>mdi-folder</v-icon>
                  {{ data.item.NAME }}
                </v-chip>
              </template>
            </v-autocomplete>
          </v-col>
        </v-row>
        <SelectUsr
          :cols_title="5"
          v-for="item in users"
          :multiple="item.multiple"
          :key="item.title"
          :id="item.id"
          :cols_input="6"
          :title="item.title"
          :userId_err="item.err"
        />
        <v-row class="mb-n6">
          <v-col cols="5">
            <v-card-text class="subtitle-1 text-right pt-2"
              >Комментарий:</v-card-text
            >
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
              >Отправить</v-btn
            >
            <v-btn class="mx-1" @click="formCancl()">Отмена</v-btn>
          </div>
        </v-card-actions>
      </v-card>
    </v-row>
  </v-container>
</template>

<script>
import { bus } from "@/main.js";
import axios from "axios";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import RqCardTitle from "@/components/RqCardTitle";
import SelectUsr from "@/components/SelectUsr";
export default {
  components: {
    DialogAfterSendForm,
    RqCardTitle,
    SelectUsr,
  },
  data: () => ({
    title: "Заявка на добавление/изменение доступа к сетевому каталогу",
    subtitle:
      "Данная услуга позволяет изменить права доступа к ранее созданному сетевому каталогу (сетевой папки). Также вы сможете отслеживать статус заявки в разделе",
    fldrs: [],
    fld_name: "",
    fld_err: "",
    users: [
      {
        id: 1,
        title: "Пользователи с возможность записи:",
        value: "",
        multiple: true,
        err: "",
      },
      {
        id: 2,
        title: "Пользователи с возможность просмотра:",
        value: "",
        multiple: true,
        err: "",
      },
      {
        id: 3,
        title: "Пользователи которых нужно отключить от каталога:",
        value: "",
        multiple: true,
        err: "",
      },
    ],
    btnLoader: false,
    userId_err: "",
    cmnt: "",
    dialog: false,
    dialogMessage: "",
    subDialogMessage: "",
  }),
  created() {
    bus.$on("SelectUsr", (data) => {
      this.users[data.input_id - 1].value = data.userId;
      this.users[data.input_id - 1].err = "";
    });
  },
  mounted() {
    axios
      .post("./ajax/ajax_nc.php", {
        data: {
          type: "getFldr",
        },
      })
      .then((response) => (this.fldrs = response.data))
      .catch((error) => console.log(error));
  },
  methods: {
    formSend: function () {
      //Проверка полей тип
      if (
        (this.fld_name && this.users[0].value) ||
        (this.fld_name && this.users[1].value)
      ) {
        this.btnLoader = true;
        console.log(this.fld_name);
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url: "./ajax/ajax_nc.php",
          data: {
            type: "nc003",
            fld_name: this.fld_name,
            rw_usr_all: this.users[0].value,
            ro_usr_all: this.users[1].value,
            rm_usr_all: this.users[2].value,
            cmnt: this.cmnt,
          },
        })
          .then((response) => {
            if (response.status == 200) {
              this.dialog = true;
              this.dialogMessage =
                "Успешно. Номер вашей заявки: " + response.data;
              this.subDialogMessage =
                "Важно: после выполнения заявки необходимо выполнить перезагрузку";
              this.btnLoader = false;
            }
          })
          .catch((error) => {
            console.log(error);
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка";
          });
      }
      if (!this.fld_name && !this.users[0].value) {
        this.fld_err = "Необходимо выбрать каталог";
        this.users[0].err = "Необходимо выбрать сотрудника";
      }
      if (!this.fld_name && !this.users[1].value) {
        this.fld_err = "Необходимо выбрать каталог";
        this.users[1].err = "Необходимо выбрать сотрудника";
      }
    },
    formCancl: function () {
      this.$router.go(-1);
    },
  },
};
</script>

<style>
</style>