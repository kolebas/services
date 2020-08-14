<template>
    <v-row class="mx-auto">
        <DialogAfterSendForm
            :dialog="dialogParam"
            disablePersistentParam
            widthDialogParam="60%"
            titleDialogParam="Заявка на медиа продукцию"
            btnTextParam="Отправить"
            :dataArray="itemsOrder"
            route="1"
        >
        </DialogAfterSendForm>
        <v-col cols="12">
            <v-card-title
                class="text-xl-h4 ml-8 text-uppercase font-weight-medium"
            >
                <img
                    class="mr-3"
                    src="https://img.icons8.com/dusk/64/000000/marketing.png"
                />
                {{ name }}
                <v-spacer></v-spacer>
                <v-btn @click="dialog = true">
                    <v-icon left grey>mdi-information-outline</v-icon>инструкция
                    работы с разделом
                </v-btn>
                <v-expand-x-transition>
                    <v-btn
                        fab
                        @click="clickOrders()"
                        v-if="ordersShow"
                        class="ml-4"
                    >
                        <v-badge color="green" :content="order">
                            <v-icon grey>mdi-cart-arrow-down</v-icon>
                        </v-badge>
                    </v-btn>
                </v-expand-x-transition>
            </v-card-title>
            <v-divider />
        </v-col>

        <v-col v-for="section in sections" :key="section.name" cols="6">
            <v-card
                @click="
                    type = section.id;
                    selectItem = true;
                "
                class="mx-auto"
                max-width="60%"
            >
                <v-card-title shaped>
                    <v-icon large left :color="section.color">{{
                        section.img
                    }}</v-icon>
                    {{ section.name }}
                </v-card-title>
            </v-card>
        </v-col>

        <template v-if="type === 1">
            <v-col v-for="bi in bankItems" :key="bi.link" cols="3">
                <v-card shaped class="mx-auto" max-width="344">
                    <v-img :src="require('./img/' + bi.img)"></v-img>
                    <v-card-subtitle class="font-weight-black mb-n6">{{
                        bi.text
                    }}</v-card-subtitle>
                    <v-card-actions>
                        <v-btn
                            class="my-2"
                            outlined
                            color="deep-purple accent-4"
                        >
                            Смотреть
                            <v-icon large right color="red">mdi-youtube</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </template>
        <template v-if="type === 0">
            <v-col v-for="item in items" :key="item.items" cols="3">
                <v-card shaped class="mx-auto" max-width="344" @click="123">
                    <v-list-item>
                        <v-list-item-avatar tile size="80">
                            <v-img
                                :src="
                                    'https://portal.ahstep.ru/ahstep/services/img/marketing/inventory/' +
                                        item.img
                                "
                                height="200px"
                            ></v-img
                        ></v-list-item-avatar>
                        <v-list-item-title>{{ item.text }}</v-list-item-title>
                    </v-list-item>
                    <v-divider />
                    <v-card-actions>
                        <v-btn
                            class="my-2"
                            outlined
                            color="indigo"
                            @click="addOrder(item.id)"
                            >в заявку
                            <v-icon right color="green"
                                >mdi-plus-circle-outline</v-icon
                            >
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </template>
        <!--<v-col cols="12">
            <v-expand-transition>
                <template v-if="selectItem">
                    <v-card class="mx-auto" max-width="60%" tile>
                        <v-list-item-group color="primary">
                            <v-list-item
                                v-for="(item, i) in items"
                                :key="i"
                                :class="`listitem`"
                            >
                                <v-list-item-avatar>
                                    <v-img :src="'https://portal.ahstep.ru/ahstep/services/img/marketing/inventory/' + item.img"></v-img>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title v-text="item.text">
                                        <v-divider />
                                    </v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                    <v-col>
                                        <v-btn icon>
                                            <v-icon
                                                @click="
                                                    order = Number(order) + 1
                                                "
                                                color="green lighten-1"
                                                >mdi-plus-circle-outline</v-icon
                                            >
                                        </v-btn>
                                        <v-btn icon>
                                            <v-icon color="grey lighten-1"
                                                >mdi-information</v-icon
                                            >
                                        </v-btn>
                                    </v-col>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list-item-group>
                    </v-card>
                </template>
            </v-expand-transition>
        </v-col>-->
    </v-row>
</template>

<script>
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
export default {
    components: {
        DialogAfterSendForm,
    },
    data: () => ({
        name: "маркетинг",
        type: false,
        sections: [
            {
                id: 0,
                name: "Рекламная и сувенирная продукция",
                img: "mdi-palette-swatch-outline",
                color: "blue",
            },
            {
                id: 1,
                name: "Медиа банк",
                img: "mdi-video-vintage",
                color: "green",
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
                text:
                    'Сладкий рис длиннозерный "СТЕПЬ" с чесноком и креветками',
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
        selectItem: "",
        items: [
            {
                id: "0",
                text: "Буклет",
                img: "1.jpg",
            },
            {
                id: "1",
                text: "Металлическая табличка",
                img: "4.jpg",
            },
            {
                id: "2",
                text: "Ручка Portobello с фирменной гравировкой",
                img: "2.jpg",
            },
            {
                id: "3",
                text: "Степь указатели",
                img: "4.jpg",
            },
            {
                id: "4",
                text: "Степь флаг 1.5х0.9 белый",
                img: "5.jpg",
            },
            {
                id: "5",
                text: "Степь флаг 1.0х0.6 белый",
                img: "6.jpg",
            },
            {
                id: "6",
                text: "Степь флаг 1.0х3.0 белый",
                img: "7.jpg",
            },
            {
                id: "7",
                text: "Фирменный ежедневник ",
                img: "8.jpg",
            },
            {
                id: "8",
                text: "Значок Агрохолдинг СТЕПЬ",
                img: "9.jpg",
            },
            {
                id: "9",
                text: "Фирменный календарь ",
                img: "10.jpg",
            },
        ],
        order: "",
        ordersShow: "",
        itemsOrder: [],
        dialogParam: false,
    }),
    methods: {
        selectedItem() {
            this.selectItem = true;
        },
        addOrder(id) {
            this.items[id].value = "1";
            this.itemsOrder = this.items.filter(
                (getItem) => getItem.value != null
            );
            this.order = this.itemsOrder.length;
            this.ordersShow = true;
        },
        clickOrders() {
            this.dialogParam = true;
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
</style>
