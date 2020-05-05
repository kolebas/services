<template>
        <v-row class="mb-n6">
                <v-col cols="5">
                    <v-card-text class="subtitle-1 text-right pt-2">
                        Организация:
                    </v-card-text>                
                </v-col>
                <v-col cols="6">
                    <v-autocomplete
                            :items="org"
                            v-model="org_name"
                            @change="selectOrg()"
                            outlined
                            solo
                            dense
                            chips
                            deletable-chips
                            cache-items
                            autofocus
                            label="Начните набирать название организации"
                            item-text="NAME"
                            item-value="NAME"
                            :error-messages="org_err"                      
                            >
                            </v-autocomplete>
                </v-col>
            </v-row>    
</template>

<script>
import { bus } from '../main.js';
import axios from 'axios';
export default {
    props: {
        org_err: {type: String}
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
    },
    methods: {
        selectOrg: function(){
            bus.$emit('selectOrg', this.org_name);
        }
    },    
}
</script>