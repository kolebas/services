<template>
    <v-container>
        <DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage" />
        <v-row>
            <v-col cols="11">
                <v-card
                    max-width="75%"
                    raised
                    class="mx-auto"
                    color="grey lighten-4"
                    v-if="card"
                >
                    <v-card-text class="pa-0">
                        <p class="text-center pt-4 headline text--primary">
                            {{ title }}
                        </p>
                    </v-card-text>
                    <v-row>
                        <v-col cols="4">
                            <v-text-field
                                class="pl-6"
                                v-model="name"
                                dense
                                label="Введите свое ФИО"
                                :error-messages="name_err"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="4"> </v-col>
                        <v-col cols="4">
                            <v-card
                                outlined
                                class="mr-4"
                                color="grey lighten-4"
                            >
                                <v-list-item>
                                    <v-list-item-content>
                                        <v-list-item-subtitle
                                            class="text-right mr-4"
                                            >Прогресс выполнения
                                            теста</v-list-item-subtitle
                                        >
                                    </v-list-item-content>
                                    <v-progress-circular
                                        :rotate="360"
                                        :size="100"
                                        :width="15"
                                        :value="progValue"
                                        color="green"
                                    >
                                        {{ progValue }} %
                                    </v-progress-circular>
                                </v-list-item>
                            </v-card>
                        </v-col>
                    </v-row>
                    <v-row v-for="item in quest" :key="item.id" class="mb-n6">
                        <v-col cols="6">
                            <v-card-text class="subtitle-1 pt-2">
                                <b>Вопрос №{{ item.id + 1 }}:</b>
                                {{ item.text }}:
                            </v-card-text>
                        </v-col>
                        <v-col cols="6">
                            <v-textarea
                                height="90"
                                class="mr-4"
                                outlined
                                solo
                                dense
                                v-model="item.value"
                                @change="progSts()"
                                @input="item.err = ''"
                                label="Введите свой ответ"
                                :error-messages="item.err"
                            >
                            </v-textarea>
                        </v-col>
                    </v-row>
                    <v-card-actions class="py-4">
                        <div class="mx-auto">
                            <v-btn
                                class="mx-1"
                                color="green lighten-2 white--text"
                                @click="formSend()"
                            >
                                Отправить
                            </v-btn>
                            <v-btn class="mx-1" @click="formCancl()">
                                Отмена
                            </v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="1" v-if="table">
                <v-btn @click="(card = false), (table = true)">
                    Проверка тестов
                </v-btn>
            </v-col>
            <v-col cols="12" v-if="table">
                <v-card>
                    <v-card-title>
                        {{ table_name }}
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Поиск"
                            single-line
                            hide-details
                        ></v-text-field>
                        <v-btn
                            @click="
                                (dialog = true),
                                    (dialog_title = 'Добавление позиции')
                            "
                            color="primary"
                            dark
                            class="mb-2 ml-2"
                            >Добавить
                        </v-btn>
                    </v-card-title>
                    <v-data-table
                        :headers="headers_items"
                        :items="item"
                        :search="search"
                        calculate-widths
                    >
                        <template v-slot:item.RESULT="{ item }">
                            <v-chip class="mr-2" @click="editItem(item.ID)">
                                Не проверено
                            </v-chip>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from "axios";
import DialogAfterSendFrom from "@/components/DialogAfterSendForm.vue";
export default {
    components: {
        DialogAfterSendFrom,
    },
    data: () => ({
        title: "Тестовые вопросы к инструкциям по уборке нишевых культур",
        name: "",
        quest: [
            {
                id: 0,
                text:
                    "Каким показателем руководствуются специалисты при определении вре-мени начала и очерёдности уборки нишевых культур",
                value: "",
                name: "",
                err: "",
            },
            {
                id: 1,
                text:
                    "Какое должностное лицо и на каком основании принимает решение о кон-кретной дате начала проведения уборочных работ",
                value: "",
                err: "",
            },
            {
                id: 2,
                text:
                    "Какие должностные лица несут ответственность за правильную регулиров-ку уборочной техники при уборке нишевых культур",
                value: "",
                err: "",
            },
            {
                id: 3,
                text: "Что такое партия сельскохозяйственной продукции",
                value: "",
                err: "",
            },
            {
                id: 4,
                text:
                    "Какое должностное лицо отбирает образцы из поступающего на ток вороха нишевых культур и обеспечивает их транспортировку в лабораторию",
                value: "",
                err: "",
            },
            {
                id: 5,
                text:
                    "Какие мероприятия необходимо предусмотреть для исключения перевалку продукции нишевых культур в склады и обеспечения её прямого подвоза на очистку",
                value: "",
                err: "",
            },
            {
                id: 6,
                text:
                    "Какие мероприятия необходимо предусмотреть для организации процесса транспортировки продукции нишевых культур на значительные расстоя-ния, для долгосрочного хранения большегрузным автотранспортом, непо-средственно из бункера ЗАВа, исключив предварительное накопление пар-тии",
                value: "",
                err: "",
            },
            {
                id: 7,
                text:
                    "Кем определяются качественные показатели партий сельскохозяйственной продукции, выращиваемой в АО Агрохолдинг «СТЕПЬ»?",
                value: "",
                err: "",
            },
            {
                id: 8,
                text:
                    "Какие действия необходимо предпринять если после предварительной очистки и мероприятий по доработке нишевых культур влажность вороха оказывается выше установленных параметров",
                value: "",
                err: "",
            },
            {
                id: 9,
                text:
                    "Персональную ответственность за качественные показатели сельскохозяй-ственной продукции несёт исполнительный директор ДО",
                value: "",
                err: "",
            },
        ],
        table_name: "Результаты тестов",
        headers_items: [
            { text: "Дата", value: "DATE" },
            { text: "ФИО", value: "CREATED_BY" },
            { text: "Результат", value: "RESULT" },
        ],
        item: [],
        search: "",
        dialog: false,
        name_err: "",
        dialogMessage: "",
        progValue: "0",
        card: true,
        table: false,
    }),
    methods: {
        formCancl: function() {
            this.$router.go(-1);
        },
        progSts() {
            let y = 0;
            for (let i = 0; i < this.quest.length; i++) {
                if (this.quest[i].value) {
                    y = y + 10;
                }
            }
            this.progValue = y;
        },
        formSend() {
            this.quest[0].name = this.name;
            this.btnLoader = true;

            for (let i = 0; i < this.quest.length; i++) {
                if (!this.quest[i].value) {
                    this.quest[i].err = "Необходимо заполнить это поле";
                }
            }
            if (this.name) {
                axios({
                    method: "post",
                    withCredentials: true,
                    headers: { "Content-Type": "multipart/form-data" },
                    url: "./ajax/hr/tests/ajax_test.php",
                    data: this.quest,
                })
                    .then((response) => {
                        if (response.status == 200) {
                            this.dialog = true;
                            this.dialogMessage =
                                "Ваш тест отправлен на проверку";
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
            if (!this.name) {
                this.name_err = "Вы забыли полнить свое ФИО";
            }
        },
    },
    mounted() {
        axios
            .get("./ajax/hr/tests/ajax_test.php", {
                headers: { "Content-Type": "application/json; charset=utf-8" },
                params: {
                    Login: "VqFLDv9gY",
                    Token: "VuivdzcaGvFtOACElaNdO?@-BKFMXy",
                },
            })
            .then((response) => (this.item = response.data));
    },
};
</script>
