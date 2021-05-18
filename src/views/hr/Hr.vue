<template>
    <v-container fluid>
        <br />
        <v-hover>
            <template v-slot="{ hover }">
                <v-card
                    max-width="400"
                    @click="$router.push('/test1')"
                    :elevation="hover ? 24 : 6"
                >
                    <v-list-item two-line>
                        <v-list-item-content>
                            <v-list-item-title class="headline mb-1"
                                >Тестирование</v-list-item-title
                            >
                            <v-list-item-subtitle
                                >Тестовые вопросы к инструкциям по уборке
                                нишевых культур</v-list-item-subtitle
                            >
                        </v-list-item-content>

                        <v-list-item-avatar size="60" color="grey">
                            <img src="@/assets/img/hr/tests/test.png" />
                        </v-list-item-avatar>
                    </v-list-item>
                </v-card>
            </template>
        </v-hover>
    </v-container>
</template>

<script>
export default {
    data: () => ({
        title: "HR",
        market_title: "Магазин",
    }),
};
</script>
