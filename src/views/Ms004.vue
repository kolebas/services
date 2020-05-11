<template>
    <v-container>
        <v-row>
            <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage"/>
            <v-card
                width='65%'
                raised
                class='mx-auto'
                color='grey lighten-4'            
                >
                <RqCardTitle 
                    :title='title' 
                    :sub_message='sub_message'
                ></RqCardTitle>
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
                    :cols_title='item.cols_title'
                    :cols_input='item.cols_input'
                ></InputCard>
                <hr/>                
                <v-card-actions class='py-4'>
                    <div class='mx-auto'>
                        <v-btn class='mx-1' :loading="btnLoader" color='green lighten-2 white--text' @click='formSend()'>
                            Отправить
                        </v-btn>
                        <v-btn class='mx-1' @click='formCancl()'>
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
import DialogAfterSendForm from '../components/DialogAfterSendForm.vue';
import RqCardTitle from '../components/RqCardTitle.vue';
import SelectOrg from '../components/SelectOrg.vue';
import InputCard from '../components/InputCard.vue';
import axios from 'axios';
export default {
    components: {
        DialogAfterSendForm,
        RqCardTitle,
        SelectOrg,
        InputCard
    },

    data: () => ({
        title: 'Добавление основного средства в папку «Техника для выработки»',
        sub_message: 'ms004',
        id: '',
        inputs: [
            {id: '1', title: 'Наименование ОС:', err: '', label: '', value: null, cols_title: '5', cols_input: '6'},
            {id: '2', title: 'Государственный регистрационный номер или инвентарный номер:', err: '', label: '', value: null, cols_title: '5', cols_input: '6'},
            {id: '3', title: 'Код ОС 1С:', err: '', label: '', value: null, cols_title: '5', cols_input: '6'}
        ],
        org_name: '',
        org_err: '',
        arr: [],
        btnLoader: false,
        dialog: false,
        dialogMessage: ''
    }),
    created(){
        bus.$on('selectOrg', data=>{
            this.org_err = '';
            this.org_name = data;
        });
        bus.$on('inputCard', data=>{
            this.inputs[data.input_id - 1].value = data.value;
            this.inputs[data.input_id - 1].err = '';     
            });
    },
    methods: {
        formSend: function(){
            if(this.org_name && this.inputs[0].value && this.inputs[1].value && this.inputs[2].value){
                this.arr.push(this.org_name);
                for (let i = 0; i < this.inputs.length; i++){
                    this.arr.push(this.inputs[i].value);
                }
                this.btnLoader = true;              
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: './ajax/ajax_ms004.php',
                    data: {
                        arr: this.arr
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
            if(!this.org_name){
                this.org_err = 'Не выбрана организация';                
            }
            if(!this.inputs[0].value){
                this.inputs[0].err = 'Не указано наименование ОС'                
            }
            if(!this.inputs[1].value){
                this.inputs[1].err = 'Не указан государственный регистрационный номер или инвентарный номер'                
            }
            if(!this.inputs[2].value){
                this.inputs[2].err = 'Не указан код ОС 1С'                
            }       
        },
        //Действие кнопки "назад"
        formCancl: function(){
            this.$router.go(-1);
        }       
    }       
}
</script>