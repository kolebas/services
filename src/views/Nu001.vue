<template>
    <v-container>
        <DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage"/> 
        <v-row>
            <v-card
                max-width="65%"
                raised
                class="mx-auto"
                color="grey lighten-4"            
                >
                <RqCardTitle :title="title" :sub_message="sub_message"></RqCardTitle>
                <hr/>
                <v-row class='mb-n6'>
                    <v-col cols='4'>
                        <v-card-text class='subtitle-1 text-right pt-2'>
                            ФИО
                        </v-card-text>                        
                    </v-col>
                    <v-col cols='6'>
                        <v-text-field v-model='fio' outlined solo dense append-icon="mdi-account"></v-text-field>
                    </v-col>                    
                </v-row>
                <v-row class='mb-n6'>
                    <v-col cols='4'>
                        <v-card-text class='subtitle-1 text-right pt-2'>
                            Должность
                        </v-card-text>                        
                    </v-col>
                    <v-col cols='6'>
                        <v-text-field v-model='prof' outlined solo dense append-icon="mdi-briefcase-account"></v-text-field>
                    </v-col>                    
                </v-row>
                <v-row class="mb-n6">
                    <v-col cols="4">
                        <v-card-text class="subtitle-1 text-right pt-2">
                            Внешний сотрудник
                        </v-card-text>                
                    </v-col>
                    <v-col cols="4">
                        <v-switch v-model='out_user' inset class="mt-n2" label='Доступ для внешнего сотрудника'></v-switch>                
                    </v-col>
                </v-row>
                <v-row class="mb-n6" v-if="out_user">
                    <v-col cols="4">
                        <v-card-text class="subtitle-1 text-right pt-2">
                            Электронная почта (внешнего сотрудника)
                        </v-card-text>                
                    </v-col>
                    <v-col cols="4">
                        <v-text-field v-model='out_user_mail' outlined solo dense append-icon="mdi-email"></v-text-field>              
                    </v-col>
                </v-row>
                <SelectOrg  :cols_title='4' :cols_input='6'
                />
                <SelectUsr :cols_title='4' :cols_input='6' title='ФИО руководителя:'              
                />
                <v-row class='mb-n6'>
                    <v-col cols='4'>
                        <v-card-text class='subtitle-1 text-right pt-2'>
                            Контактный телефон
                        </v-card-text>                        
                    </v-col>
                    <v-col cols='3'>
                        <v-text-field v-model='tel' outlined solo dense append-icon="mdi-cellphone"></v-text-field>
                    </v-col>                    
                </v-row>
                <v-row class='mb-n6'>
                    <v-col cols='4'>
                        <v-card-text class='subtitle-1 text-right pt-2'>
                            Дата выхода
                        </v-card-text>                        
                    </v-col>
                    <v-col cols="3">
                    <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                        >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                            outlined
                            solo
                            dense
                            v-model="date_start"
                            label="Дата"
                            append-icon="mdi-calendar-outline"
                            v-on="on"
                            :error-messages="date_start_err"
                            ></v-text-field>
                        </template>
                        <v-date-picker 
                            locale="ru"
                            v-model="date_start" 
                            no-title 
                            @input="menu = false"                            
                            ></v-date-picker>
                        </v-menu>
                </v-col>                    
                </v-row>
                <v-row class='mb-n6'>
                    <v-col cols='4'>
                        <v-card-text class='subtitle-1 text-right pt-2'>
                            Дата рождения
                        </v-card-text>                        
                    </v-col>
                    <v-col cols="3">
                    <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                        >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                            outlined
                            solo
                            dense
                            v-model="date_burn"
                            label="Дата"
                            append-icon="mdi-calendar-outline"
                            v-on="on"
                            :error-messages="date_burn_err"
                            ></v-text-field>
                        </template>
                        <v-date-picker 
                            locale="ru"
                            v-model="date_burn" 
                            no-title 
                            @input="menu = false"                            
                            ></v-date-picker>
                        </v-menu>
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
import { bus } from '../main.js';
import DialogAfterSendFrom from '../components/DialogAfterSendForm.vue';
import RqCardTitle from '../components/RqCardTitle';
import SelectOrg from '../components/SelectOrg.vue';
import SelectUsr from '../components/SelectUsr.vue';
export default {
    components:{
        DialogAfterSendFrom,
        RqCardTitle,
        SelectOrg,
        SelectUsr
    },
    data:() => ({
        title:'Заявка на нового сотрудника',
        sub_message: 'Данная заявка в первую очередь согласовывается сотрудниками "Департамента управления персоналом", если выбрана опция "Внешний сотрудник", то заявка дополнительно будет отправлена на согласование директору по информационной безопасности и директору департамента ИТ также вы сможете отслеживать статус заявки в разделе',
        dialog: false,
        dialogMessage: '',
        org_name: '',
        org_name_err: '',
        out_user: '',
        out_user_mail: '',
        tel: '',
        tel_err: '',
        date_start: '',
        date_start_err: '',
        date_burn: '',
        date_burn_err: '',
        btnLoader: false     
    }),
    created(){
        bus.$on('selectOrg', data=>{
            this.org_err = '';
            this.org_name = data;
        });
    },
    methods: {
        formCancl: function(){
            this.$router.go(-1);
        },
    }
}
</script>