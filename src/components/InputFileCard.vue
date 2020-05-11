<template>
    <v-file-input 
        type="file" 
        v-model="file" 
        :label="lable" 
        chips outlined solo multiple dense 
        ref="file"
        messages="Примечание: для добавление нескольких файлов зажмите кнопку CTRL и выберите необходимые файлы" 
        prepend-icon='' 
        prepend-inner-icon='mdi-file'
        @change='inputFile'
    >
        <template v-slot:selection="{ text }">
        <v-chip
            label
        ><v-icon left>mdi-paperclip</v-icon>
            {{ text }}
        </v-chip>
        </template>
    </v-file-input>
</template>

<script>
import { bus } from '../main.js';
export default {
    props: {
        //
    },
    data:() => ({
        lable: 'Выберите файл или файлы',
        file: []
    }),
    methods: {
        inputFile: function(){
            bus.$emit('inputFile', this.file);
        }
    },
}
</script>