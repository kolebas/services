<template>
    <v-container>
        <v-btn v-if="btns" @click="test_btn()">Test BTN</v-btn>

    </v-container>
</template>

<script>
import axios from 'axios';
export default {
    data: () => ({
        btns: true,
    }),
    methods: {
    test_btn:  function(){
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
                    cmnt: "this.cmnt"
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