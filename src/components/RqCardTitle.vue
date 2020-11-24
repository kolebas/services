<template>
    <v-container>
        <v-card-text class="pa-0">
                <p class="text-center pt-4 headline text--primary">{{ title }}</p>
                <p class="subtitle-1 font-weight-medium mx-8"><v-icon left color="blue darken-2"> mdi-information-outline</v-icon>{{ sub_message }} <v-btn @click="btnToMyreq()" color="green lighten-2 white--text" small><v-icon left dark>mdi-format-list-bulleted</v-icon>Мои заявки</v-btn></p>
        </v-card-text>
    </v-container>
</template>

<script>
export default {
    props: {
        title: {
            type: String
        },
        sub_message: {
            type: String
        }
    },
    data: () =>({
        //
    }),
    methods: {
        //Действие кнопки "Мои заявки"
        btnToMyreq(){
            document.location.href = "/it-uslugi/helpdesk/my_ticket.php";
        },
    }
}
</script>