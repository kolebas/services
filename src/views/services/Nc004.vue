<template>
  <v-container>
    <v-row>
      <v-card max-width="55%" raised class="mx-auto" color="grey lighten-4">
        <RqCardTitle :title="title" :sub_message="sub_message"></RqCardTitle>
        <hr />
        <SelectUsr :cols_title="4" :cols_input="6" title="ФИО:" :userId_err="userId_err" />
        <v-row class="mb-n6">
          <v-col cols="4">
            <v-card-text class="subtitle-1 text-right pt-2">Наименование ПО:</v-card-text>
          </v-col>
          <v-col cols="6">
            <v-select
              @change="showSoftModal()"
              v-model="soft"
              :items="items_type"
              label="Выберите необходимое ПО"
              solo
              outlined
              dense
              :error-messages="type_err"
            >
              <template v-slot:selection="{ item }">
                <v-chip color="#bcedfc" close label @click:close="soft=''">{{ item }}</v-chip>
              </template>
            </v-select>
          </v-col>
        </v-row>
        <v-row class="mb-n6">
          <v-col cols="4">
            <v-card-text class="subtitle-1 text-right pt-2">Комментарий:</v-card-text>
          </v-col>
          <v-col cols="6">
            <v-textarea
              v-model="cmnt"
              outlined
              solo
              label="Для указания дополнительной информации используйте это поле"
            ></v-textarea>
          </v-col>
        </v-row>
        <hr />
        <v-card-actions class="py-4">
          <div class="mx-auto">
            <v-btn
              class="mx-1"
              :loading="btnLoader"
              color="green lighten-2 white--text"
              @click="formSend()"
            >Отправить</v-btn>
            <v-btn class="mx-1" @click="formCancl()">Отмена</v-btn>
          </div>
        </v-card-actions>
      </v-card>
    </v-row>
  </v-container>
</template>

<script>
export default {};
</script>

<style>
</style>