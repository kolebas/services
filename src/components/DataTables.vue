<template>
  <v-card>
    <v-dialog v-model="dialogAdd" :max-width="dialogMaxWidth">
      <v-card>
        <v-card-title>
          <span class="headline">{{ dialogTitle }}</span>
        </v-card-title>
        <v-divider />
        <v-card-text>
          <v-container>
            <v-row>
              <v-col
                v-for="field in getDialogFields"
                :key="field.i"
                :cols="dialogFieldsCols"
              >
                <v-text-field
                  v-model="field.val"
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
    <v-dialog v-model="dialogUpd" :max-width="dialogMaxWidth">
      <v-card>
        <v-card-title>
          <span class="headline">{{ dialogTitle }}</span>
        </v-card-title>
        <v-divider />
        <v-card-text>
          <v-container>
            <v-row>
              <v-col
                v-for="field in getDialogFields"
                :key="field.i"
                :cols="dialogFieldsCols"
              >
                <v-text-field
                  v-model="field.val"
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
      <v-img max-height="30" max-width="30" :src="img" class="mr-2" />{{
        title
      }}
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
      calculate-widths
      :show-expand="expand"
      :loading="loading"
      @click="dialog = true"
      single-expand
      :expanded.sync="expanded"
    >
      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length">More info about {{ item.NAME_UZ }}</td>
      </template>
      <template v-slot:[`item.NAME_UZ`]="{ item }">
        <v-chip class="mr-2">{{ item.NAME_UZ }}</v-chip>
      </template>
      <template v-slot:[`item.ACTIONS`]="{ item }">
        <v-icon
          class="mr-2"
          v-if="actions.includes('add')"
          @click="add(item)"
          color="blue"
          >mdi-plus</v-icon
        >
        <v-icon
          class="mr-2"
          v-if="actions.includes('chg')"
          @click="updItem(item)"
          color="green"
          >mdi-pencil</v-icon
        >
        <v-icon
          v-if="actions.includes('rem')"
          @click="remItem(item)"
          color="red"
          >mdi-delete</v-icon
        >
      </template>
      <template v-slot:[`item.CONTACT_IT`]="{ item }">
        <v-chip @click="alert()" class="mr-2">{{ item.CONTACT_IT }}</v-chip>
      </template>
      <template v-slot:[`item.TASK_ID`]="{ item }">
        <v-chip
          class="mr-2"
          v-if="item.TASK_ID != null"
          v-html="item.TASK_ID"
        ></v-chip>
      </template>
      <template v-slot:[`item.STATUS`]="{ item }">
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
    dialogTitle: { type: String, default: "Новый параметр" },
    dialogFieldsCols: { type: Number, default: 12 },
    dialogMaxWidth: { type: String, default: "50%" },
    dialogFields: { type: Array },
    headers: { type: Array },
    actions: { type: Array },
    item: { type: Array },
    loading: { type: Boolean },
    expand: { type: Boolean },
    img: { type: String },
  },
  data: () => ({
    dialogAdd: false,
    dialogUpd: false,
    itemValue: [],
    search: "",
    expanded: [],
  }),
  computed: {
    getDialogFields() {
      return this.dialogFields.filter(
        (getFields) => getFields.value != "ACTIONS" && getFields.actions == null
      );
    },
  },
  methods: {
    remItem(item) {
      bus.$emit("remItem", item);
    },
    close() {
      this.dialogAdd = false;
      this.dialogUpd = false;
      this.itemValue = "";
    },
    updItem() {
      this.dialogUpd = true;
      bus.$emit("updItem", this.dialogFields);
    },
    newItem() {
      this.dialogAdd = true;
      bus.$emit("newItem", this.dialogFields);
      this.close();
    },
  },
};
</script>