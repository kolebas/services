<template>
  <v-container fluid>
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
          <v-card-text> * Поля обязательные для заполнения </v-card-text>
          <hr />
          <Buttons
            :input="input"
            :sendButtonDisable="sendButtonDisable"
            ajax="./ajax/ajax_nsi.php"
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
    sub_message: "Вы сможете отслеживать статус заявки в разделе",
    sendButtonDisable: true,
    input: [
      {
        id: 0,
        name: "Вид номенклатуры:*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "autocomplete",
        items: [
          { NAME: "Аккумуляторы", ID: [2, 3, 5, 6, 7, 8, 9, 11] },
          { NAME: "Ветпрепараты и вет.материалы", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Внутренняя услуга", ID: [2, 6, 7, 8, 9] },
          { NAME: "Для закупок", ID: [1, 2, 6, 7, 8, 9] },
          { NAME: "Животные", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Запасные части", ID: [2, 3, 5, 6, 7, 10, 8, 9, 11] },
          { NAME: "Инвентарь", ID: [2, 4, 6, 7, 8, 9] },
          {
            NAME: "Канцтовары и хоз.принадлежности",
            ID: [2, 4, 6, 7, 8, 9],
          },
          { NAME: "Корма", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Крепежные изделия", ID: [2, 3, 4, 5, 6, 7, 8, 9] },
          {
            NAME: "Металлопрокат и листовой металл",
            ID: [2, 4, 6, 7, 8, 9],
          },
          { NAME: "Оборудование", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Основные средства", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Оргтехника и комплектующие", ID: [2, 4, 5, 6, 7, 8, 9] },
          { NAME: "Тара и тарные материалы", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Стройматериалы", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Технические жидкости", ID: [2, 3, 4, 6, 7, 8, 9] },
          { NAME: "Спецодежда и спецоснастка", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Топливо", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Смазочные материалы", ID: [2, 3, 4, 6, 7, 8, 9] },
          { NAME: "СЗР", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Семена", ID: [2, 3, 4, 6, 7, 8, 9] },
          { NAME: "Прочие материалы", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Удобрения", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Продукция", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Продукты питания", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Мясо и мясопродукты", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Услуга", ID: [2, 4, 6, 7, 8, 9] },
          { NAME: "Фильтры", ID: [2, 3, 5, 6, 7, 8, 9, 11] },
          { NAME: "Шины", ID: [2, 3, 5, 6, 7, 8, 9, 11] },
          { NAME: "Электроматериалы", ID: [1, 2, 4, 5, 6, 7, 8, 9] },
        ],
        outlined: true,
        dense: true,
        solo: true,
        err: "",
        required: true,
      },
    ],
    items: [
      {
        id: 1,
        name: "Статья бюджета закупок:*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "autocomplete",
        items: [
          { NAME: "Автострахование" },
          { NAME: "Аренда техники" },
          { NAME: "Аудит и оценка" },
          { NAME: "Бензин" },
          { NAME: "Ветеринарные и фитосанитарные услуги" },
          { NAME: "Ветпрепараты и медикаменты" },
          { NAME: "Газ сжиженный" },
          { NAME: "Дизельное топливо" },
          { NAME: "Животные" },
          { NAME: "Запчасти и расходные материалы" },
          { NAME: "Инвентарь" },
          { NAME: "Информационно-консультационные услуги" },
          { NAME: "Канцтовары" },
          { NAME: "Корма" },
          { NAME: "Крепежные изделия" },
          { NAME: "Металлопрокат и листовой металл" },
          { NAME: "Мясо и мясопродукты" },
          { NAME: "Оборудование" },
          { NAME: "Оборудование (объекты основных средств)" },
          { NAME: "Оборудование к установке" },
          { NAME: "Оргтехника и расходные материалы" },
          { NAME: "Охранные услуги" },
          { NAME: "Подстилка" },
          { NAME: "Полуфабрикаты" },
          { NAME: "Посадочный материал" },
          { NAME: "Приобретение прочих активов" },
          { NAME: "Программное обеспечение (в т.ч.сопровождение)" },
          { NAME: "Продукты питания" },
          { NAME: "Продукция" },
          { NAME: "Продукция из материалов заказчика" },
          { NAME: "Прочая продукция животноводства" },
          { NAME: "Прочие материалы" },
          { NAME: "Прочие услуги" },
          { NAME: "Расходные ветеринарные материалы" },
          { NAME: "Расходы на фумигацию" },
          { NAME: "Семена и посадочный материал (покупные)" },
          { NAME: "Сертификация" },
          { NAME: "Смазочные материалы и тех. жидкости" },
          { NAME: "Спецодежда и спецоснастка" },
          { NAME: "Средства защиты растений" },
          { NAME: "Средства ОТ и безопасности" },
          { NAME: "Страхование прочее" },
          { NAME: "Стройматериалы" },
          { NAME: "Тара и тарные материалы" },
          { NAME: "Тара под товаром и порожняя" },
          { NAME: "Технические жидкости" },
          { NAME: "Технологические услуги" },
          { NAME: "Товары" },
          { NAME: "Товары на комиссии" },
          { NAME: "Топливо" },
          { NAME: "Транспортировка с/х продукции" },
          { NAME: "Транспортные услуги прочие" },
          { NAME: "Удобрения минеральные" },
          { NAME: "Упаковочный материал" },
          { NAME: "Услуги" },
          { NAME: "Услуги авиации" },
          { NAME: "Услуги по ремонту техники" },
          { NAME: "Услуги по сбору рабочих" },
          { NAME: "Услуги по содержанию оргтехники" },
          { NAME: "Услуги по уборке" },
          { NAME: "Услуги элеваторов" },
          { NAME: "Фильтры" },
          { NAME: "Хозтовары" },
          { NAME: "Шины" },
          { NAME: "Электроматериалы" },
          { NAME: "Юридические услуги" },
        ],
        outlined: true,
        dense: true,
        solo: true,
        err: "",
        required: true,
      },
      {
        id: 2,
        name: "Предварительное наименование:*",
        label: "Коленвал ЯМЗ-236Д",
        hint: "Наименование вносится в соответствии с наименованием в приходном документе. Формат: 1-ая - Заглавная буква.  Только один знак '_' или '-'",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        rule: [
          (value) => !!value || "Поле обязательно для заполнения",          
        ],
        required: true,
      },
      {
        id: 3,
        name: "Характеристика:",
        label: "Пример: Винт регулир. коромысла ЯМЗ-236, -238, -240.",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
      },
      {
        id: 4,
        name: "Доп.характеристика:",
        label: "Пример: Наружнее применение",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
      },
      {
        id: 5,
        name: "Артикул:*",
        label: "Пример: CYFS12Y2",
        hint: "Артикул вносится в соответствии с артикулом в приходном документе.",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        err: "",
        required: true,
      },
      {
        id: 6,
        name: "Единица измерения:*",
        label: "Пример: шт./м./дн.",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        required: true,
      },
      {
        id: 7,
        name: "Ставка НДС:*",
        label: "Пример: 20%",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "select",
        select_arr: ["0%", "10%", "18%", "20%", "Без НДС"],
        outlined: true,
        dense: true,
        solo: true,
        err: "",
        required: true,
      },
      {
        id: 8,
        name: "Наименование номенклатуры:*",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        disabled: true,
        required: true,
      },
      {
        id: 9,
        name: "Комментарий:",
        label: "Пример: SAS Russia Training Center",
        hint: "Данная графа заполняется максимально подробно. (Оригинал или аналог и на какую технику планируется приобретение)",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "textarea",
        outlined: true,
        dense: true,
        solo: true,
        required: false,
      },
      {
        id: 10,
        name: "Бренд техники:*",
        label: "Пример: Камаз",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        dense: true,
        solo: true,
        required: true,
      },      
      {
        id: 11,
        name: "Скан приходного документа:",
        value: "",
        cs: "12",
        sm: "6",
        md: "6",
        type: "file",
        outlined: true,
        dense: true,
        solo: true,
      },
    ],
  }),
  computed: {
    fullName() {
      let previeName = this.items.filter(
        (item) => item.id == 2
      );
      let charasters = this.items.filter(
        (item) => item.id == 3
      );
      let charastersAditional = this.items.filter(
        (item) => item.id == 4
      );
      let str = previeName[0].value + " " + charasters[0].value + " " + charastersAditional[0].value;
      return (        
        str.charAt(0).toUpperCase() + str.slice(1)
      );
    },
    vidNomenklatury() {
      let vidNomenklaturyValue = this.input.filter(
        (item) => item.name == "Вид номенклатуры:*"
      );
      return vidNomenklaturyValue[0].value;
    },
  },
  watch: {
    fullName(val) {
      if (val) {
        var arrayItemFullName = this.items.filter(
          (item) => item.name == "Наименование номенклатуры:*"
        );
        arrayItemFullName[0].value = val;
      }
    },
    vidNomenklatury(val) {
      this.clearItemsValue();
      this.setVisibleInput(val);
    },
    input: {
      handler: function () {
        for (let i = 0; i < this.input.length; i++) {
          //console.log(this.input)
          if (this.input[i].value === "" && this.input[i].required === true) {
            this.sendButtonDisable = true;
            break;
          }
          this.sendButtonDisable = false;
          //console.log("a thing changed " + val[i].value + oldVal[i].value);
        }
      },
      deep: true,
    },
  },
  methods: {
    setVisibleInput(nomenklatura) {
      if (nomenklatura) {
        this.input.splice(1);
        let arrayInput = this.input
          .filter((item) => item.name == "Вид номенклатуры:*")[0]
          .items.filter((itemValue) => itemValue.NAME == nomenklatura);
        for (let i = 0; i < arrayInput[0].ID.length; i++) {
          let idInput = arrayInput[0].ID[i];
          let input = this.items.filter((item) => item.id == idInput);
          //let rule = [(value) => !!value || "Поле обязательно для заполнения"];
          //input[0].rule = rule;
          this.input.push(input[0]);
        }
      } else {
        for (let i = 0; i < this.items.length; i++) {
          this.input.splice(1);
        }
      }
    },
    clearItemsValue() {
      for (let i = 0; i < this.items.length; i++) {
        this.items[i].value = "";
      }
    },
  },
};
</script>

<style>
</style>