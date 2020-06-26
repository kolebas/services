<template>
    <v-container>        
        <DialogAfterSendFrom :dialog="dialog" :warnMessage="dialogMessage"/>
        <v-row>
            <v-progress-circular
                class="mx-auto"
                :rotate="360"
                :size="80"
                :width="10"
                :value="value"
                color="primary"
                :indeterminate="indeterminate"
                >
                <b>{{ value }}</b>
            </v-progress-circular>
            <v-col cols="12">
                <v-tabs>
                <v-tab 
                    v-for="tab in tabs" 
                    :key="tab.text"
                    >                        
                        <v-badge
                            color="green"
                            :value="b_value"
                            :content="new_count"
                            bordered
                            >
                                {{tab.text}}
                        </v-badge>
                    </v-tab>
                    <v-tab-item>
                        <v-card>
                            <v-card-title>
                                    {{table_name}}
                                    <v-divider
                                        class="mx-4"
                                        inset
                                        vertical
                                        ></v-divider>
                                        <v-spacer></v-spacer>
                                        <v-text-field
                                            v-model="search"
                                            append-icon="mdi-magnify"
                                            label="Поиск"
                                            single-line
                                            hide-details
                                        ></v-text-field>
                                        <v-btn
                                            color="primary" 
                                            dark 
                                            class="mb-2 ml-2"
                                        >Добавить
                                        </v-btn>
                                    </v-card-title>
                                    <v-data-table
                                        :headers="headers_items"
                                        :items="item"
                                        :search="search"
                                        sortable
                                        :loading="loading"
                                        @click="dialog=true"
                                        >
                                        <template v-slot:item.TASK_ID="{ item }">
                                            <v-chip
                                                class="mr-2"
                                                v-if="item.TASK_ID!=null"
                                                v-html='item.TASK_ID'
                                            >  
                                            </v-chip>
                                        </template>
                                        <template v-slot:item.STATUS="{ item }">
                                            <v-chip
                                                class="mr-2"
                                                v-if="item.STATUS!=null"
                                                v-html='item.STATUS'
                                            >  
                                            </v-chip>
                                        </template>
                                    </v-data-table>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item>
                        2
                    </v-tab-item>
                    <v-tab-item>
                        <v-card>
                            <v-card-title>
                                Компьютеры
                                <v-divider
                                    class="mx-4"
                                    inset
                                    vertical
                                >
                                </v-divider>
                                <v-spacer></v-spacer>
                                <v-text-field
                                        v-model="search"
                                        append-icon="mdi-magnify"
                                        label="Поиск"
                                        single-line
                                        hide-details
                                >
                                </v-text-field>
                                <v-btn
                                    color="primary" 
                                    dark 
                                    class="mb-2 ml-2"
                                >Добавить
                                </v-btn>
                            </v-card-title>
                            <v-data-table
                                :headers="headers_comp"
                                :items="glpi_data.data"
                                :search="search"
                                sortable
                                @click="dialog=true"
                                >
                            </v-data-table>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from 'axios';
import DialogAfterSendFrom from '@/components/DialogAfterSendForm.vue';
export default {
    components:{
        DialogAfterSendFrom,
    },
    data:()=>({
        title: "Тестовые вопросы к инструкциям по уборке нишевых культур",
        name: "",   
        tabs:[            
            {text:"Заявки"},
            {text:"Инциденты"},
            {text:"Компьютеры"}
        ],
        b_value: false,
        new_count:"",
        loading: false,     
        table_name:"Dashboard",
        table_inc: true,
        headers_items:[
            {text:"Наименование", value:"NAME"},
            {text:"Дата", value:"DATE"},
            {text:"Автор", value:"CREATED_BY"},
            {text:"Получатель", value:"POLUCHATEL"},
            {text:"Задача",value:"TASK_ID"},
            {text:"Статус", value:"STATUS"}
            ],
        headers_comp:[
            {text:"Имя компьютера", value:"1"},
            {text:"Местоположение", value:"3"},
            {text:"Тип", value:"4"},
            {text:"Пользователь", value:"70"},
            {text:"MAC", value:"113"}
            ],
        item:[],
        search:"",
        dialog: false,
        name_err: "",
        dialogMessage: "",
        progValue: "0",
        indeterminate: true,
        value: "0",
        glpi_data:[]
    }),
    methods:{
        formCancl: function(){
            this.$router.go(-1);
        },
        progSts(){
            let y = 0;
            for(let i=0; i < this.quest.length; i++){
                if (this.quest[i].value){
                    y = y + 10
                }
            }
            this.progValue = y
        },
        time(){
            setInterval(()=>{
                this.getData()
            }, 30000)
        },
        getData(){
            axios
             .get('https://portal.ahstep.ru/ahstep/services/ajax/dashboard/ajax.php', {
                 headers: { 'Content-Type': 'application/json; charset=utf-8'},
                 auth:{
                     username: "admin",
                     password: "Htdjk.wbz17"
                 },
                 params:{
                     result: "result"
                 }
                })
                .then(response => {
                    (   
                        this.new_count = response.data.length - this.item.length,                                                
                        this.item = response.data                        
                        )
                        //var now = new Date();
                        //console.log(this.item)
                        this.loading = false
                        this.value = this.item.length
                        this.indeterminate = false                    
                        /*if(this.new_count == 0){
                                this.b_value = false
                            }*/
                        if(this.new_count != 0 && this.new_count != response.data.length){
                            this.b_value = true
                            //this.new_count = this.new_count  + 1
                            }
                    })
        },
        getGlpiData(){
            axios
             .get('https://support.ahstep.ru/apirest.php/initSession', {
                 headers: { 
                    "Content-Type": "application/json;"
                     },
                 params:{
                     app_token: "TL0qYrS1zTK48QYMJD7N6yAvmO1WRx2BbsqB9iMu",
                     user_token: "vnF70gCwnlSpMnWwXqWafIzT0bWlPI2Lm7QJVXpg"
                 }
                })
                .then(response => {  
                        var s_token = response.data.session_token; 
                        axios
                            .get("https://support.ahstep.ru/apirest.php/search/Computer",{
                                headers:{
                                    "Content-Type": "application/json;",
                                    "Session-Token": s_token
                                },
                                params:{
                                    app_token: "TL0qYrS1zTK48QYMJD7N6yAvmO1WRx2BbsqB9iMu",
                                    range: "0-2000",
                                    forcedisplay: [3,4,70,113]
                                }
                            })
                            .then(response => {
                                this.glpi_data = response.data
                            })
                    })
        },
        getTest(){
            axios
             .get('https://b2btestservice.ocs.ru/b2bjson.asmx/GetCatalog', {
                 headers: { 
                    "Content-Type": "application/json;"
                     },
                 params:{
                     Login: "VqFLDv9gY",
                     Token: "VuivdzcaGvFtOACElaNdO?@-BKFMXy"
                 }
                })
                .then(response => {   
                        console.log(response.data)
                    })
        }                
    },
    mounted() {
        //this.getTest()  
        this.loading = true
        this.getData()
        this.time()
        this.getGlpiData()            
    }
}
</script>