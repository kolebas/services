<template>
  <v-container fluid>
    <DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage" />
    <TitleService />
    <v-card min-height="800px" class="py-12">
      <v-row>
        <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
          <RqCardTitle
            :title="$router.currentRoute.name"
            :sub_message="sub_message"
          ></RqCardTitle>
          <hr />
          <Input :arrInput="input" />
          <hr />
          <v-card-actions class="py-4">
            <div class="mx-auto">
              <v-btn
                class="mx-1"
                :loading="btnLoader"
                :disabled="btnStatus"
                color="green lighten-2 white--text"
                @click="checkData()"
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
import RqCardTitle from "@/components/RqCardTitle.vue";
import DialogAfterSendFrom from "@/components/DialogAfterSendForm.vue";
import Input from "@/components/Input.vue";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    RqCardTitle,
    DialogAfterSendFrom,
    Input,
    TitleService,
  },
  data: () => ({
    sub_message:
      "В рамках исполнения данной заявки будет запущено согласование запрошенных доступов. При изменении статуса заявки, вы будете получать уведомления на электронный почтовый ящик, указанный у вас в профиле. Также вы можете отслеживать статус заявки в разделе.",
    btnLoader: false,
    btnStatus: false,
    input: [
      {
        id: 0,
        name: "Тип заявки:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "select",
        select_arr: [
          "Создать новый электронный адрес рассылки",
          "Добавить сотрудников в существующий адрес рассылки",
          "Удалить сотрудников из существующего адрес рассылки",
          "Создать дополнительный электронный ящик",
        ],
        outlined: true,
        dense: true,
        solo: true,
        multiple: false,
        err: "",
      },
      {
        id: 1,
        name: "Электронный адрес:",
        value: "",
        suffix: "@ahstep.ru",
        cs: "12",
        sm: "3",
        md: "3",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
        visible: false,
      },
      {
        id: 2,
        name: "ФИО сотрудника:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "selectUsr",
        class: "mt-n2",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
        multiple: true,
        visible: false,
      },
    ],
    dialog: false,
    dialogMessage: "",
  }),
  methods: {
    inputCondition() {
      return this.input;
    },
  },
  computed: {
    watchType() {
      return this.input[0].value;
    },
  },
  watch: {
    watchType: function (val) {
      if (
        val == "Добавить сотрудников в существующий адрес рассылки" ||
        val == "Создать новый электронный адрес рассылки" ||
        val != null
      ) {
        this.input[1].visible = true;
        this.input[2].visible = true;
      }
    },
  },
};
</script>

<style>
</style>