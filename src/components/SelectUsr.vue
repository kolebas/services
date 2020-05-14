<template>
    <v-row class="mb-n6">
        <v-col :cols="cols_title">
            <v-card-text class="subtitle-1 text-right pt-2">
                {{ title }}
            </v-card-text>                
        </v-col>
        <v-col :cols="cols_input">
            <v-autocomplete
                    :items="users"
                    @change="selectUsr()"
                    v-model="userId"
                    outlined
                    solo
                    dense
                    chips
                    deletable-chips
                    cache-items
                    autofocus
                    label="Начните набирать фамилию или имя сотрудника"
                    item-text="NAME"
                    item-value="ID"
                    :error-messages="userId_err"                      
                    > 
                        <template v-slot:selection="data">
                                <v-chip
                                    color="#bcedfc"
                                    label
                                    close
                                    @click:close="userId=''"
                                ><v-avatar left>
                                    <img 
                                        v-if="data.item.PHOTO"
                                        :src=data.item.PHOTO
                                    >
                                    <v-icon v-else>mdi-account-circle</v-icon>
                                  </v-avatar><span color="red">{{ data.item.NAME }}</span>
                                </v-chip>
                            </template>
                            <template v-slot:item="data">
                                <template v-if="typeof data.item !== 'object'">
                                <v-list-item-content v-text="data.item.NAME"></v-list-item-content>
                                </template>
                                <template v-else>
                                <v-list-item-avatar v-if="data.item.PHOTO">
                                    <img :src="data.item.PHOTO">
                                </v-list-item-avatar>
                                <v-list-item-avatar v-else color="#bcedfc">
                                    <v-icon color="white">mdi-account-circle</v-icon>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title v-html="data.item.NAME"></v-list-item-title>
                                    <v-list-item-subtitle v-html="data.item.POSITION"></v-list-item-subtitle>
                                </v-list-item-content>
                                </template>
                            </template>
                    </v-autocomplete>
        </v-col>
    </v-row>
</template>

<script>
import { bus } from '../main.js';
import axios from 'axios';
export default {
    props: {
        title: {type: String},
        userId_err: {type: String},                
        cols_title: {type: Number},
        cols_input: {type: Number}
    },
    data: () => ({
        users: [],
        userId: '',
        userIdErr: '', 
        error: []
    }),
    methods: {
        selectUsr: function(){
            bus.$emit('SelectUsr', this.userId);
        }
    },
    //Получение списка пользователей
    mounted() {
         axios
             .get('./ajax/ajax_user.php', {                 
                })
                .then(response => (this.users = response.data))        
    }
}
</script>