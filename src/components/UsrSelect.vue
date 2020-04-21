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
                    v-model="userId"
                      outlined
                      solo
                      dense
                      chips
                      label="Укажите пользователей которые могут создавать удалять и редактровать файлы"
                      :item-text="users => users.LAST_NAME + ' ' + users.NAME"
                      :item-value="users => users.ID"                      
                    > 
                    </v-autocomplete>
        </v-col>                   
    </v-row>
</template>

<script>
import axios from 'axios';
export default {
    data: () => ({
        users: [],
        userId: ''
    }),
    mounted() {
         axios
             .get('./ajax/ajax.php', { 
                //.get('./ajax/ajax.php', {
                    //onUploadProgress: function(progressEvent) {},
                   /* auth: {
                    username: 'admin',
                    password: 'Htdjk.wbz17'
                },*/
                })
                .then(response => (this.users = response.data))        
    }
}
</script>