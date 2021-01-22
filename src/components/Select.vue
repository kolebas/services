<template>
  <v-container>
    <v-row class="mb-n6">
      <v-col :cols="cols_title">
        <v-card-text class="subtitle-1 text-right pt-2">
          {{ title }}
        </v-card-text>
      </v-col>
      <v-col :cols="cols_input">
        <v-autocomplete
          :items="item"
          v-model="value"
          @change="Select()"
          outlined
          solo
          dense
          clearable
          cache-items
          autofocus
          :label="label"
          item-text="NAME"
          item-value="NAME"
        >
          <template v-slot:selection="data">
            <v-chip color="#bcedfc" label close @click:close="value = ''"
              ><v-icon left>{{ icon }}</v-icon>
              {{ data.item.NAME }}
            </v-chip>
          </template>
        </v-autocomplete>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { bus } from "../main.js";
import axios from "axios";
export default {
  props: {
    title: { type: String },
    value_err: { type: String },
    icon: { type: String },
    label: { type: String },
    cols_title: { type: Number },
    cols_input: { type: Number },
    source: { type: String },
    param: { type: String },
  },
  data: () => ({
    item: [],
    value: "",
  }),
  mounted() {
    axios
      .get(this.source, {
        params: {
          param: this.param,
        },
      })
      .then((response) => (this.item = response.data))
      .catch((error) => console.log(error));
  },
  methods: {
    Select: function () {
      bus.$emit("Select", this.value);
    },
  },
};
</script>