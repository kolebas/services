<template>
        <v-row class='mb-n6'>
            <v-col :cols='cols_title'>
                <v-card-text class='subtitle-1 text-right pt-2'>
                    {{ title }}
                </v-card-text>                        
            </v-col>
            <v-col :cols='cols_input'>
                <v-text-field 
                    v-model='value.value' 
                    outlined 
                    solo 
                    dense
                    :id='id'                     
                    :label='label'
                    :suffix='suffix'
                    :append-icon='icon_in_rt' 
                    :error-messages='input_err'
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
        suffix: {type: String},
        icon_in_rt: {type: String},
        input_err: {type: String},
        cols_title: {type: String},
        cols_input: {type: String},
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