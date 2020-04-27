<template>
    <v-row class="mb-n6">
        <v-col cols="4">
            <v-card-text class="subtitle-1 text-right pt-2">
                ФИО сотрудника:
            </v-card-text>                
        </v-col>
        <v-col cols="6">
            <v-autocomplete
                    :items="users"
                    @change="selectUsr()"
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
</template>

<script>
import { bus } from '../main.js';
import axios from 'axios';
export default {
    props: {
        userId_err: {
            type: String
        }
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