<template>
        <v-row class="mb-n6">
                <v-col :cols="cols_title">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Организация:
                    </v-card-text>                
                </v-col>
                <v-col :cols="cols_input">
                    <v-autocomplete
                            :items="org"
                            v-model="org_name"
                            @change="selectOrg()"
                            outlined
                            solo
                            dense
                            clearable
                            cache-items
                            autofocus
                            label="Начните набирать название организации"
                            item-text="NAME"
                            item-value="NAME"
                            :error-messages="org_err"                      
                            >
                            <template v-slot:selection="data">
                                <v-chip
                                    label
                                ><v-icon left>mdi-city</v-icon>
                                    {{ data.item.NAME }}
                                </v-chip>
                            </template>
                            </v-autocomplete>
                </v-col>
            </v-row>    
</template>

<script>
import { bus } from '../main.js';
import axios from 'axios';
export default {
    props: {
        org_err: {type: String},        
        cols_title: {type: Number},
        cols_input: {type: Number}
    },
    data: () => ({
        org: [],
        org_name: ''
    }),
    mounted() {
         axios
             .get('./ajax/ajax_ms001.php', {
                
                })
                .then(response => (this.org = response.data))
                .catch(error => (console.log(error)))        
    },
    methods: {
        selectOrg: function(){
            bus.$emit('selectOrg', this.org_name);
        }
    },    
}
</script>