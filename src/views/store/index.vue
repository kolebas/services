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
            @click="formSend('addOrder')"
            >Отправить</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-col cols="12">
      <v-card-title class="text-xl-h4 ml-8 text-uppercase font-weight-medium">
        <img
          class="mr-3"
          src="https://portal.ahstep.ru/upload/iblock/b68/icons8-онлайн-магазин-30.png"
        />
        {{ $router.currentRoute.name }}
        <v-spacer></v-spacer>
        <v-btn
          v-if="getAdminOrderAccess"
          @click="$router.push('/storeadmin')"
          class="ml-4"
        >
          <v-icon left grey>mdi-information-outline</v-icon>Управление заказами
        </v-btn>
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
    <template>
      <v-card min-width="100%">
        <Alert
          :text="accessText"
          :type="'info'"
          :colorBorder="true"
          :borderType="'bottom'"
          :btnBack="false"
        />
        <v-row class="mx-auto">
          <v-col v-for="item in storeItems" :key="item.items" cols="2">
            <v-card shaped elevation="3" class="mx-auto">
              <v-list-item @click="showDialogItem(item)">
                <v-list-item-avatar tile rounded size="80">
                  <v-img :src="'https://portal.ahstep.ru' + item.img" contain></v-img>
                </v-list-item-avatar>
                <v-list-item-title>{{ item.text }}</v-list-item-title>
              </v-list-item>
              <v-divider />
              <v-card-actions>
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
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-card>
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
    userId: "",
    userGroup: [],
    address: "",
    fio: "",
    tel: "",
    type: false,
    numberRule: [
      (v) =>
        (!isNaN(parseFloat(v)) && v >= 0 && v <= 999) ||
        "Неправильное значение",
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
    dialogPreview: false,
    itemPreview: [],
    dialog: false,
    btnLoader: false,
    OrderItems: [],
    accessText:
      "Уважаемые коллеги, заказы на молочную продукцию принимаются с понедельника 9:00 по среду 18:00, получение заказов по молочной продукции осуществляется по пятницам в центральном офисе по адресу: пер. Соборный, 19",
    sourceUrl: "./ajax/marketing/store.php",
  }),
  computed: {
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
    //Вычисление суммы заказа
    getOrderSum() {
      var sum = 0;
      for (let i = 0; i < this.OrderItems.length; i++) {
        sum = sum + this.OrderItems[i].value * this.OrderItems[i].price;
      }
      return sum + " Р.";
    },
    //Получение прав для администрирования магазина
    getAdminOrderAccess() {
      if (this.userGroup.includes("49")) {
        return true;
      } else {
        return false;
      }
    },
    //Получение дня недели
    getOrderDay() {
      let date = new Date();
      if (date.getDay() > 0 && date.getDay() < 4) {
        return true;
      } else {
        return false;
      }
    },
  },
  mounted() {
    this.getuserInfo();
    this.getStoreItems(95, 856);
  },
  methods: {
    //Получение информации о авторизованном пользователе
    getuserInfo() {
      axios
        .get("./ajax/ajax_usr.php", {})
        .then(
          (response) => (
            (this.userId = response.data[0].ID),
            (this.userGroup = response.data[0].GROUP)
          )
        );
    },
    //Отправка в данных в бизнес процесс
    formSend(type) {
      //Проверка полей тип
      if (this.OrderItems) {
        this.btnLoader = true;
        axios({
          method: "post",
          withCredentials: true,
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          url: this.sourceUrl,
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
      let itemPosition = this.OrderItems.find((array) => array.id === obj.id);
      if (itemPosition) {
        for (let i in this.OrderItems) {
          if (this.OrderItems[i].id == obj.id) {
            this.OrderItems[i].value++;
          }
        }
      } else {
        this.OrderItems.push(obj);
      }
    },
    //Открытие заказа
    clickOrders() {
      this.dialog = true;
    },
    //Открытие карточки позиции
    showDialogItem(item) {
      (this.dialogPreview = true), (this.itemPreview = item);
    },
    //Получение списка позиций
    getStoreItems(infoblockID, sectionID) {
      axios
        .get(this.sourceUrl, {
          params: {
            type: "getStoreItems",
            id: infoblockID,
            sectionID: sectionID,
          },
        })
        .then((response) => (this.storeItems = response.data));
    },
    //Удаление позиции из заказа
    removeItemInOrder(item) {
      for (var i = this.OrderItems.length - 1; i >= 0; i--) {
        if (this.OrderItems[i].id === item.id) {
          this.OrderItems.splice(i, 1);
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
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
  opacity: 0;
}
</style>
