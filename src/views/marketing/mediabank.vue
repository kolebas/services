<template >
  <v-row class="mx-auto">
    <v-dialog v-if="itemPreview" v-model="dialogPreview" width="60%">
      <v-card>
        <v-card-title>{{ itemPreview.text }}</v-card-title>
        <v-divider />
        <v-container>
          <v-row class="mx-auto">
            <v-col cols="5">
              <v-img
                max-height="480"
                max-width="320"
                class="mx-auto"
                :src="'https://portal.ahstep.ru' + itemPreview.img"
              />
            </v-col>
            <v-divider inset vertical />
            <v-col cols="6">
              <v-row class="mb-4">
                <v-col cols="4"> Цена: </v-col>
                <v-col cols="8">
                  <b>{{ itemPreview.price }} Р.</b>
                </v-col>
              </v-row>
              <v-row>
                <v-btn
                  large
                  class="mx-auto"
                  outlined
                  :disabled="!getOrderDay"
                  color="indigo"
                  @click="addOrder(itemPreview)"
                  >{{ setTitleButtonAddItem }}
                  <v-icon right color="green">mdi-plus-circle-outline</v-icon>
                </v-btn>
              </v-row>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialog" v-if="OrderItems.length > 0" width="60%">
      <v-card>
        <v-card-title class="headline grey lighten-2" primary-title>
          {{ setTitleCard }}</v-card-title
        >
        <template>
          <Input v-if="type === 1" :arrInput="input" />
          <v-divider />
          <v-list-item dense v-for="item in OrderItems" :key="item.id">
            <v-col cols="7" class="my-n4">
              <v-card-title>{{ item.text }}</v-card-title>
            </v-col>
            <v-col cols="2" class="my-n4 d-flex justify-center">
              <v-text-field
                outlined
                type="number"
                :rules="numberRule"
                prepend-icon="mdi-minus"
                @click:prepend="item.value--"
                append-outer-icon="mdi-plus"
                @click:append-outer="item.value++"
                dense
                :messages="item.price + ' Р/' + item.tara"
                :suffix="item.tara"
                v-model="item.value"
                class="pt-6"
              />
            </v-col>
            <v-col cols="2" class="d-flex justify-center">
              <b> {{ item.price * item.value }} Р.</b>
            </v-col>
            <v-col cols="1" class="d-flex justify-center">
              <v-icon @click="removeItemInOrder(item)" color="error">
                mdi-minus-circle-outline
              </v-icon>
            </v-col>
          </v-list-item>
          <div class="d-flex justify-end mb-2 mr-16">
            <b>Итого: {{ getOrderSum }}</b>
          </div>
        </template>
        <v-divider />
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            :loading="btnLoader"
            text
            @click="formSend(type)"
            >Отправить</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-col cols="12">
      <v-card-title class="text-xl-h4 ml-8 text-uppercase font-weight-medium">
        <img
          class="mr-3"
          src="https://img.icons8.com/dusk/64/000000/marketing.png"
        />
        {{ name }}
        <v-spacer></v-spacer>
        <v-expand-x-transition>
          <v-btn v-if="type === 0 && userId" @click="dialogAdd = true">
            <v-icon left color="green">mdi-plus-circle-outline</v-icon>Добавить
            элемент
          </v-btn>
        </v-expand-x-transition>
        <!-- <v-btn @click="dialog = true" class="ml-4">
          <v-icon left grey>mdi-information-outline</v-icon>инструкция работы с
          разделом
        </v-btn> -->
        <v-expand-x-transition>
          <v-btn
            fab
            @click="clickOrders()"
            v-if="OrderItems.length > 0"
            class="ml-4"
          >
            <v-badge color="green" :content="OrderItems.length">
              <v-icon grey>mdi-cart-arrow-down</v-icon>
            </v-badge>
          </v-btn>
        </v-expand-x-transition>
      </v-card-title>
      <v-divider />
    </v-col>
    <v-col v-for="section in sections" :key="section.name" cols="4">
        <v-card
          @click="
            type = section.id;
            selectItem = true;
            getStoreItems(95, 856);
            
          "
          class="mx-auto"
        >
          <v-card-title shaped>
            <v-icon large left :color="section.color">
              {{ section.img }}
            </v-icon>
            {{ section.name }}
          </v-card-title>
        </v-card>
    </v-col>
    <template v-if="type === 2">
      <Alert :text="accessText" :type="'info'" :colorBorder="true" :borderType="'bottom'" :btnBack="false" />
      <v-col v-for="item in storeItems" :key="item.items" cols="2">
        <v-card shaped elevation="3" class="mx-auto">
          <v-list-item @click="showDialogItem(item)">
            <v-list-item-avatar tile rounded size="80">
              <v-img :src="'https://portal.ahstep.ru' + item.img"></v-img>
            </v-list-item-avatar>
            <v-list-item-title>{{ item.text }}</v-list-item-title>
          </v-list-item>
          <v-divider />
          <v-card-actions>
            <template>
              <v-btn
                class="my-2"
                outlined
                color="indigo"
                :disabled="!getOrderDay"
                @click="addOrder(item)"
              >
                {{ setTitleButtonAddItem }}
                <v-icon right color="green">mdi-plus-circle-outline</v-icon>
              </v-btn>
              <v-spacer></v-spacer>
              <div class="mr-2">
                <b>{{ item.price }} Р/{{ item.tara }}</b>
              </div>
              <template v-if="condition">
                <v-btn fab x-small color="primary"
                  ><v-icon>mdi-pencil</v-icon></v-btn
                >
                <v-btn fab x-small color="error"
                  ><v-icon>mdi-delete</v-icon></v-btn
                >
              </template>
            </template>
          </v-card-actions>
        </v-card>
      </v-col>
    </template>
    <template v-if="type === 1">
      <v-col v-for="bi in bankItems" :key="bi.link" cols="3">
        <transition name="fade">
        <v-card v-if="type === 1" shaped elevation="3" class="mx-auto" max-width="344">
          <v-img :src="require('./img/' + bi.img)"></v-img>
          <v-card-subtitle class="font-weight-black mb-n6">
            {{ bi.text }}
          </v-card-subtitle>
          <v-card-actions>
            <v-btn
              class="my-2"
              outlined
              color="deep-purple accent-4"
              :href="bi.link"
              target="_blank"
            >
              Смотреть
              <v-icon large right color="red">mdi-youtube</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
        </transition>
      </v-col>
    </template>
    <template v-if="type === 0">
      <v-col v-for="item in items" :key="item.items" cols="3">
        <v-card shaped elevation="3" class="mx-auto" max-width="344">
          <v-list-item @click="showDialogItem(item)">
            <v-list-item-avatar tile size="80" >
              <v-img
                :src="
                  'https://portal.ahstep.ru/ahstep/services/img/marketing/inventory/' +
                  item.img
                "
              ></v-img>
            </v-list-item-avatar>
            <v-list-item-title>{{ item.text }}</v-list-item-title>
          </v-list-item>
          <v-divider />
          <v-card-actions>
            <template>
              <v-btn
                class="my-2"
                outlined
                color="indigo"
                disabled
                @click="addOrder(item.id)"
              >
                {{ setTitleButtonAddItem }}
                <v-icon right color="green">mdi-plus-circle-outline</v-icon>
              </v-btn>

              <v-spacer></v-spacer>
              <template v-if="condition">
                <v-btn fab x-small color="primary"
                  ><v-icon>mdi-pencil</v-icon></v-btn
                >
                <v-btn fab x-small color="error"
                  ><v-icon>mdi-delete</v-icon></v-btn
                >
              </template>
            </template>
          </v-card-actions>
        </v-card>
      </v-col>
    </template>
  </v-row>
