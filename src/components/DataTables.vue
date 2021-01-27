<template>
  <v-card>
    <v-dialog v-model="dialogAdd" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Новый параметр</span>
        </v-card-title>
        <v-divider />
        <v-card-text>
          <v-container>
            <v-row>
              <v-col
                v-for="field in dialogFields"
                :key="field.text"
                cols="12"
                sm="6"
                md="12"
              >
                <v-text-field
                  v-model="itemValue"
                  :label="field.text"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-divider />
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close"> Отмена </v-btn>
          <v-btn color="blue darken-1" text @click="newItem"> Сохранить </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card-title>
      {{ title }}
      <v-divider class="mx-4" inset vertical></v-divider>
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Поиск"
        single-line
        hide-details
      ></v-text-field>
      <v-btn color="primary" @click="dialogAdd = true" class="mb-2 ml-2"
        >Добавить</v-btn
      >
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="item"
      :search="search"
      sortable
      :show-expand="expand"
      :loading="loading"
      @click="dialog = true"
      single-expand
      :expanded.sync="expanded"
    >
      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length">More info about {{ item.NAME_UZ }}</td>
      </template>
      <template v-slot:item.NAME_UZ="{ item }">
        <v-chip class="mr-2">{{ item.NAME_UZ }}</v-chip>
      </template>
      <template v-slot:item.ACTIONS="{ item }">
        <v-row>
          <v-col v-if="headers[2].actions.includes('add')" cols="12" md="3">
            <v-icon class="mr-2" @click="add(item)" color="blue"
              >mdi-plus</v-icon
            >
          </v-col>
          <v-col v-if="headers[2].actions.includes('chg')" cols="12" md="3">
            <v-icon class="mr-2" @click="editItem(item)" color="green"
              >mdi-pencil</v-icon
            >
          </v-col>
          <v-col v-if="headers[2].actions.includes('rem')" cols="12" md="3">
            <v-icon @click="remItem(item)" color="red">mdi-delete</v-icon>
          </v-col>
        </v-row>
      </template>
      <template v-slot:item.CONTACT_IT="{ item }">
        <v-chip @click="alert()" class="mr-2">{{ item.CONTACT_IT }}</v-chip>
      </template>
      <template v-slot:item.TASK_ID="{ item }">
        <v-chip
          class="mr-2"
          v-if="item.TASK_ID != null"
          v-html="item.TASK_ID"
        ></v-chip>
      </template>
      <template v-slot:item.STATUS="{ item }">
        <v-chip
          class="mr-2"
          v-if="item.STATUS != null"
          v-html="item.STATUS"
        ></v-chip>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import { bus } from "@/main.js";
export default {
  props: {
    title: { type: String },
    headers: { type: Array },
    item: { type: Array },
    loading: { type: Boolean },
    expand: { type: Boolean },
  },
  data: () => ({
    dialogAdd: false,
    itemValue: [],
    search: "",
    expanded: [],
  }),
  computed: {
    dialogFields() {
      return this.headers.filter(
        (getFields) => getFields.value != "ACTIONS" && getFields.actions == null
      );
    },
  },
  methods: {
    add(item) {
      this.dialogSub = true;
      console.log(item);
      console.log(this.headers[2].actions.includes("add"));
    },
    editItem(item) {
      this.dialogAdd = true;
      this.itemValue = item.NAME;
    },
    remItem(item){
      bus.$emit("remItem", item);
    },
    close() {
      this.dialogAdd = false;
    },
    newItem() {
      this.dialogAdd = true;
      bus.$emit("newItem", this.itemValue);
      this.close();
    },
  },
};
</script>