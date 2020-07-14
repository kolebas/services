<template>
  <v-card>
    <v-card-title>
      {{title}}
      <v-divider class="mx-4" inset vertical></v-divider>
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Поиск"
        single-line
        hide-details
      ></v-text-field>
      <v-btn color="primary" dark class="mb-2 ml-2">Добавить</v-btn>
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
        <v-chip class="mr-2">{{item.NAME_UZ}}</v-chip>
      </template>
      <template v-slot:item.CONTACT_IT="{ item }">
        <v-chip @click="alert()" class="mr-2">{{item.CONTACT_IT}}</v-chip>
      </template>
      <template v-slot:item.TASK_ID="{ item }">
        <v-chip class="mr-2" v-if="item.TASK_ID != null" v-html="item.TASK_ID"></v-chip>
      </template>
      <template v-slot:item.STATUS="{ item }">
        <v-chip class="mr-2" v-if="item.STATUS != null" v-html="item.STATUS"></v-chip>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
export default {
  props: {
    title: { type: String },
    headers: { type: Array },
    item: { type: Array },
    loading: { type: Boolean },
    expand: { type: Boolean }
  },
  data: () => ({
    search: "",
    expanded: [],
  })
};
</script>