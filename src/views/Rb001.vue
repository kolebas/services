<template>
<v-container>
    <div class="text-center">
        <v-dialog
        v-model="dialog"
        width="500"
        persistent
        >
        <v-card>
            <v-card-title
            class="headline grey lighten-2"
            primary-title
            >
            Статус
            </v-card-title>

            <v-card-text>
                {{ warnMessage }}            
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                text
                @click="dialog = false"
            >
                Понятно
            </v-btn>
            </v-card-actions>
        </v-card>
        </v-dialog>
    </div>
    <v-row>
        <v-card
            max-width="55%"
            raised
            class="mx-auto"
            color="grey lighten-4"            
            >
            <v-card-text class="pa-0">
                <p class="text-center pt-4 headline text--primary">{{ title }}</p>
                <p class="text-center">{{ sub_message }} <v-btn @click="btnToMyreq()" color="green lighten-2 white--text" small><v-icon left dark>mdi-format-list-bulleted</v-icon>Мои заявки</v-btn></p>
                <hr/>
            </v-card-text>
            <UsrSelect/>               
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Тип оборудования:
                    </v-card-text>                        
                </v-col>
                <v-col cols="6">
                    <v-combobox
                        v-model="type"
                        :items="items_type"
                        label="Выберите необходимое оборудование"
                        chips
                        multiple
                        solo
                        outlined
                        dense
                        :error-messages="type_err"
                    ></v-combobox>
                </v-col>                   
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Комментарий:
                    </v-card-text> 
                </v-col>
                <v-col cols="6">
                    <v-textarea v-model="cmnt" outlined solo label="Для указания дополнительноый информации используйте это поле"></v-textarea>
                </v-col>                   
            </v-row>
            <hr/>
            <v-card-actions class="py-4">
                <div class="mx-auto">
                    <v-btn class="mx-1" color="green lighten-2 white--text" @click="formSend()">
                        Отправить
                    </v-btn>
                    <v-btn class="mx-1" @click="formCancl()">
                        Отмена
                    </v-btn>
                </div>
            </v-card-actions>                    
        </v-card>
    </v-row>    
</v-container>
</template>

<script>
import UsrSelect from '../components/UsrSelect';
import axios from 'axios';
    export default{
        components: {
            UsrSelect
        },
        data:() => ({
            title: "Заявка на закупку (DEV)",
            sub_message: "Заявка согласуется внутри департамента ИТ, при необходимости может быть дополнительно отправлена на согласование руководителям подразделений или компаний. Статус созданной заявки вы моежете отслеживать в разделе ",
            warnMessage: "",
            items_type: ['Стационарный компьютер', 'Ноутбук', 'Монитор', 'МФУ', 'Стационарный телефон', 'Прочее оборудование и ПО'],
            dialog: false,
            userId: "",
            type: "",
            cmnt: "",
            type_err: ""
    }),
    methods: {
        function () {
            console.log('test');            
        },
        //Отправка формы
        formSend: function(){            
            //Проверка полей тип
            if (this.type && this.cmnt) {
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: './ajax/ajax.php',
                    data: {
                        userId: this.userId,
                        type: this.type,
                        cmnt: this.cmnt
                    }
                })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });		
                this.dialog = true;
                this.loading = false;
                this.warnMessage = "Ваша заявка успешно отправлена"
            }

            if (!this.type) {
                this.type_err = "Требуется выбрать тип устройства"
            }
            if (!this.cmnt) {
                console.log('Error');
            }
            
        },
        formCancl: function(){
            this.$router.go(-1);
        },
        btnToMyreq(){
            document.location.href = "/it-uslugi/helpdesk/my_ticket.php";
        },
    }
    }    
</script>