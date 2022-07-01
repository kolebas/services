<template>
  <v-container>
    <v-row :class="classItem">
      <v-col :cols="cols_title" v-if="title">
        <v-card-text class="subtitle-1 text-right pt-2">{{
          title
        }}</v-card-text>
      </v-col>
      <v-col :cols="cols_input">
        <v-autocomplete
          :items="users"
          @change="selectUsr()"
          v-model="userId"
          :multiple="multiple"
          :search-input.sync="searchInput"          
          outlined
          solo
          dense
          chips
          class=""
          deletable-chips
          cache-items
          placeholder="Начните набирать фамилию или имя сотрудника"
          item-text="NAME"
          item-value="ID"
          :error-messages="userId_err"
        >
          <template v-slot:selection="data">
            <v-chip
              color="#bcedfc"
              label
              close
              @click:close="
                value = '';
                userId = '';
              "
            >
              <v-avatar left>
                <img v-if="data.item.PHOTO" :src="data.item.PHOTO" />
                <v-icon v-else>mdi-account-circle</v-icon>
              </v-avatar>
              <span color="red">{{ data.item.NAME }}</span>
            </v-chip>
          </template>
          <template v-slot:item="data">
            <v-list-item-avatar v-if="data.item.PHOTO">
              <img :src="data.item.PHOTO" />
            </v-list-item-avatar>
            <v-list-item-avatar v-else color="#bcedfc">
              <v-icon color="white">mdi-account-circle</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title v-html="data.item.NAME"></v-list-item-title>
              <v-list-item-subtitle
                v-html="data.item.POSITION"
              ></v-list-item-subtitle>
            </v-list-item-content>
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
    classItem: { type: String, default: "mb-n6" },
    userId_err: { type: String, default: "" },
    label: { type: String, default: "" },
    cols_title: { type: Number },
    cols_input: { type: Number },
    multiple: { type: Boolean },
    id: { type: Number },
  },
  data: () => ({
    users: [],
    userId: "",
    searchInput: "",
    error: [],
    value: [],
  }),
  methods: {
    selectUsr: function () {
      this.searchInput = "";
      //Условие когда необходимо использовать несколько селектов на одной форме
      if (this.id != null) {
        this.value.input_id = this.id;
        this.value.userId = this.userId;
        bus.$emit("SelectUsr", this.value);
      } else {
        bus.$emit("SelectUsr", this.userId);
      }
    },
  },
  //Получение списка пользователей
  mounted() {
    axios
      .get("./ajax/ajax_user.php", {})
      .then((response) => (this.users = response.data));
  },
};
</script>