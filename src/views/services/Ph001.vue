<template>
  <v-container fluid>
    <v-dialog v-model="dialog" width="75%">
      <v-card>
        <v-card-title class="text-h5 grey lighten-2" primary-title color="red">
          {{ titleDialogParam }}
        </v-card-title>
        <v-card-text class="subtitle-1 mt-4" v-html="dialogMessage" />
        <v-divider />
        <v-card-actions>
          <div class="mx-auto">
            <v-btn color="green darken-1" text @click="dialog = false">
              Не согласен
            </v-btn>
            <v-btn
              color="green darken-1"
              text
              @click="
                dialog = false;
                funSwitch(true);
              "
            >
              Согласен
            </v-btn>
          </div>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <TitleService />
    <v-card min-height="800px" class="py-12">
      <v-row>
        <v-card max-width="65%" raised class="mx-auto" color="grey lighten-4">
          <RqCardTitle :title="title" :sub_message="subMessage"></RqCardTitle>
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
import RqCardTitle from "@/components/RqCardTitle";
import Input from "@/components/Input.vue";
import Buttons from "@/components/Buttons.vue";
import TitleService from "@/components/TitleService.vue";
export default {
  components: {
    RqCardTitle,
    Input,
    Buttons,
    TitleService,
  },
  data: () => ({
    title: "Предоставление служебной сотовой связи",
    subMessage:
      "Согласно действующей политике, данная заявка может сотрудником только для себя. При этом ваши данные будут переданы оператору сотовой связи для подтвержения личности вам необходимо будет подтвердить через портал госулуги Статус созданной заявки вы можете отслеживать в разделе ",
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
    route: 1,
    titleDialogParam:
      "Соглашение о предоставлении персональных данных для корпоративной мобильной связи",
    dialogMessage: `
    <p>   Заполняя форму «Предоставление служебной сотовой связи» на странице подачи заявки в личном кабинете корпоративного портала компаний группы АО Агрохолдинг «СТЕПЬ", расположенном по адресу в сети Интернет <a href="https://portal.ahstep.ru">https://portal.ahstep.ru</a> и нажимая кнопку «Отправить», Вы, свободно, своей волей и в своем интересе выражаете согласие на обработку АО Агрохолдинг «СТЕПЬ», находящегося по адресу: РФ, Ростовская область, г. Ростов-на-Дону, пер. Соборный, 19, с целью предоставления корпоративной мобильной связи для своих работников, следующих персональных данных:</p>
      <b>•	фамилия, имя, отчество;</b><br>
      <b>•	паспортные данные (серия, номер, дата выдачи, кем выдан, код подразделения);</b><br>
      <b>•	номер телефона;</b><br>
      <br>
      <p>   Нажимая кнопку «Отправить», разрешаете сбор, запись, систематизацию, накопление, хранение, уточнение (обновление, изменение), извлечение, использование, передачу (предоставление, доступ), удаление, уничтожение персональных данных. Обработка Ваших персональных данных осуществляться с использованием средств автоматизации.
      Нажимая кнопку «Отправить», не возражаете против передачи указанных персональных данных, в указанных целях, для их дальнейшей обработки по поручению АО Агрохолдинг «СТЕПЬ», в компании группы АО Агрохолдинг «СТЕПЬ» (Список организаций входящих в состав ГК Агрохолдинг «СТЕПЬ» расположен по адресу в сети Интернет: <a href="https://www.ahstep.ru/about-us/pravovaya-informatsiya">https://www.ahstep.ru/about-us/pravovaya-informatsiya</a>). А также, не возражаете против передачи указанных персональных данных для их дальнейшей обработки в ПАО «МТС», в рамках соблюдения Федерального закона от <b>7 июля 2003 года N 126-ФЗ "О связи" (в ред. ФЗ N533-ФЗ от 30.12.2020 г.)</b>.
      Данное согласие действует в течение срока существования учетной записи в личном кабинете на корпоративном портале, либо в течении действия трудового договора, либо в течении действия договора граждански-правового характера. Данное согласие может быть отозвано по Вашему письменному заявлению, либо при удалении учетной записи в личном кабинете на корпоративном портале, либо по окончанию действия трудового договора, либо по окончанию действия договора граждански-правового характера.
      Нажимая кнопку «Отправить», подтверждаете, что Вы ознакомлены с положениями законодательства Российской Федерации о персональных данных, политикой обработки персональных данных АО Агрохолдинг «СТЕПЬ», расположенной по адресу в сети Интернет - <a href="https://www.ahstep.ru/about-us/pravovaya-informatsiya">https://www.ahstep.ru/about-us/pravovaya-informatsiya</a>, а также с правами и обязанностями в этой области. Права и обязанности в области обработки и защиты персональных данных Вам полноценно разъяснены и понятны.</p>
`,
    dialog: false,
    btnLoader: false,
    sendButtonDisable: true,
    url: "./ajax/ajax_ph001.php",
  }),
  methods: {
    //Действие кнопки 'Мои заявки'
    btnToMyreq() {
      document.location.href = "/it-uslugi/helpdesk/my_ticket.php";
    },
    //Взаимодействие с диалогом
    funcDialog() {
      this.$router.go(-1);
    },
    //Взаимодействие с переключателем
    funSwitch(status) {
      let getSwitch = this.input.filter((item) => item.type == "switch");
      if (status == true) {
        getSwitch[0].value = true;
      }
    },
    checkInput(value) {
      this.sendButtonDisable = true;
      if (value.length < 10) {
        return "Поле обязательно для заполнения";
      }
      if (!Number.isInteger(Number(value))) {
        return "Недопустимый символ";
      }
      if (value.length > 13) {
        return "Слишком длинный номер";
      } 
      if (value == 0) {
        return "Недопустимое значение";
      }else {
        this.sendButtonDisable = false;
        return true;        
      }
    },
  },
  computed: {
    typeService() {
      let getTypeServiceValue = this.input.filter((item) => item.id == 0);
      return getTypeServiceValue[0].value;
    },
    changeSwitch() {
      let getSwitch = this.input.filter((item) => item.type == "switch");
      if (getSwitch.length > 0) {
        return getSwitch[0].value;
      } else {
        return false;
      }
    },
  },
  watch: {
    input: {
      handler: function () {
        for (let i = 0; i < this.input.length; i++) {
          if (this.input[i].value === "" && this.input[i].type != "textarea") {
            this.sendButtonDisable = true;
            break;
          }
          this.sendButtonDisable = false;
        }
      },
      deep: true,
    },
    typeService(val) {
      this.input.splice(1);
      if (val === "Перевод личного номера на корпоративный контракт") {
        this.dialog = true;
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
            this.checkInput,
            /*
            (value) => value.length > 0 || "Поле обязательно для заполнения",
            (value) => Number.isInteger(Number(value)) || "Недопустимый символ",
            (value) => value > 0 || "Недопустимое значение",
            (value) => value.length < 13 || "Слишком длинный номер",*/
          ],
          err: "",
          required: true,
        };
        this.input.push(addTypeService);
      }
      if (val === "Предоставаление нового номера") {
        this.dialog = true;
      }
      let addComment = {
        id: this.input.length + 1,
        name: "Комментарий:",
        value: "",
        cs: "11",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
      };
      let addCheckBox = {
        name: "С условиями обработки персональных данных ознакомлен:",
        value: "",
        type: "switch",
        label: "",
        class: "mt-2",
        cs: "11",
        sm: "6",
        md: "6",
      };
      this.input.push(addComment);
      this.input.push(addCheckBox);
    },
    changeSwitch(newValue) {
      if (newValue == true) {
        for (let i = 0; i < this.input.length; i++) {
          if (this.input[i].type == "switch") {
            this.input[i].label = "Подтверждаю";
          }
        }
      } else {
        this.sendButtonDisable = true;
      }
    },
  },
};
</script>