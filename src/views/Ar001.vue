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
                        Дата увольнения:
                    </v-card-text> 
                </v-col>
                <v-col cols="3">
                    <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                        >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                            outlined
                            solo
                            dense
                            v-model="ar_date"
                            label="Дата"
                            append-outer-icon="mdi-calendar-outline"
                            v-on="on"
                            :error-messages="ar_date_err"
                            ></v-text-field>
                        </template>
                        <v-date-picker 
                            locale="ru"
                            v-model="ar_date" 
                            no-title 
                            @input="menu = false"                            
                            ></v-date-picker>
                        </v-menu>
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
import SelectUsr from '../components/SelectUsr.vue';
import axios from 'axios';

    export default{
        components: {
            RqCardTitle,
            SelectUsr
        },
        data:() => ({            
            title: "Отключение пользователя от информационных систем",
            sub_message: "Заявка в обязательном порядке согласуется сотрудниками департамента управления персоналом. Статус созданной заявки вы моежете отслеживать в разделе ",
            warnMessage: '',
            items_type: ['Служебный компьютер', 'Личный компьютер', 'Мобильное устройство'],
            dialog: false,
            users: [],
            userId: '',
            userId_err: '',
            ar_date: '',
            ar_date_err: '',
            menu: '',
            cmnt: ''
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
            if (this.userId && this.ar_date) {
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: './ajax/ajax_ar001.php',
                    data: {
                        userId: this.userId,
                        ardate: this.ar_date,
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
            if (!this.userId) {
                this.userId_err = 'Необходимо выбрать сотрудника'
            }
            if (!this.ar_date) {               
                this.ar_date_err = 'Не указана дата'
            }
        },
        //Действие кнопки "назад"
        formCancl: function(){
            this.$router.go(-1);
        },        
        //Взаимодействие с диалогом
        funcDialog(){
            this.$router.go(-1);
        }
    }    
}    
</script>
