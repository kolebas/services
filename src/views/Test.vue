<template>
    <v-container>
        <h1>{{ title }}</h1>
        {{ value }}
        <SelectUsr />
        <v-text-field  outlined>{{ value }}</v-text-field>

    </v-container>
</template>

<script>
import { bus } from '../main.js';
import SelectUsr from '../components/SelectUsr.vue';
export default {
    components: {
        SelectUsr
    },

    data: () => ({
        title: 'Test',
        value: '',
        id: ''
    }),
    created(){
    bus.$on('SelectUsr', data=>{
        console.log(data);
        this.value = data;
        });
    }   
    
}
</script>