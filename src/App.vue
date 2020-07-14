<template>
    <v-app>
        <v-main>
            <v-alert
                v-if="top_bar"
                class="ml-4 mr-4"
                border="left"
                colored-border
                color="primary"
                elevation="2"
                dense
            >
                <v-btn
                    v-for="item in items"
                    :key="item.text"
                    text
                    @click="itemclk(item.lnk, item.route)"
                >
                    <v-icon left color="green">{{ item.icon }}</v-icon>
                    {{ item.text }}
                </v-btn>
                <v-btn text disabled>
                    <v-icon left>mdi-file-cog</v-icon>
                    {{ this.$router.currentRoute.name }}
                </v-btn>
            </v-alert>
            <router-view />
        </v-main>
    </v-app>
</template>

<script>
export default {
    name: "App",

    data: () => ({
        top_bar: "",
        items: [
            {
                text: "Главная",
                disabled: false,
                lnk: "/stream",
                icon: "mdi-home",
            },
        ],
    }),
    methods: {
        itemclk: function(lnk, route) {
            if (route != null) {
                this.$router.push(route);
            } else {
                document.location.href = lnk;
            }
        },
    },
    mounted() {
        if (this.$router.currentRoute.path != "/") {
            this.items.splice(1, 1);
            let services = {
                text: "Заявки",
                route: "/",
            };
            this.items.push(services);
        }
    },
};
</script>

<style>
body {
    overflow: unset !important;
}
#uiToolbarContainer {
    display: none;
}
.v-breadcrumbs {
    background: #f5f5f5;
    margin-left: 1% !important;
    margin-right: 1% !important;
    margin-top: 10px !important;
    margin-bottom: 10px !important;
    border-radius: 10px !important;
}
</style>
