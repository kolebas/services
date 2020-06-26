<template>
<v-container>
    <div class="text-center">
        <DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage"/>   
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
                :cols_title='5'
                :cols_input='6'  
                :org_err='org_err'
            />
            <InputCard 
                    v-for='item in inputs'
                    :key='item.id'
                    :id='item.id'  
                    :title='item.title' 
                    :input_err='item.err' 
                    :label='item.label'
                    :suffix='item.suffix'
                    :cols_title='item.cols_title'
                    :cols_input='item.cols_input'
                ></InputCard>
            <v-row class="mb-n6">
                <v-col cols="5">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Комментарий:
                    </v-card-text> 
                </v-col>
                <v-col cols="6">
                    <v-textarea v-model="cmnt" outlined solo :error-messages="cmnt_err" @input="cmnt_err=''" label="Для указания дополнительной информации используйте это поле"></v-textarea>
                </v-col>                   
            </v-row>
            <v-row class="mb-n6">
                <v-col cols="5">
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
import DialogAfterSendFrom from '@/components/DialogAfterSendForm.vue';
import SelectOrg from '@/components/SelectOrg.vue';
import InputFileCard from '@/components/InputFileCard.vue';
import RqCardTitle from '@/components/RqCardTitle';
import InputCard from '@/components/InputCard.vue';
import axios from 'axios';
    export default{
        components: {
            DialogAfterSendFrom,
            SelectOrg,
            InputFileCard,
            RqCardTitle,
            InputCard
        },
        data:() => ({
            sub_message: "Согласно действующей политике, данная заявка может заводиться только руководителями подразделений. Статус созданной заявки вы моежете отслеживать в разделе ",
            dialogMessage: '',
            dialog: false,
            org: [],
            org_name: '',
            org_err: '',
            inputs: [
                {id: '1', title: 'Номер и дата путевого листа:', err: '', label: 'Например: P000006235 от 20.04.2020', value: null, cols_title: '5', cols_input: '6'},
                {id: '2', title: 'Номер поля (участка):', err: '', label: 'Например', value: null, cols_title: '5', cols_input: '6'},
                {id: '3', title: 'Выработка по данным агронома:', err: '', label: '', value: null, suffix:'Га', cols_title: '5', cols_input: '2'},
                {id: '4', title: 'Выработка по данным "Истории поля:', err: '', label: '', value: null, suffix:'Га', cols_title: '5', cols_input: '2'},
            ],            
            cmnt: '',
            cmnt_err: '',
            file: [],
            btnLoader: false
    }),
    created(){
            bus.$on('inputCard', data=>{
                this.inputs[data.input_id - 1].value = data.value;
                this.inputs[data.input_id - 1].err = '';    
            });
            bus.$on('inputFile', data=>{
                this.file = data;               
            }),
            bus.$on('selectOrg', data=>{
            this.org_err = '';
            this.org_name = data;
            })
        },
    methods: {
        //Отправка формы
        formSend(){
            if (this.org_name && this.inputs[0].value && this.inputs[1].value && this.inputs[2].value && this.inputs[3].value && this.cmnt) {
                this.btnLoader = true;
                var formData = new FormData();
                    for( var i = 0; i < this.file.length; i++ ){
                        let file = this.file[i];
                        formData.append('file[' + i + ']', file);
                    }
                formData.append('org', this.org_name); 
                formData.append('list', this.inputs[0].value); 
                formData.append('area', this.inputs[1].value); 
                formData.append('agro', this.inputs[2].value);
                formData.append('hyst', this.inputs[3].value); 
                formData.append('cmnt', this.cmnt);                
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'multipart/form-data'},
                    url: './ajax/ajax_ms001.php',
                    data: formData,                    
                })                
                .then(response => {
                    if(response.status == 200){
                        this.dialog = true
                        this.dialogMessage = 'Успешно. Номер вашей заявки:' + response.data
                        this.btnLoader = false;
                    }                    
                })
                .catch(error => {
                    console.log(error);
                    this.dialog = true
                    this.dialogMessage = 'Произошла ошибка'
                });	                
                }
            if (!this.org_name) {               
                this.org_err = 'Необходимо выбрать организацию'
            }
            if (!this.inputs[0].value) {               
                this.inputs[0].err = 'Необходимо указать номер и дату путевого листа'
            }
            if (!this.inputs[1].value) {               
                this.inputs[1].err = 'Необходимо указать номер поля или участка'
            }
            if (!this.inputs[2].value) {               
                this.inputs[2].err = 'Необходимо указать выработку'
            }
            if (!this.inputs[3].value) {               
                this.inputs[3].err = 'Необходимо указать выработку'
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