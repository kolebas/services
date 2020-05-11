<template>
    <div class="text-center">
        <v-dialog
        v-model="dialog"
        width="500"
        persistent
        >
            <v-card>
                <v-card-title
                class="headline grey lighten-2"
                primary-title
                >
                {{ title }}
                </v-card-title>

                <v-card-text class="subtitle-1 text-center mt-4">
                    {{ warnMessage }}            
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                <v-spacer></v-spacer>
                    <v-btn
                        color="primary"
                        text
                        @click="funcDialog()"
                    >
                        {{ btn_text}}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>        
    </div>
</template>

<script>
export default {
    props: {
        dialog: {type: Boolean},
        warnMessage: {type: String}
    },
    data:() => ({
        title: 'Статус',
        btn_text: 'Понятно'
    }),
    methods: {
        funcDialog(){
            this.$router.go(-1);
        }
    }
}
</script>