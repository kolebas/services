<template>
    <v-autocomplete
              v-if="items.visible != false"
              :items="items.items"
              v-model="items.value"
              :outlined="items.outlined"
              :solo="items.solo"
              :dense="items.dense"
              clearable
              cache-items
              autofocus
              :multiple="items.multiple"
              :label="items.label"
              item-text="NAME"
              item-value="NAME"
              :error-messages="items.err"
              @change="changeValue()"
            >
              <template v-slot:selection="data">
                <v-chip
                  color="#bcedfc"
                  label
                  close
                  @click:close="items.value = ''"
                >
                  <v-icon v-if="items.icon || data.item.ICON" :color="items.color || data.item.COLOR" left>{{items.icon}} || {{ data.item.ICON }}</v-icon>
                  {{ data.item.NAME }}
                </v-chip>
              </template>
    </v-autocomplete>
</template>

<script>
import { bus } from '../main.js';
export default {
    props: {
        items: {type: Object},
        label: {type: String},
    },
    data:() => ({
    }),
    methods: {
        changeValue: function(){
            bus.$emit('valueAutocomplete', this.items.value);
        }
    },
}
</script>