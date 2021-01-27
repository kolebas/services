<template>
  <v-container>
    <v-row v-for="item in arrInput" class="mb-n6" :key="item.name">
      <v-col v-if="item.name" :cols="item.cs - 7">
        <v-card-text
          v-if="item.visible != false"
          class="subtitle-1 text-right pt-2"
        >
          {{ item.name }}
        </v-card-text>
      </v-col>
      <v-col :cols="item.cs" :sm="item.sm" :md="item.md">
        <v-text-field
          v-if="item.type == 'string' && item.visible != false"
          v-model="item.value"
          :class="item.class"
          :label="item.label"
          :outlined="item.outlined"
          :dense="item.dense"
          :solo="item.solo"
          required
          :error-messages="item.err"
          :append-icon="item.aicon"
          :suffix="item.suffix"
          @input="item.err = ''"
          @change="input"
        ></v-text-field>
        <v-menu
          v-if="item.type == 'date' && item.visible != false"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="item.value"
              :label="item.label"
              :outlined="item.outlined"
              :dense="item.dense"
              :solo="item.solo"
              v-bind="attrs"
              :error-messages="item.err"
              readonly
              v-on="on"
              @input="item.err = ''"
              @change="input"
            ></v-text-field>
          </template>
          <v-date-picker
            v-model="item.value"
            @input="menu = false"
            :range="item.range"
            :allowed-dates="item.allowed-dates"
            locale="ru-RU"
            @change="input"
          ></v-date-picker>
        </v-menu>

        <v-select
          v-if="item.type == 'select'"
          v-model="item.value"
          :items="item.select_arr"
          :label="item.name"
          :outlined="item.outlined"
          :dense="item.dense"
          :solo="item.solo"
          required
          :multiple="item.multiple"
          :error-messages="item.err"
          @change="
            input;
            item.err = '';
          "
        ></v-select>
        <v-textarea
          v-if="item.type == 'textarea' && item.visible != false"
          v-model="item.value"
          :outlined="item.outlined"
          :dense="item.dense"
          :solo="item.solo"
          :error-messages="item.err"
          :hint="item.hint"
          @input="item.err = ''"
          @change="input"
          >
        </v-textarea>
        <v-switch
          v-if="item.type == 'switch'"
          v-model="item.value"
          :label="item.label"
          inset
          :class="item.class"
          @change="input"          
        ><template slot="label"><span class="green--text text-uppercase">{{item.label}}</span></template></v-switch>
        <AutocompleteUsr
          v-if="item.type == 'selectUsr' && item.visible != false"
          @change="input"
          :classItem="item.class"
          :id="item.id"
          :userId_err="item.err"
        />
        <InputFileCard v-if="item.type == 'file'" />
        <InputAutocomplete :items="item" v-if="item.type == 'autocomplete'" />
        <SelectOrg :title='item.name' :cols_title='item.cols_title' :cols_input='item.cols_input' :org_err='item.org_err' v-if="item.type == 'selectOrg'" />
      </v-col>
    </v-row>
  </v-container>
</template>


<script>
import { bus } from "../main.js";
import AutocompleteUsr from "@/components/AutocompleteUsr.vue";
import InputFileCard from "@/components/InputFileCard.vue";
import InputAutocomplete from "@/components/InputAutocomplete.vue";
import SelectOrg from '@/components/SelectOrg.vue';
export default {
  components: {
    AutocompleteUsr,
    InputFileCard,
    InputAutocomplete,
    SelectOrg,
  },
  props: {
    arrInput: { type: Array },
    id: { type: String },
    title: { type: String },
    label: { type: String },
    suffix: { type: String },
    icon_in_rt: { type: String },
    input_err: { type: String },
    cols_title: { type: String },
    cols_input: { type: String },
  },
  data: () => ({
    dates: [],
    value: {
      input_id: "",
      value: "",
    },
  }),
  computed: {
    dateRangeText() {
      return this.item.value.join(" ~ ");
    },
  },
  methods: {
    change() {
      this.item.err = "";
    },
    input: function () {
      /*this.value.input_id = this.id;*/
      bus.$emit("resultArray", this.arrInput);
    },
  },
};
</script>