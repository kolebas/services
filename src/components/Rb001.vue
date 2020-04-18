<template>
<v-container>
    <h1>{{ title }}</h1>
    <v-row>
        <v-card
            max-width="55%"
            raised
            class="mx-auto"
            color="grey lighten-4"            
            >
                <v-card-text class="pa-0">
                    <p class="text-center pt-4 headline text--primary">{{title}}</p>
                    <p class="text-center">{{sub_message}} <v-btn @click="clkmyreq()" color="green lighten-2 white--text" small><v-icon left dark>mdi-format-list-bulleted</v-icon>Мои заявки</v-btn></p>
                    <hr/>
                </v-card-text>
                <v-row class="mb-n6">
                    <v-col cols="4">
                        <v-card-text class="subtitle-1 text-right pt-2">
                            ФИО сотрудника:
                        </v-card-text>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field label="Solo" placeholder="Начните вводить фамилию сотрудника" dense outlined solo></v-text-field>
                    </v-col>                   
                </v-row>
                <v-row class="mb-n6">
                    <v-col cols="4">
                        <v-card-text class="subtitle-1 text-right pt-2">
                            Тип оборудования:
                        </v-card-text>                        
                    </v-col>
                    <v-col cols="6">
                        <v-combobox
                            :items="items_type"
                            label="Выберите необходимое оборудование"
                            chips
                            multiple
                            solo
                            outlined
                            dense
                        ></v-combobox>
                    </v-col>                   
                </v-row>
                <v-row class="mb-n6">
                    <v-col cols="4">
                        <v-card-text class="subtitle-1 text-right pt-2">
                            Комментарий:
                        </v-card-text> 
                    </v-col>
                    <v-col cols="6">
                        <v-textarea outlined solo label="Для указания дополнительноый информации используйте это поле"></v-textarea>
                    </v-col>                   
                </v-row>
                <hr/>
                <v-card-actions class="py-4">
                    <div class="mx-auto">
                        <v-btn class="mx-1" color="green lighten-2 white--text" @click="btn_sent()">
                            Отправить
                        </v-btn>
                        <v-btn class="mx-1" @click="btn_cns()">
                            Отмена
                        </v-btn>
                    </div>
                </v-card-actions>                    
        </v-card>
    </v-row>    
</v-container>
</template>

<script>
    export default{
        data:() => ({
            title: "Заявка на закупку (DEV)",
            sub_message: "Заявка согласуется внутри департамента ИТ, при необходимости может быть дополнительно отправлена на согласование руководителям подразделений или компаний. Статус созданной заявки вы моежете отслеживать в разделе ",
            items_type: ["Стационарный компьютер","Ноутбук","Монитор","МФУ","Стационарный телефон","Прочее оборудование и ПО"],
    }),
    methods: {
        btn_sent: function(){
            console.log("Отправка");
        },
        btn_cns: function(){
            console.log("Отмена");
        }
    }
    }    
</script>