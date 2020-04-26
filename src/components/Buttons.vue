<template>
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
</template>

<script>
//import axios from 'axios';
export default {
    data: () => ({
        btns: true,
    }),
   /* methods: {
    test_btn:  function(){
       axios({
                method: 'post',
                withCredentials: true,
                headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                url: './ajax/ajax.php',
                /*auth: {
                    username: 'admin',
                    password: 'Htdjk.wbz17'
                },
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
  }  */  
}
</script>