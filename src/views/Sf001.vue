<template>
    <v-container>
        <h1>{{ title }}</h1>
        <v-row>
        <v-card
            max-width="100%"
            raised
            class="mx-auto"
            color="grey lighten-4"            
            >
            <v-card-text class="pa-0">
                <p class="text-center pt-4 headline text--primary">{{ title }}</p>
                <p class="text-center">{{ sub_message }} <v-btn @click="btnToMyreq()" color="green lighten-2 white--text" small><v-icon left dark>mdi-format-list-bulleted</v-icon>Мои заявки</v-btn></p>
                <hr/>
            </v-card-text>
            <UsrSelect/>                
            <v-row class="mb-n6">
                <v-col cols="4">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Тип оборудования:
                    </v-card-text>                        
                </v-col>
                <v-col cols="6">
                    <v-combobox
                        v-model="type"
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
                    <v-textarea v-model="cmnt" outlined solo label="Для указания дополнительноый информации используйте это поле"></v-textarea>
                </v-col>                   
            </v-row>
            <hr/>
            <v-card-actions class="py-4">
                <div class="mx-auto">
                    <v-btn class="mx-1" color="green lighten-2 white--text" @click="test_sf001()">
                        Отправить
                    </v-btn>
                    <v-btn class="mx-1" @click="formCancl()">
                        Отмена
                    </v-btn>
                </div>
            </v-card-actions>                    
        </v-card>
    </v-row>
        <v-btn @click="test_sf001()">Test BTN</v-btn>
        <transition name="slide-fade">
            <v-row v-if="show">
                <v-card
                    max-width="55%"
                    raised
                    class="mx-auto"
                    color="grey lighten-4"
                    >
                    <v-card-text>{{ title }}</v-card-text>
                </v-card>            
            </v-row>
        </transition>        
            <button v-on:click="show = !show">
                Переключить
            </button>
    </v-container>
</template>

<script>
import axios from 'axios';
    export default{
        data:() => ({
            title: "Установка программного обеспечения (Dev)",
            cmnt: "",
            show: true,
        }),
        methods: {
        test_sf001:  function(){
       axios({
                method: 'post',
                withCredentials: true,
                headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                url: './ajax/ajax.php',
                /*auth: {
                    username: 'admin',
                    password: 'Htdjk.wbz17'
                },*/
                data: {
                    cmnt: this.cmnt
                }
            })
            .then(function (response) {
                //this.warnMessage = "Ваша заявка успешно отправлена";
                console.log(response);
            })
            .catch(function (error) {
                //this.warnMessage = "Произошла ошибка";
                console.log(error);
            });
    }
  }  
    }
</script>

<style>
    .slide-fade-enter-active {
    transition: all .3s ease;
    }
    .slide-fade-leave-active {
    transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active до версии 2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
    }
</style>