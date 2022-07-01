<template>
  <v-container>
    <v-row class="mb-n6">
      <v-col :cols="cols_title">
        <v-card-text class="subtitle-1 text-right pt-2"
          >{{title}}</v-card-text
        >
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
          label="Начните набирать название организации"
          item-text="NAME"
          item-value="NAME"
          :error-messages="org_err"
        >
          <template v-slot:selection="data">
            <v-chip color="#bcedfc" label close @click:close="org_name = '', org = []">
              <v-icon left>mdi-city</v-icon>
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
    title: { type: String, default: "Организация*:"},
    org_err: { type: String },
    cols_title: { type: Number },
    cols_input: { type: Number },
  },
  data: () => ({
    org: [],
    org_name: "",
    org_send: [],
  }),
  mounted() {
    axios
      .get("./ajax/GetOrg.php", {})
      .then((response) => (this.org = response.data))
      .catch((error) => console.log(error));
  },
  methods: {
    selectOrg: function () {
      function Org(name, rdt) {
        this.name = name;
        this.rdt = rdt;
      }
      let rdt = this.org.filter((getRDT) =>
        getRDT.NAME.includes(this.org_name)
      );
      let org = new Org(this.org_name, rdt[0].RDT);

      bus.$emit("selectOrg", org);
    },
  },
};
</script>