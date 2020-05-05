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
            <v-row class="mb-n6">
                <v-col cols="5">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Организация:
                    </v-card-text>                
                </v-col>
                <v-col cols="6">
                    <v-autocomplete
                            :items="org"
                            v-model="org_name"
                            outlined
                            solo
                            dense
                            chips
                            deletable-chips
                            cache-items
                            autofocus
                            label="Начните набирать название организации"
                            item-text="NAME"
                            item-value="NAME"
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
                    <v-text-field v-model="hyst" outlined solo dense suffix="Га" :error-messages="hyst_err"></v-text-field>
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
                    <v-file-input type="file" v-model="file" label="Выберите файл или файлы" chips outlined solo multiple dense ref="file" prepend-icon=""></v-file-input>                  
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
            dialog: false,
            org: [],
            org_name: '',
            org_err: '',
            list: '',
            list_err: '',
            area: '',
            area_err: '',
            agro: '',
            agro_err: '',
            hyst: '',
            hyst_err: '',
            cmnt: '',
            file: []
    }),
    mounted() {
         axios
             .get('./ajax/ajax_ms001.php', {
                })
                .then(response => (this.org = response.data))        
    },
    methods: {
        //Отправка формы
        formSend(){
            if (this.org_name && this.list && this.area && this.hyst && this.agro && this.cmnt) {
                var formData = new FormData();
                    for( var i = 0; i < this.file.length; i++ ){
                        let file = this.file[i];
                        formData.append('file[' + i + ']', file);
                    }
                formData.append('org', this.org_name); 
                formData.append('list', this.list); 
                formData.append('area', this.area); 
                formData.append('agro', this.agro);
                formData.append('hyst', this.hyst); 
                formData.append('cmnt', this.cmnt);                
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'multipart/form-data'},
                    url: './ajax/ajax_ms001.php',
                    data: formData,                    
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
            if (!this.org_name) {               
                this.org_err = 'Необходимо выбрать организацию'
            }
            if (!this.list) {               
                this.list_err = 'Необходимо указать номер и дату путевого листа'
            }
            if (!this.area) {               
                this.area_err = 'Необходимо указать номер поля или участка'
            }
            if (!this.agro) {               
                this.agro_err = 'Необходимо указать выработку'
            }
            if (!this.hyst) {               
                this.hyst_err = 'Необходимо указать выработку'
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