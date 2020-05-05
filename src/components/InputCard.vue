<template>
        <v-row class='mb-n6'>
            <v-col cols='5'>
                <v-card-text class='subtitle-1 text-right pt-2'>
                    {{ title }}
                </v-card-text>                        
            </v-col>
            <v-col cols='6'>
                <v-text-field 
                    v-model='value.value' 
                    outlined 
                    solo 
                    dense
                    :id='id'
                    :error-messages='input_err' 
                    :label='label' 
                    @change='inputCard'
                ></v-text-field>
            </v-col>                    
        </v-row>        
</template>

<script>
import { bus } from '../main.js';
export default {
    props: {
        id: {type: String},
        title: {type: String},
        label: {type: String},
        input_err: {type: String}
    },
    data:() => ({
        value: {
            input_id: '',
            value: ''
        }
    }),
    methods: {
        inputCard: function(){
            this.value.input_id = this.id;
            bus.$emit('inputCard', this.value);
        }
    },
}
</script>