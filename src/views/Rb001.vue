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

            <v-card-text class="subtitle-1 text-center mt-4">
                {{ warnMessage }}            
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                text
                @click="funcDialog()"
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
            <RqCardTitle :title="title" :sub_message="sub_message"></RqCardTitle>
            <hr/>
            <SelectUsr :userId_err="userId_err"></SelectUsr>                 
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Тип оборудования:
                    </v-card-text>                        
                </v-col>
                <v-col cols="6">
                    <v-select
                        v-model="type"
                        :items="items_type"
                        label="Выберите необходимое оборудование"
                        chips
                        deletable-chips
                        multiple
                        solo
                        outlined
                        dense
                        :error-messages="type_err"
                    ></v-select>
                </v-col>                   
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Комментарий:
                    </v-card-text> 
                </v-col>
                <v-col cols="6">
                    <v-textarea v-model="cmnt" outlined solo label="Для указания дополнительной информации используйте это поле"></v-textarea>
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
import { bus } from '../main.js';
import RqCardTitle from '../components/RqCardTitle';
import SelectUsr from '../components/SelectUsr';
import axios from 'axios';
    export default{
        components: {
            RqCardTitle,
            SelectUsr
        },
        data:() => ({
            title: "Заявка на приобретение техники/программного обеспечения",
            sub_message: "Заявка согласуется внутри департамента ИТ, при необходимости может быть дополнительно отправлена на согласование руководителям подразделений или компаний. Статус созданной заявки вы моежете отслеживать в разделе ",
            warnMessage: '',
            items_type: ['Стационарный компьютер', 'Ноутбук', 'Монитор', 'МФУ', 'Стационарный телефон', 'Прочее оборудование и ПО'],
            dialog: false,
            users: [],
            userId: '',
            type: '',
            cmnt: '',
            userId_err: '',
            type_err: ''
    }),
    created(){
            bus.$on('SelectUsr', data=>{
                this.userId = data;
            });
        },
    methods: {
        //Отправка формы
        formSend: function(){            
            //Проверка полей тип
            if (this.type && this.userId) {
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: './ajax/ajax_rb001.php',
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
                this.warnMessage = 'Ваша заявка успешно отправлена';
                
                }

            if (!this.type) {
                this.type_err = 'Необходимо выбрать тип оборудование'
            }
            if (!this.userId) {
                this.userId_err = 'Необходимо выбрать сотрудника'
            }
            
        },
        //Действие кнопки "назад"
        formCancl: function(){
            this.$router.go(-1);
        },
        //Действие кнопки "Мои заявки"
        btnToMyreq(){
            document.location.href = "/it-uslugi/helpdesk/my_ticket.php";
        },
        //Взаимодействие с диалогом
        funcDialog(){
            this.$router.go(-1);
        }
    },
    mounted() {
         axios
             .get('./ajax/ajax_rb001.php', {
                })
                .then(response => (this.users = response.data))        
    }
}    
</script>