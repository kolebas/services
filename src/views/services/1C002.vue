<template>
    <v-container>
        <DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage"/> 
        <v-row>
            <v-card
                width="65%"
                raised
                class="mx-auto"
                color="grey lighten-4"            
                >
                <RqCardTitle :title="title" :sub_message="sub_message"></RqCardTitle>
                <hr/>
                <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Описание требуемой доработки:
                    </v-card-text> 
                </v-col>
                <v-col cols="6">
                    <v-textarea 
                        v-model="cmnt" 
                        outlined 
                        solo
                        rows="10" 
                        :error-messages="cmnt_err" 
                        @input="cmnt_err=''" 
                        label="Для указания дополнительной информации используйте это поле"
                        ></v-textarea>
                </v-col>                   
            </v-row>
            <Select 
                    title='Базы данных:'
                    icon='mdi-database'
                    :cols_title='4'
                    :cols_input='6'
                    source='./ajax/ajax_1c002.php'
                    :error-messages='dep_err'
                    />
            <v-row class="mb-n4">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Файл:
                    </v-card-text> 
                </v-col>
                <v-col cols="6">
                    <inputFileCard />
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
import RqCardTitle from '@/components/RqCardTitle.vue';
import DialogAfterSendFrom from '@/components/DialogAfterSendForm.vue';
import Select from '@/components/Select.vue';
import inputFileCard from '@/components/InputFileCard.vue';
import axios from 'axios';
export default {
    components:{
        RqCardTitle,
        DialogAfterSendFrom,
        Select,
        inputFileCard
    },
    data:()=>({
        title: 'Заявка на доработку 1С',
        sub_message: 'Опишите необходимые доработки, которые в данные момент не реализованы.',
        btnLoader: false,
        cmnt: '',
        cmnt_err: '',
        db: '',
        file: []
    }),
    created(){
        bus.$on('Select', data=>{
            this.db = data;
        })
        bus.$on('inputFile', data=>{
                this.file = data;               
            })        
    },
    methods: {
        formCancl: function(){
            this.$router.go(-1);
        },
        formSend(){
            this.btnLoader = true;
            if(this.cmnt){
                var formData = new FormData();
                    for( var i = 0; i < this.file.length; i++ ){
                        let file = this.file[i];
                        formData.append('file[' + i + ']', file);
                    }
                    formData.append('cmnt', this.cmnt)
                    formData.append('db', this.db)
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'multipart/form-data'},
                    url: './ajax/ajax_1c002.php',
                    data: formData, 
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
                    this.btnLoader = false
                });
            }
            if(!this.cmnt){
                this.cmnt_err = 'Опишите требуемую доработку'
            }
        }
    }
}
</script>