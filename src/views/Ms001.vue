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
            <v-row class="mb-n6">
        <v-col cols="5">
            <v-card-text class="subtitle-1 text-right pt-2">
                Организация:
            </v-card-text>                
        </v-col>
        <v-col cols="6">
            <v-autocomplete
                    :items="org"
                    @change="selectUsr()"
                    v-model="userId"
                    outlined
                    solo
                    dense
                    chips
                    deletable-chips
                    cache-items
                    autofocus
                    label="Начните набирать фамилию или имя сотрудника"
                    item-text="NAME"
                    item-value="ID"
                    :error-messages="org_err"                      
                    >
                    </v-autocomplete>
        </v-col>
    </v-row>                  
            <v-row class="mb-n6">
                <v-col cols="5">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Номер и дата путевого листа:
                    </v-card-text>                        
                </v-col>
                <v-col cols="6">
                    <v-text-field v-model="list" outlined solo dense :error-messages="list_err" label="Например: P000006235 от 20.04.2020"></v-text-field>
                </v-col>                    
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="5">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Номер поля (участка):
                    </v-card-text> 
                </v-col>
                <v-col cols="6">
                    <v-text-field v-model="area" outlined solo dense :error-messages="area_err" label="Например:"></v-text-field>
                </v-col>                   
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="5">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Выработка по данным агронома:
                    </v-card-text>
                </v-col>
                <v-col cols="2">
                    <v-text-field v-model="agro" outlined solo dense suffix="Га" :error-messages="agro_err"></v-text-field>
                </v-col>
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="5">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Выработка по данным "Истории поля":
                    </v-card-text>
                </v-col>
                <v-col cols="2">
                    <v-text-field v-model="agro" outlined solo dense suffix="Га" :error-messages="agro_err"></v-text-field>
                </v-col>
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="5">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Комментарий:
                    </v-card-text> 
                </v-col>
                <v-col cols="6">
                    <v-textarea v-model="cmnt" outlined solo label="Для указания дополнительной информации используйте это поле"></v-textarea>
                </v-col>                   
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="5">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Файл:
                    </v-card-text>
                </v-col>
                <v-col cols="6">                    
                    <input type="file" id="file" ref="file" @change="onFileUpload()" />                    
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
import axios from 'axios';
    export default{
        components: {
            RqCardTitle
        },
        data:() => ({
            title: "Корректировка данных в путевых листах 1С",
            sub_message: "Согласно действующей политике, данная заявка может заводиться только руководителями подразделений. Статус созданной заявки вы моежете отслеживать в разделе ",
            warnMessage: '',
            items_type: ['Предоставить новый номер', 'Перевод личного номера на корпоративный контракт'],
            dialog: false,
            userId: '',
            org: [],
            org_err: '',
            list: '',
            list_err: '',
            area: '',
            area_err: '',
            agro: '',
            agro_err: '',
            hyst: '',
            hyst_err: '',
            cmnt: '123456',
            file: ''
    }),
    created(){
            bus.$on('SelectUsr', data=>{
                this.userId = data;
            });
        },
    methods: {
        //Отправка формы
        formSend(){            
            //Проверка полей тип
            if (this.cmnt ) {
                var formData = new FormData();
                formData.append('file', this.file);
                formData.append("cmnt", this.cmnt);
                console.log(formData);                
                axios({
                    method: 'post',
                    formData,
                    headers: { 'Content-Type': 'multipart/form-data'},
                    url: 'https://portal.ahstep.ru/ahstep/services/ajax/ajax_ms001.php',
                    auth:{
                        username: "admin",
                        password: "Htdjk.wbz17"
                    },
                    data: formData,
                })
                /*axios.post('https://portal.ahstep.ru/ahstep/services/ajax/ajax_ms001.php',
                    formData,
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    auth:{
                        username: "admin",
                        password: "Htdjk.wbz17"
                    }
                }
                ).then(function(data){
                console.log(data.data);
                })
                .catch(function(){
                console.log('FAILURE!!');
                });*/
                .then(function (response) {
                    console.log(formData);
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });		
                this.dialog = false;
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
        onFileUpload: function(){
           this.file = this.$refs.file.files[0];
            console.log(this.file);
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