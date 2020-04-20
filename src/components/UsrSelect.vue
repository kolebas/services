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
                      outlined
                      solo
                      dense
                      chips
                      small-chips
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
        users: []

    }),
    mounted() {
        for (let i = 0, t = 1000; i < t ; i = i + 50) {
            axios
                .get('https://portal.ahstep.ru/rest/1/s7i3kl0o2ydbm29h/user.get.json', {
                    //onUploadProgress: function(progressEvent) {},
                    params: {
                        //ACTIVE: "Y",
                        start: i
                    }
                })
                .then(response => (this.users = this.users.concat(response.data.result)))
        }
    }
}
</script>