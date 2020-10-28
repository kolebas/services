<template>
    <div class="text-center">
        <v-dialog
            :value="dialog"
            :width="widthDialog"
            :persistent="persistentDialog"
        >
            <v-card>
                <v-card-title class="headline grey lighten-2" primary-title>
                    {{ title }}
                </v-card-title>
                <v-card-text class="subtitle-1 text-center mt-4">
                    {{ warnMessage }}
                </v-card-text>
                <template v-if="dataArray">
                    <v-card background-color="red">
                        <v-list dense>
                            <v-list-item
                                v-for="item in dataArray"
                                :key="item.id"
                            >
                                <v-col cols="1"> </v-col>
                                <v-col cols="8">
                                    <v-card-text class="subtitle-1 pt-2">
                                        {{ item.text }}
                                    </v-card-text>
                                </v-col>
                                <v-col cols="2">
                                    <v-text-field
                                        outlined
                                        solo
                                        dense
                                        suffix="шт."
                                        v-model="item.value"
                                    />
                                </v-col>
                            </v-list-item>
                        </v-list>
                    </v-card>
                </template>
                <v-card-text
                    v-if="subMessage"
                    color="info"
                    class="subtitle-1 text-center mt-4"
                >
                    {{ subMessage }}
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" :disabled="btnDisable" text @click="funcDialog()">
                        {{ btnText }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { bus } from "@/main.js";
export default {
    components: {},
    props: {
        disablePersistentParam: { type: Boolean },
        dialog: { type: Boolean },
        warnMessage: { type: String },
        subMessage: { type: String },
        route: { type: String },
        widthDialogParam: { type: String },
        titleDialogParam: { type: String },
        btnTextParam: { type: String },
        btnDisable: { type: Boolean, default: false },
        dataArray: { type: Array },
    },
    data: () => ({
        title: "Статус",
        btnText: "Понятно",
        widthDialog: "500px",
        persistentDialog: true,
    }),
    methods: {
        funcDialog() {
            this.btnDisable = true 
            if (this.route == 1) {
                this.dialog = false;
                bus.$emit("chngSwitch", this.dialog);
            } else {
                this.$router.go(-1);
            }
        },
        getParam() {
            //Отключаем невозможность закрыть диалоговое окно
            if (this.disablePersistentParam) {
                this.persistentDialog = false;
            }
            //Ширина
            if (this.widthDialogParam) {
                this.widthDialog = this.widthDialogParam;
            }
            //Название окна
            if (this.titleDialogParam) {
                this.title = this.titleDialogParam;
            }
            //Название кнопки
            if (this.btnTextParam) {
                this.btnText = this.btnTextParam;
            }
        },
    },
    mounted() {
        this.getParam();
    },
};
</script>
