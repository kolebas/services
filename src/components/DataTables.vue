<template>
  <v-card>
    <v-dialog v-model="dialogAdd" :max-width="dialogMaxWidth">
      <v-card :max-height="dialogMaxWHeight">
        <v-card-title>
          <span class="headline">{{ dialogTitle }}</span>
        </v-card-title>
        <v-divider />
        <Input :arrInput="getDialogFields" />
        <v-divider />
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close"> Отмена </v-btn>
          <v-btn color="blue darken-1" text @click="newItem"> Сохранить </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogUpd" :max-width="dialogMaxWidth">
      <v-card :max-height="dialogMaxWHeight">
        <v-card-title>
          <span class="headline">{{ dialogTitle }}</span>
        </v-card-title>
        <v-divider />
        <v-card-text>
          <v-container>
            <Input :arrInput="getDialogFields" />
          </v-container>
        </v-card-text>
        <v-divider />
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close"> Отмена </v-btn>
          <v-btn color="blue darken-1" text @click="updItem(dialogFields)">
            Сохранить
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card-title>
      <v-img
        v-if="img"
        max-height="30"
        max-width="30"
        :src="img"
        class="mr-2"
      />{{ title }}
      <v-divider class="mx-4" inset vertical></v-divider>
      <v-tooltip v-for="(item, index) in addTableButtons" :key="index" bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            v-bind="attrs"
            v-on="on"
            @click="exportTableToFile()"
            :color="item.color"
            class="mr-2"
            fab
            x-small
            ><v-icon>{{ item.icon }}</v-icon></v-btn
          >
        </template>
        <span>{{ item.tooltip }}</span>
      </v-tooltip>
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Поиск"
        single-line
        hide-details
      ></v-text-field>
      <v-btn
        v-if="addButtonOnTitle"
        color="primary"
        @click="
          dialogAdd = true;
          clearValue();
        "
        class="mb-2 ml-2"
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
      <template v-slot:[`item.USER`]="{ item }">
        <v-chip
          outlined
          color="#2FC6F6"
          text-color="rgba(0, 0, 0, 0.87)"
          class="mr-2"
          >{{ item.USER }}</v-chip
        >
      </template>
      <template v-slot:[`item.ACTIONS`]="{ item }">
        <v-icon
          class="mr-2"
          v-if="actions && actions.includes('add')"
          @click="add(item)"
          color="blue"
          >mdi-plus</v-icon
        >
        <v-icon
          class="mr-2"
          v-if="actions && actions.includes('chg')"
          @click="loadItem(item)"
          color="green"
          >mdi-pencil</v-icon
        >
        <v-icon
          v-if="actions && actions.includes('rem')"
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
      <template v-slot:[`item.ORDER_STATUS`]="props">
        <v-edit-dialog :return-value.sync="props.item.ORDER_STATUS"
          ><v-chip class="mr-2">{{ props.item.ORDER_STATUS }}</v-chip>
          <template v-slot:input>
            <v-select
              v-model="props.item.ORDER_STATUS"
              label="Статус"
              :items="itemsOrderStatus"
              @change="updItem(props.item)"
              single-line
            ></v-select>
          </template>
        </v-edit-dialog>
      </template>
      <template v-slot:[`item.ORDER`]="{ item }">
        <v-chip
          class="mr-1 my-1"
          v-for="(order, index) in item.ORDER"
          :key="index"
          color="info"
          text-color="black"
          outlined
          >{{ order }}</v-chip
        >
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import { bus } from "@/main.js";
import Input from "@/components/Input.vue";
export default {
  components: {
    Input,
  },
  props: {
    title: { type: String },
    dialogTitle: { type: String, default: "Новый параметр" },
    dialogFieldsCols: { type: Number, default: 12 },
    dialogMaxWidth: { type: String, default: "50%" },
    dialogMaxWHeight: { type: String, default: "50%" },
    dialogFields: { type: Array },
    addButtonOnTitle: { type: Boolean, default: true },
    addTableButtons: { type: Array },
    itemsOrderStatus: { type: Array, default: () => ["Новый", "Отгружен"] },
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
      //setTimeout(this.clearValue, 1000);
    },
    loadItem(item) {
      this.dialogUpd = true;
      let obj_arr = Object.values(item);
      for (let i = 0; i < this.dialogFields.length; i++) {
        this.dialogFields[i].value = obj_arr[i];
      }
      console.log(item);
    },
    updItem(data) {
      bus.$emit("updItem", data);
      this.close();
    },
    newItem() {
      this.dialogAdd = true;
      bus.$emit("newItem", this.dialogFields);
      this.close();
    },
    clearValue() {
      for (let i = 0; i < this.dialogFields.length; i++) {
        this.dialogFields[i].value = null;
      }
    },
    exportTableToFile() {
      bus.$emit("exportTableToFile", this.item);
    },
  },
};
</script>