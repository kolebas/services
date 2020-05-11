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
                                    label
                                ><v-avatar left>
                                    <v-icon>mdi-account-circle</v-icon>
                                  </v-avatar>{{ data.item.NAME }}
                                </v-chip>
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