</template>

<script>
import axios from "axios";
import Input from "@/components/Input.vue";
import Alert from "@/components/Alert.vue";
export default {
  components: {
    Input,
    Alert,
  },
  data: () => ({
    show: true,
    name: "маркетинг",
    userId: "",
    address: "",
    fio: "",
    tel: "",
    type: false,
    numberRule: [
      (v) =>
        (!isNaN(parseFloat(v)) && v >= 0 && v <= 999) ||
        "Неправильное значение",
    ],
    sections: [
      {
        id: 0,
        name: "Рекламная и сувенирная продукция",
        img: "mdi-palette-swatch-outline",
        color: "blue",
        show: false,
      },
      {
        id: 1,
        name: "Медиа банк",
        img: "mdi-video-vintage",
        color: "green",
        show: false,
      },
      {
        id: 2,
        name: "Магазин молочной продукции",
        img: "mdi-store",
        color: "red",
        show: false,
      },
    ],
    cats: [
      {
        id: 0,
        name: "Буклеты",
        icon: "mdi-book-open-blank-variant",
      },
      { id: 1, name: "Ручки", icon: "mdi-grease-pencil" },
      {
        id: 2,
        name: "Стеллы Указатели",
        icon: "mdi-sign-text",
      },
      {
        id: 3,
        name: "Таблички входные",
        icon: "mdi-card-text-outline",
      },
      {
        id: 4,
        name: "Флаги",
        icon: "mdi-flag-variant-outline",
      },
      { id: 5, name: "Ежедневники", icon: "mdi-book-open" },
      { id: 6, name: "Значки", icon: "mdi-palette" },
      {
        id: 7,
        name: "Календари",
        icon: "mdi-calendar-month-outline",
      },
      { id: 8, name: "Кепки", icon: "mdi-school" },
    ],
    bankItems: [
      {
        id: 0,
        text: "Запеканка манная в мультиварке",
        link: "https://www.youtube.com/watch?v=fMjKdL722Ek",
        img: "3.jpg",
      },
      {
        id: 1,
        text: "Вареники с вишней и сахаром",
        link: "https://www.youtube.com/watch?v=bfHoVOtBLdI",
        img: "1.jpg",
      },
      {
        id: 2,
        text: "Пшеничная каша на воде с маслом",
        link: "https://www.youtube.com/watch?v=W09w-3yLMP0",
        img: "1.jpg",
      },
      {
        id: 3,
        text: 'Мюсли из хлопьев 4 злака "СТЕПЬ"',
        link: "https://www.youtube.com/watch?v=fVrNBJ3XBSI",
        img: "7.jpg",
      },
      {
        id: 4,
        text: 'Полента из кукурузной крупы "СТЕПЬ" с креветками',
        link: "https://www.youtube.com/watch?v=oPQwAkHpNYQ",
        img: "10.jpg",
      },
      {
        id: 5,
        text: 'Ячневая каша "СТЕПЬ" на миндальном молоке',
        link: "https://www.youtube.com/watch?v=2S4Z3XYaRZM",
        img: "15.jpg",
      },
      {
        id: 6,
        text: 'Сладкий рис длиннозерный "СТЕПЬ" с чесноком и креветками',
        link: "https://www.youtube.com/watch?v=OJ7TU4hPK0U",
        img: "1.jpg",
      },
      {
        id: 7,
        text: 'Рис круглозерный "СТЕПЬ" с паприкой и сливками',
        link: "https://www.youtube.com/watch?v=hLRNjZlT8E8",
        img: "1.jpg",
      },
      {
        id: 8,
        text: "Курица в панировке из гречневых хлопьев",
        link: "https://www.youtube.com/watch?v=ETxxgJhthAg",
        img: "5.jpg",
      },
      {
        id: 9,
        text: "Овсяное печенье из геркулеса",
        link: "https://www.youtube.com/watch?v=l7WhxdlGPRo",
        img: "8.jpg",
      },
      {
        id: 10,
        text: 'Пшеничная каша "СТЕПЬ" с овощами',
        link: "https://www.youtube.com/watch?v=y7rZTbIagMQ",
        img: "12.jpg",
      },
    ],
    storeItems: [],
    selectItem: "",
    input: [
      {
        id: 0,
        label: "Адрес доставки:",
        value: "",
        dense: true,
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        class: "mb-n4",
      },
      {
        id: 1,
        label: "ФИО контактного лица:",
        value: "",
        dense: true,
        cs: "12",
        sm: "6",
        md: "6",
        type: "string",
        outlined: true,
        class: "mb-n4",
      },
      {
        id: 0,
        label: "Телефон для связи:",
        value: "",
        dense: true,
        cs: "10",
        sm: "4",
        md: "3",
        type: "string",
        outlined: true,
        class: "mb-n4",
      },
    ],
    items: [
      {
        id: "0",
        text: "Буклет",
        img: "1.jpg",
        subText: [
          {
            id: "0",
            title: "Формат",
            text: "книжный, полноцветный, двухсторонний",
          },
          {
            id: "1",
            title: "Примечание",
            text:
              "Имиджевый буклет с краткой информацией о Агрохолдинге «СТЕПЬ» предназначенный для партнеров, пайщиков",
          },
        ],
        value: "",
      },
      {
        id: "1",
        text: "Металлическая табличка",
        img: "4.jpg",
        subText: [
          {
            id: "0",
            title: "Размер",
            text: "адаптивный ",
          },
          {
            id: "1",
            title: "Назначение",
            text: "оформление входной группы ",
          },
          {
            id: "2",
            title: "Использование",
            text: " ДЗК, Центральный офиc",
          },
        ],
        value: "",
      },
      {
        id: "2",
        text: "Ручка Portobello с фирменной гравировкой",
        title: "Брендированная металлическая ручка Portobello",
        img: "2.jpg",
        subText: [
          {
            id: "0",
            title: "Механизм",
            text: "автоматическая",
          },
        ],
        value: "",
      },
      {
        id: "3",
        text: "Степь указатели",
        img: "4.jpg",
        subText: [
          {
            id: "0",
            title: "Назначение",
            text: "Подтверждение принадлежности ДЗК Агрохолдинга «СТЕПЬ»",
          },
        ],
        value: "",
      },
      {
        id: "4",
        text: "Степь флаг 1.5х0.9 белый",
        img: "5.jpg",
        subText: [
          {
            id: "0",
            title: "Назначение",
            text:
              "располагается на входных группах ДЗК, используется на внешних и внутренних мероприятиях Агрохолдинга «СТЕПЬ».",
          },
        ],
        value: "",
      },
      {
        id: "5",
        text: "Степь флаг 1.0х0.6 белый",
        img: "6.jpg",
        subText: [
          {
            id: "0",
            title: "Назначение",
            text:
              "располагается на входных группах ДЗК, используется на внешних и внутренних мероприятиях Агрохолдинга «СТЕПЬ».",
          },
        ],
        value: "",
      },
      {
        id: "6",
        text: "Степь флаг 1.0х3.0 белый",
        img: "6.jpg",
        subText: [
          {
            id: "0",
            title: "Назначение",
            text:
              "располагается на входных группах ДЗК, используется на внешних и внутренних мероприятиях Агрохолдинга «СТЕПЬ».",
          },
        ],
        value: "",
      },
      {
        id: "7",
        text: "Фирменный ежедневник ",
        img: "17.jpg",
        subText: [
          {
            id: "0",
            title: "Формат",
            text: "А5 15х20.5",
          },
        ],
        value: "",
      },
      {
        id: "8",
        text: "Значок Агрохолдинг СТЕПЬ",
        img: "9.jpg",
        subText: [
          {
            id: "0",
            title: "Назначение",
            text: "фирменная символика",
          },
        ],
        value: "",
      },
      {
        id: "9",
        text: "Фирменный календарь ",
        img: "10.jpg",
        value: "",
      },
      {
        id: "10",
        text: "Фирменный пакет ",
        img: "18.jpg",
        subText: [
          {
            id: "0",
            title: "Размер",
            text: "300х420х150",
          },
        ],
        value: "",
      },
    ],
    dialogPreview: false,
    itemPreview: [],
    dialog: false,
    btnLoader: false,
    OrderItems: [],
    accessText: "Уважаемые коллеги, сообщаем, что заказы на молочную продукцию принимаются с понедельника 9:00 по среду 18:00",
  }),
  computed: {
    /*  itemPreview() {
      return this.items[this.idPreview];
    }, */
    //Установка заголовка карточки
    setTitleCard() {
      this.type;
      if (this.type == 2) {
        return "Корзина";
      }
      return "Заявка на медиа продукцию";
    },
    //Установка названия кнопки для добаления в заказ
    setTitleButtonAddItem() {
      this.type;
      if (this.type == 2) {
        return "В заказ";
      }
      return "В заявку";
    },
    condition() {
      this.userId;
      return this.userId == 1 || this.userId == 1940;
    },
    //Вычисление суммы заказа
    getOrderSum() {
      var sum = 0;
      for (let i = 0; i < this.OrderItems.length; i++) {
        sum = sum + this.OrderItems[i].value * this.OrderItems[i].price;
      }
      return sum + " Р.";
    },
    //Получение дня недели
    getOrderDay() {
      let date = new Date();
      if (date.getDay > 0 && date.getDay < 4){
        return true
      }
      else {return false}
    }
  },
  mounted() {
    axios
      .get("./ajax/ajax_usr.php", {})
      .then((response) => (this.userId = response.data));
  },
  methods: {
    //Отправка в данных в бизнес процесс
    formSend(type) {
      //Проверка полей тип
      if (this.OrderItems) {
        this.btnLoader = true;
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url: "./ajax/marketing/mediabank.php",
          data: {
            fio: this.fio,
            tel: this.tel,
            address: this.address,
            items: this.OrderItems.filter((getItem) => getItem.value > 0),
            type: type,
          },
        })
          .then((response) => {
            if (response.status == 200) {
              this.dialog = false;
              this.dialogMessage =
                "Успешно. Номер вашей заявки: " + response.data;
              this.btnLoader = false;
              this.OrderItems = [];
            }
          })
          .catch((error) => {
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка:" + error;
          });
      }
    },
    selectedItem() {
      this.selectItem = true;
    },
    //Добавление к позиции в заказ
    addOrder(item) {
      let obj = {
        id: item.id,
        text: item.text,
        price: item.price,
        tara: item.tara,
        value: 1,
      };
      this.OrderItems.push(obj);
      //this.order = this.OrderItems.length;
      //this.ordersShow = true;
    },
    //Открытие заказа
    clickOrders() {
      this.dialog = true;
    },
    //Открытие карточки позиции
    showDialogItem(item) {
      (this.dialogPreview = true), (this.itemPreview = item);
      console.log(item);
    },
    //Получение списка позиций
    getStoreItems(infoblockID, sectionID) {
      axios
        .get(
          "https://portal.ahstep.ru/ahstep/services/ajax/marketing/store.php",
          {
            params: {
              type: "getStoreItems",
              id: infoblockID,
              sectionID: sectionID,
            },
          }
        )
        .then((response) => (this.storeItems = response.data));
    },
    //Удаление позиции из заказа
    removeItemInOrder(item) {
      for (var i = this.OrderItems.length - 1; i >= 0; i--) {
        if (this.OrderItems[i].id === item.id) {
          this.OrderItems.splice(i, 1);
          console.log(this.OrderItems);
        }
      }
    },
  },
};
</script>

<style>
.hover_card:hover {
  border-left: 5px solid green !important;
}
.click_card {
  border: 1px solid green !important;
}
.listitem {
  border-bottom: 1px solid rgb(201, 201, 201) !important;
}
.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active до версии 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
  opacity: 0;
}
</style>
