<template>
  <v-container fluid>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
    <TitleService />
    <v-card min-height="800px" class="py-12">
      <v-row>
        <v-card max-width="55%" raised class="mx-auto" color="grey lighten-4">
          <RqCardTitle :title="title" :sub_message="sub_message"></RqCardTitle>
          <hr />
          <Input :arrInput="input" />
          <hr />
          <Buttons
            :input="input"
            :sendButtonDisable="sendButtonDisable"
            :ajax="url"
          />
        </v-card>
      </v-row>
    </v-card>
  </v-container>
</template>

<script>
import DialogAfterSendForm from "@/components/DialogAfterSendForm";
import RqCardTitle from "@/components/RqCardTitle";
import Input from "@/components/Input.vue";
import Buttons from "@/components/Buttons.vue";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    DialogAfterSendForm,
    RqCardTitle,
    Input,
    Buttons,
    TitleService,
  },
  data: () => ({
    title: "Предоставление служебной сотовой связи",
    sub_message:
      "Согласно действующей политике, данная заявка может сотрудником только для себя. При этом ваши данные будут переданы оператору сотовой связи для подтвержения личности вам необходимо будет подтвердить через портал госулуги Статус созданной заявки вы можете отслеживать в разделе ",
    dialogMessage: "",
    input: [
      {
        id: 0,
        name: "Тип услуги:*",
        value: "",
        label: "Выбери необходимый тип услуги",
        select_arr: [
          "Предоставаление нового номера",
          "Перевод личного номера на корпоративный контракт",
        ],
        cs: "11",
        sm: "6",
        md: "6",
        type: "select",
        outlined: true,
        dense: true,
        solo: true,
        required: false,
      },
    ],
    dialog: false,
    btnLoader: false,
    sendButtonDisable: true,
    termsCheckBox: false,
    url: "./ajax/ajax_ph001.php",
  }),
  methods: {
    //Действие кнопки "Мои заявки"
    btnToMyreq() {
      document.location.href = "/it-uslugi/helpdesk/my_ticket.php";
    },
    //Взаимодействие с диалогом
    funcDialog() {
      this.$router.go(-1);
    },
  },
  computed: {
    typeService() {
      let getTypeServiceValue = this.input.filter((item) => item.id == 0);
      return getTypeServiceValue[0].value;
    },
  },
  watch: {
    input: {
      handler: function () {
        for (let i = 0; i < this.input.length; i++) {
          if (this.input[i].value === "") {
            this.sendButtonDisable = true;
            break;
          }
          this.sendButtonDisable = false;
          console.log(this.input);
        }
      },
      deep: true,
    },
    typeService(val) {
      if (val === "Перевод личного номера на корпоративный контракт") {
        let addTypeService = {
          id: this.input.length + 1,
          name: "Номер телефона:*",
          label: "Пример: +79998887766",
          value: "",
          cs: "11",
          sm: "6",
          md: "6",
          type: "string",
          outlined: true,
          dense: true,
          solo: true,
          rule: [
            (value) => value.length > 0 || "Поле обязательно для заполнения",
            (value) => Number.isInteger(Number(value)) || "Недопустимый символ",
            (value) => value > 0 || "Недопустимое значение",
            (value) => value.length < 13 || "Слишком длинный номер",
          ],
          err: "",
          required: true,
        };
        this.input.push(addTypeService);
      } else {
        this.input.splice(1, 1);
      }
    },
  },
};
</script>