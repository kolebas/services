<template>
<v-container>
    <div class="text-center">
        <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage"/>        
    </div>
    <v-row>
        <v-card
            max-width="65%"
            raised
            class="mx-auto"
            color="grey lighten-4"            
            >
            <RqCardTitle :title="$router.currentRoute.name" :sub_message="sub_message"></RqCardTitle>
            <hr/>
            <SelectOrg
                :cols_title='4'
                :cols_input='6'  
                :org_err='org_err'
            />                   
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2" >
                        Номер техники:
                    </v-card-text>                        
                </v-col>
                <v-col cols="6">
                    <v-text-field v-model="nmbr" outlined solo dense @input="nmbr_err = ''" :error-messages="nmbr_err" hint="Номер регистрационного знака техники с несиправным оборудованием" label="Например: 54-32 УА 161"></v-text-field>
                </v-col>                  
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Описание неисправности:
                    </v-card-text> 
                </v-col>
                <v-col cols="6">
                    <v-textarea v-model="cmnt" outlined solo @input="cmnt_err = ''" :error-messages="cmnt_err" label="Для указания дополнительной информации используйте это поле"></v-textarea>
                </v-col>                   
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Файл:
                    </v-card-text>
                </v-col>
                <v-col cols="6" class="mb-2">
                    <InputFileCard />
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
import RqCardTitle from '@/components/RqCardTitle';
import SelectOrg from '@/components/SelectOrg';
import InputFileCard from '@/components/InputFileCard';
import axios from 'axios';
    export default{
        components: {
            DialogAfterSendForm,
            RqCardTitle,
            SelectOrg,
            InputFileCard
        },
        data:() => ({
            sub_message: "Согласно действующей политике, данная заявка может заводиться только руководителями подразделений. Статус созданной заявки вы можете отслеживать в разделе ",
            dialog: false,
            dialogMessage: '',
            org_name: '',
            org_err: '',
            nmbr: '',
            nmbr_err: '',
            cmnt: '',
            cmnt_err: '',
            file: [],
            btnLoader: false
    }),
    created(){
            bus.$on('selectOrg', data=>{
                this.org_name = data.name;
                this.org_err = '';
            });
            bus.$on('inputFile', data=>{
                this.file = data;               
            });
        },
    methods: {
        //Отправка формы
        formSend: function(){            
            //Проверка полей тип
            if (this.org_name && this.nmbr && this.cmnt) {
                this.btnLoader = true
                this.btnLoader = true;
                var formData = new FormData();
                    for( var i = 0; i < this.file.length; i++ ){
                        let file = this.file[i];
                        formData.append('file[' + i + ']', file);
                    }
                formData.append('org', this.org_name); 
                formData.append('nmbr', this.nmbr); 
                formData.append('cmnt', this.cmnt);               
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'multipart/form-data'},
                    url: './ajax/ajax_ms003.php',
                    data: formData,                    
                })
                .then(response => {
                    if(response.status == 200){
                        this.dialog = true
                        this.dialogMessage = 'Успешно. Номер вашей заявки: ' + response.data
                        this.btnLoader = false;
                    }                    
                })
                .catch(error => {
                    console.log(error);
                    this.dialog = true
                    this.dialogMessage = 'Произошла ошибка'
                    this.btnLoader = false
                });                
                }
            if (!this.org_name) {
                this.org_err = 'Необходимо выбрать организацию'
            }
            if (!this.nmbr) {               
                this.nmbr_err = 'Необходимо указать номер техники'
            }
            if (!this.cmnt) {
                this.cmnt_err = 'Необходимо указать комментарий'
            }
        },
        //Действие кнопки "назад"
        formCancl: function(){
            this.$router.go(-1);
        }
    }
}    
</script>