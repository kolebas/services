<template>
  <v-container fluid>
    <div v-for="item in inputFields" :key="item.name">
      <v-row v-if="item" class="mb-n6" >
        <v-col v-if="item.name" :cols="item.cs - 7">
          <v-card-text
            v-if="item.visible != false"
            :class="item.textPosition || textPosition"
          >
            {{ item.name }}
          </v-card-text>
        </v-col>
        <v-col :cols="item.cs" :sm="item.sm" :md="item.md">
          <v-text-field
            v-if="(item.type === 'string' || (item.type === 'S' && (item.user_type === '' || item.user_type === null ))) && item.visible != false"
            v-model="item.value"
            :type="item.typeDomInput"
            :class="item.class"
            :label="item.label"          
            :hint="item.hint"
            :outlined="item.outlined"
            :dense="item.dense"
            :solo="item.solo"
            :disabled="item.disabled"
            :error-messages="item.err"
            :messages="item.messages"
            :append-icon="item.icon"
            @click:append="appendIconCallback()"
            :required="item.required"
            :suffix="item.suffix"
            :rules="item.rule"
            @input="input(); item.err = ''"
            @change="input()"
          ></v-text-field>
          <v-menu
            v-if="(item.type === 'date' || (item.type === 'S' && item.user_type === 'Date')) && item.visible != false"
            v-model="dateDialog"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{}">
              <v-text-field
                type="date"
                v-model="item.value"
                :label="item.label"
                :outlined="item.outlined"
                :dense="item.dense"
                :solo="item.solo"
                :rules="item.rule"
                :error-messages="item.err"
                @input="item.err = ''"
                @change="input()"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="item.value"
              locale="ru-RU"
              @input="menu = false"
              @change="input()"
            ></v-date-picker>
          </v-menu>

          <v-select
            v-if="item.type === 'select' && item.visible != false"
            v-model="item.value"
            :items="item.select_arr"
            :label="item.label"
            :hint="item.hint"
            :outlined="item.outlined"
            :dense="item.dense"
            :solo="item.solo"
            :rules="item.rule"
            :multiple="item.multiple"
            :error-messages="item.err"
            @change="
              input();
              item.err = '';
            "
          ></v-select>
          <v-textarea
            v-if="item.type === 'textarea' && item.visible != false"
            v-model="item.value"
            :outlined="item.outlined"
            :dense="item.dense"
            :solo="item.solo"
            :class="item.class"
            :label="item.label"
            :error-messages="item.err"
            :hint="item.hint"
            @input="item.err = '', input()"
            @change="input()"
          >
          </v-textarea>
          <v-radio-group
            :class="item.radioClass"
            v-if="item.type == 'radio' && item.visible != false"
            v-model="item.value"
          >
            <v-radio
              v-for="item in item.radioGroup"
              :key="item"
              :label="item"
              :value="item"
              @change="input()"
            ></v-radio>
          </v-radio-group>
          <v-switch
            v-if="item.type === 'switch'"
            v-model="item.value"
            :label="item.label"
            inset
            :class="item.class"
            @change="input()"
            ><template slot="label"
              ><span class="green--text text-uppercase">{{
                item.label
              }}</span></template
            ></v-switch
          >
          <AutocompleteUsr
            v-if="(item.type === 'selectUsr' || item.user_type === 'employee') && item.visible != false "
            @change="input()"
            :classItem="item.class"
            :id="item.id"
            :label="item.label"
            :userId_err="item.err"
            :rules="item.rule"
            :multiple="item.multiple"
          />
          <InputFileCard v-if="item.type === 'file' || item.type === 'F'" />
          <InputAutocomplete :items="item" v-if="item.type === 'autocomplete' || item.type === 'L'" />
          <SelectOrg
            :title="item.name"
            :cols_title="item.cols_title"
            :cols_input="item.cols_input"
            :org_err="item.org_err"
            v-if="item.type == 'selectOrg'"
          />
        </v-col>
      </v-row>
    </div>
  </v-container>
</template>


<script>
import { bus } from "../main.js";
import AutocompleteUsr from "@/components/AutocompleteUsr.vue";
import InputFileCard from "@/components/InputFileCard.vue";
import InputAutocomplete from "@/components/InputAutocomplete.vue";
import SelectOrg from "@/components/SelectOrg.vue";
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
    textPosition: { type: String, default: "subtitle-1 text-right pt-2" },
    label: { type: String },
    suffix: { type: String },
    icon_in_rt: { type: String },
    input_err: { type: String },
    cols_title: { type: String },
    cols_input: { type: String },
  },
  data: () => ({
    dateDialog: "",
    dates: [],
    value: {
      input_id: "",
      value: "",
    },
    rulesTest: [
      (value) => !!value || "Поле обязательно для заполнения",
      (value) => (value || "").length <= 20 || "Max 20 characters",
    ],
  }),
  computed: {
    dateRangeText() {
      return this.item.value.join(" ~ ");
    },
    inputFields() {
      return this.arrInput.filter((input) => input.name !== "ID");
    },
  },
  methods: {
    change() {
      this.item.err = "";
    },
    input: function () {
      bus.$emit("resultArray", this.arrInput);
      return;
    },
    appendIconCallback(){
      bus.$emit("appendIconCallback");
    }
  },
};
</script>