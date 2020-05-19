<template>
    <v-container>
    <v-card
    class="mx-auto"
    max-width="400"
    outlined
    >
        <v-list-item two-line>
            <v-list-item-content>
                <div class="overline mb-4">MARKET</div>
                <v-list-item-title class="headline mb-1">Внутренний магазин</v-list-item-title>
                <v-list-item-subtitle>Панель управления магазином</v-list-item-subtitle>
            </v-list-item-content>

            <v-list-item-avatar
                tile
                size="80"
                color="grey"
            ></v-list-item-avatar>
        </v-list-item>

        <v-card-actions>
        <v-btn @click="$router.go(-1)" outlined color="green lighten-2" >Вход</v-btn>
        </v-card-actions>
    </v-card>        
    <v-container>
            <v-tabs>
                <v-tab>Заказы</v-tab>
                    <v-tab-item>
                        <v-card>
                            <v-card-title>
                            Заказы
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Поиск"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </v-card-title>
                            <v-data-table
                            :headers="headers"
                            :items="desserts"
                            :search="search"
                            ></v-data-table>
                        </v-card>
                    </v-tab-item>
                <v-tab>Номенклатура</v-tab>
                    <v-tab-item>
                        <v-data-table
                            :headers="headers"
                            :items="desserts"
                            :items-per-page="20"
                            class="elevation-1"
                        ></v-data-table>
                    </v-tab-item>
            </v-tabs>
            </v-container>
    </v-container>
</template>

<script>
export default {
    data: () => ({
        title: "HR",
        market_title: "Магазин"
    })
}
</script>