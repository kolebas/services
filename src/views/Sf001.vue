<template>
    <v-container>
        <h1>{{ title }}</h1>
        <transition name="slide-fade">
            <v-row v-if="show">
                <v-card
                    max-width="55%"
                    raised
                    class="mx-auto"
                    color="grey lighten-4"
                    >
                    <v-card-text>{{ title }}</v-card-text>
                </v-card>            
            </v-row>
        </transition>        
            <button v-on:click="show = !show">
                Переключить
            </button>
    </v-container>
</template>

<script>
    export default{
        data:() => ({
            title: "Установка программного обеспечения (Dev)",
            show: true,
        })
    }
</script>

<style>
    .slide-fade-enter-active {
    transition: all .3s ease;
    }
    .slide-fade-leave-active {
    transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active до версии 2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
    }
</style>