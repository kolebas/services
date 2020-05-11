<template>
    <v-container>
        <v-row>
            <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage"/>
            <v-card
                width='55%'
                raised
                class='mx-auto'
                color='grey lighten-4'            
                >
                <v-card-text class="pa-0">
                        <p class="text-center pt-4 headline text--primary">{{ title }}</p>
                        <p class="subtitle-1 font-weight-medium mx-8">{{ sub_title }}</p>
                </v-card-text>
                <hr/>  
                <v-row>                    
                    <v-col cols='11' class="mx-auto">
                        <label>Краткое описание:</label>
                        <v-text-field 
                            v-model='title_inc'
                            @input="title_inc_err = ''" 
                            outlined 
                            solo 
                            dense
                            :error-messages='title_inc_err' 
                            :label='title_inc_label'
                        ></v-text-field>
                        <v-switch v-model="switch_1c" @change="switch_mon = false" label='Проблема связана с 1С' inset class="mt-n2"></v-switch>
                        <v-switch v-model="switch_mon" disabled @change="switch_1c = false" label='Проблема связана с мониторингом' inset class="mt-n2"></v-switch>                        
                        <v-expand-transition>
                            <div v-if="switch_1c">                            
                                <label>Выбор направления 1С:</label>
                                <v-select
                                    :items='type_1c'
                                    v-model="type_1c_slct"                                                                    
                                    outlined
                                    solo
                                    dense                                    
                                    :error-messages='type_1c_err'
                                    :label='type_1c_label' 
                                    @change="type_1c_err = ''"
                                ></v-select>                                                       
                            </div> 
                        </v-expand-transition>                       
                        <label>Подробное описание:</label>
                        <v-textarea 
                            v-model="cmnt" 
                            outlined solo 
                            rows='7'
                            :label="cmnt_label"
                        ></v-textarea>
                        <InputFileCard />
                    </v-col>
                </v-row>
                <hr/>                
                <v-card-actions class='py-4'>
                    <div class='mx-auto'>
                        <v-btn class='mx-1' :loading="btnLoader" color='blue lighten-1 white--text' @click='formSend()'>
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
import InputFileCard from '../components/InputFileCard.vue';
import axios from 'axios';

export default {
    components: {
        InputFileCard,
        DialogAfterSendForm
    },
    data:() => ({
        title: 'Новое обращение',
        sub_title: 'Если у вас возникли проблемы с используемым оборудованием, программным обеспечением или вы нуждаетесь в консультации специалистов, заполните предлагаемую форму и нажмите кнопку "ОТПРАВИТЬ".',
        title_inc: '',
        title_inc_err: '',
        title_inc_label: 'Буквально в двух словах',
        type_1c: ['Бухгалтерский учёт',
            'Зарплата и управление персоналом','Документооборот (договоры, приказы, поручение, закупки)','Казначейство','Закупка','Весовые','Эдо, EDI, ККТ','Логистика','МСФО','1С не работает, работает медленно', 'Ошибка в НСИ'],
        type_1c_slct: '',
        type_1c_err: '',
        type_1c_label: '',
        switch_1c: '',
        switch_mon: '',
        cmnt: '',
        cmnt_label: 'Подробное описание помагает быстрее решить проблему',
        file: [],
        file_lable: 'Выберите файл или файлы',
        dialog: false,
        dialogMessage: '',
        btnLoader: false
    }),
    created(){
            bus.$on('inputFile', data=>{
                this.file = data;               
            });
        },
    methods: {
        formSend: function(){
            if((this.title_inc && !this.switch_1c) || (this.title_inc && this.switch_1c && this.type_1c_slct)){
                this.btnLoader = true;              
                var formData = new FormData();
                    for( var i = 0; i < this.file.length; i++ ){
                        let file = this.file[i];
                        formData.append('file[' + i + ']', file);
                    }
                formData.append('title_inc', this.title_inc); 
                formData.append('type_1c_slct', this.type_1c_slct);
                formData.append('switch_mon', this.switch_mon);
                formData.append('cmnt', this.cmnt);                
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'multipart/form-data'},
                    url: './ajax/ajax_inc.php',
                    data: formData,                    
                })
                .then(response => {
                    if(response.status == 200){
                        this.dialog = true
                        this.dialogMessage = 'Успешно. Номер вашего обращение: ' + response.data
                        this.btnLoader = false;
                    }                    
                })
                .catch(error => {
                    console.log(error);
                    this.dialog = true
                    this.dialogMessage = 'Произошла ошибка'
                });
            }
            if(!this.title_inc){
                this.title_inc_err = 'Ввидите краткое описание проблемы'
            }
            if(!this.type_1c_slct){
                this.type_1c_err = 'Выберите необходимое направление 1C'
            }
        },
        formCancl: function(){
            this.$router.go(-1);
        },
    }
}
</script>