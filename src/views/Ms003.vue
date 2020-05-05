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
            max-width="65%"
            raised
            class="mx-auto"
            color="grey lighten-4"            
            >
            <RqCardTitle :title="title" :sub_message="sub_message"></RqCardTitle>
            <hr/>
            <SelectUsr :userId_err="userId_err"></SelectUsr>                  
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2" >
                        Номер техники:
                    </v-card-text>                        
                </v-col>
                <v-col cols="6">
                    <v-text-field v-model="agro" outlined solo dense :error-messages="agro_err" hint="Номер регистрационного знака техники с несиправным оборудованием" label="Например: 54-32 УА 161"></v-text-field>
                </v-col>                  
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Описание неисправности:
                    </v-card-text> 
                </v-col>
                <v-col cols="6">
                    <v-textarea v-model="cmnt" outlined solo label="Для указания дополнительной информации используйте это поле"></v-textarea>
                </v-col>                   
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Файл:
                    </v-card-text>
                </v-col>
                <v-col cols="6">
                    <v-file-input v-model="file" chips outlined solo dense label="Выберите файлы"></v-file-input>
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
            title: "Заявка о неисправности телематического оборудования",
            sub_message: "Согласно действующей политике, данная заявка может заводиться только руководителями подразделений. Статус созданной заявки вы моежете отслеживать в разделе ",
            warnMessage: '',
            dialog: false,
            users: [],
            userId: '',
            userId_err: '',
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
            if (this.userId && this.type && this.type != 'Перевод личного номера на корпоративный контракт'  || this.userId && this.type && this.telNumber ) {
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: './ajax/ajax_ph001.php',
                    data: {
                        userId: this.userId,
                        type: this.type,
                        telnumber: this.telNumber
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
            if (!this.type) {               
                this.type_err = 'Необходимо выбрать тип устройства'
            }
            if (this.type == 'Перевод личного номера на корпоративный контракт'){
                if(!this.telNumber){
                    this.telNumberErr = 'Необходимо указать телефонный номер'
                }
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