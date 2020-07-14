<template>
<v-container>
    <div class="text-center">
        <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage"/>
        <v-dialog
        v-model="softModal"
        width="500"
        persistent
        >
        <v-card>
            <v-card-title
            class="headline red lighten-1"
            primary-title
            color="red"
            >
            Внимание!!!
            </v-card-title>

            <v-card-text class="subtitle-1 text-center mt-4" >
                {{ softModalMsg }}            
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                text
                @click="closeSoftModal()"
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
            <SelectUsr
                    :cols_title='4'
                    :cols_input='6'
                    title='ФИО:'
                    :userId_err="userId_err"                    
                />                  
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Наименование ПО:
                    </v-card-text>                        
                </v-col>
                <v-col cols="6">
                    <v-select
                        @change="showSoftModal()"
                        v-model="soft"
                        :items="items_type"
                        label="Выберите необходимое ПО"
                        solo
                        outlined
                        dense
                        :error-messages="type_err"
                    >
                    <template v-slot:selection="{ item }">
                                <v-chip
                                    color="#bcedfc"
                                    close
                                    label
                                    @click:close="soft=''"
                                >{{ item }}
                                </v-chip>
                            </template>
                    </v-select>
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
import { bus } from '@/main.js';
import DialogAfterSendForm from '@/components/DialogAfterSendForm.vue';
import SelectUsr from '@/components/SelectUsr';
import RqCardTitle from '@/components/RqCardTitle';
import axios from 'axios';
    export default{
        components: {
            DialogAfterSendForm,
            RqCardTitle,
            SelectUsr
        },
        data:() => ({
            title: "Установка программного обеспечения",
            sub_message: "В рамках исполнения данной заявки вам будет предоставлен доступ к программному обеспечения из предложенного списка (Для закупки программного обеспечения вам необходимо воспользоваться заявкой за закупку). Статус созданной заявки вы можете отслеживать в разделе ",
            dialogMessage: '',
            items_type: ['Консультант +'],
            dialog: false,
            users: [],
            userId: '',
            soft: '',
            softModal: '',
            softModalMsg: 'В связи с ограничением лицензионного соглашения, данное программное обеспечение предоставляется только сотрудникам офиса в г. Ростове-на-Дону',
            cmnt: '',
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
        //Показ предупреждения о Консультанте
        showSoftModal: function(){
            if(this.soft == 'Консультант +'){
                this.softModal = true;
            }            
        },
        //Закрыть предупреждение
        closeSoftModal: function(){
            this.softModal = false;
        },
        //Отправка формы
        formSend: function(){            
            //Проверка полей тип
            if (this.userId && this.soft) {
                this.btnLoader = true
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: './ajax/ajax_sf001.php',
                    data: {
                        userId: this.userId,
                        soft: this.soft,
                        cmnt: this.cmnt
                    }
                })
                .then(response => {
                    if(response.status == 200){
                        this.dialog = true
                        this.dialogMessage = 'Успешно. Номер вашей заявки: ' + response.data
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
            if (!this.soft) {
                this.type_err = 'Необходимо выбрать программное обеспечение'
            }
        },
        //Действие кнопки "назад"
        formCancl: function(){
            this.$router.go(-1);
        }
    },
    mounted() {        
         axios
             .get('./ajax/ajax_user.php', {
                })
                .then(response => (this.users = response.data))        
    }
}    
</script>