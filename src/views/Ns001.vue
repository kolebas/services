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
        <v-dialog
        v-model="cmpNameModal"
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
                {{ cmpNameModalMsg }}            
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                text
                @click="closeCmpModal()"
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
            <v-card-text class="pa-0">
                <p class="text-center pt-4 headline text--primary">{{ title }}</p>
                <p class="subtitle-1 font-weight-medium mx-8">{{ sub_message }} <v-btn @click="btnToMyreq()" color="green lighten-2 white--text" small><v-icon left dark>mdi-format-list-bulleted</v-icon>Мои заявки</v-btn></p>
                <hr/>
            </v-card-text>
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        ФИО сотрудника:
                    </v-card-text>                
                </v-col>
                <v-col cols="6">
                    <v-autocomplete
                            :items="users"
                            v-model="userId"
                            outlined
                            solo
                            dense
                            chips
                            deletable-chips
                            label="Начните набирать фамилию или имя сотрудника"
                            :item-text="users => users.LAST_NAME + ' ' + users.NAME"
                            :item-value="users => users.ID"
                            :error-messages="userId_err"                      
                            > 
                            </v-autocomplete>
                </v-col>
            </v-row>                  
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Выберите тип устройства:
                    </v-card-text>                        
                </v-col>
                <v-col cols="6">
                    <v-combobox
                        @change="showCmpModal()"
                        v-model="type"
                        :items="items_type"
                        label="Выберите тип устройства"
                        chips
                        deletable-chips
                        solo
                        outlined
                        dense
                        :error-messages="type_err"
                    ></v-combobox>
                </v-col>                   
            </v-row>
            <v-row class="mb-n6" v-if="type == 'Служебный компьютер'">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Имя компьютера:
                    </v-card-text> 
                </v-col>
                <v-col cols="6">
                    <v-text-field v-model="cmpName" outlined solo dense :error-messages="cmpNameErr" label="Укажите имя служебного компьютера"></v-text-field>
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
//import Buttons from '../components/Buttons';
import axios from 'axios';
    export default{
        components: {
            //Buttons
        },
        data:() => ({
            title: "Удаленный доступ (VPN)",
            sub_message: "В рамках исполнения данной заявки вам будет предоставлен доступ к программному обеспечения из предложенного списка. Статус созданной заявки вы моежете отслеживать в разделе ",
            warnMessage: '',
            items_type: ['Служебный компьютер', 'Личный компьютер', 'Мобильное устройство'],
            dialog: false,
            users: [],
            userId: '',
            type: '',
            cmpNameModal: '',
            cmpNameModalMsg: 'Для исполнения данной заявки: служебный компьютер должен находится в корпоративной сети, и после её исполнения необходимо выполнить перезагрузку',
            cmpName: '',
            cmpNameErr: '',
            userId_err: '',
            type_err: ''
    }),
    methods: {
        //Показ предупреждения о Консультанте
        showCmpModal: function(){
            if(this.type == 'Служебный компьютер'){
                this.cmpNameModal = true;
            }            
        },
        closeCmpModal: function(){
            this.cmpNameModal = false;        },
        //Отправка формы
        formSend: function(){            
            //Проверка полей тип
            if (this.userId && this.type && this.type != 'Служебный компьютер'  || this.userId && this.type && this.cmpName ) {
                axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: './ajax/ajax_ns001.php',
                    data: {
                        userId: this.userId,
                        type: this.type,
                        cmpname: this.cmpName
                    }
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
            if (!this.userId) {
                this.userId_err = 'Необходимо выбрать сотрудника'
            }
            if (!this.type) {               
                this.type_err = 'Необходимо выбрать тип устройства'
            }
            if (this.type == 'Служебный компьютер'){
                if(!this.cmpName){
                    this.cmpNameErr = 'Необходимо указать имя компьютера'
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
    },
    mounted() {
         axios
             .get('./ajax/ajax_user.php', {
                })
                .then(response => (this.users = response.data))        
    }
}    
</script>