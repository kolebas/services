<template>
<v-container>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage"/>
    <v-row>
        <v-card
            max-width="55%"
            raised
            class="mx-auto"
            color="grey lighten-4"            
            >
            <RqCardTitle :title="title" :sub_message="sub_message"></RqCardTitle>
            <hr/>
            <SelectUsr
                    :cols_title='4'
                    :cols_input='6'
                    title='ФИО:'
                    :userId_err="userId_err"                    
                />                  
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Тип услуги:
                    </v-card-text>                        
                </v-col>
                <v-col cols="6">
                    <v-select
                        @change="showCmpModal()"
                        v-model="type"
                        :items="items_type"
                        label="Выберите необходимую опцию"
                        solo
                        outlined
                        dense
                        :error-messages="type_err"
                    ></v-select>
                </v-col>                   
            </v-row>
            <v-expand-transition>
                <v-row class="mb-n6" v-if="type == 'Перевод личного номера на корпоративный контракт'">
                    <v-col cols="4">
                        <v-card-text class="subtitle-1 text-right pt-2">
                            Номер телефона:
                        </v-card-text> 
                    </v-col>
                    <v-col cols="6">
                        <v-text-field type="number" v-model="telNumber" outlined solo dense :error-messages="telNumberErr" label="Введите номер который необходимо перевести"></v-text-field>
                    </v-col>                   
                </v-row>
            </v-expand-transition>            
            <hr/>
            <v-card-actions class="py-4">
                <div class="mx-auto">
                    <v-btn class="mx-1" :loading="btnLoader" color="green lighten-2 white--text" @click="formSend()">
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
import DialogAfterSendForm from '../components/DialogAfterSendForm';
import RqCardTitle from '../components/RqCardTitle';
import SelectUsr from '../components/SelectUsr';
import axios from 'axios';
    export default{
        components: {
            DialogAfterSendForm,
            RqCardTitle,
            SelectUsr
        },
        data:() => ({
            title: "Предоставление служебной сотовой связи",
            sub_message: "Согласно действующей политике, данная заявка может заводиться только руководителями подразделений. Статус созданной заявки вы моежете отслеживать в разделе ",
            dialogMessage: '',
            items_type: ['Предоставить новый номер', 'Перевод личного номера на корпоративный контракт'],
            dialog: false,
            users: [],
            userId: '',
            type: '',
            telNumber: '',
            telNumberErr: '',
            userId_err: '',
            type_err: '',
            btnLoader: false
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
                this.btnLoader = true
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
                .then(response => {
                    if(response.status == 200){
                        this.dialog = true
                        this.dialogMessage = 'Успешно. Номер вашего обращение: ' + response.data                   
                        console.log(response.data)
                        this.btnLoader = false
                    }                    
                })
                .catch(error => {
                    console.log(error)
                    this.dialog = true
                    this.dialogMessage = 'Произошла ошибка'
                });
                
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
        //Действие кнопки "Мои заявки"
        btnToMyreq(){
            document.location.href = "/it-uslugi/helpdesk/my_ticket.php";
        },
        //Взаимодействие с диалогом
        funcDialog(){
            this.$router.go(-1);
        }
    }
}    
</script>