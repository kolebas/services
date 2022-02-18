<template>
  <v-container fluid>
    <br />
    <v-card class="py-12 px-6">
      <v-row>
        <div v-for="item in tests" :key="item.route">
          <v-hover v-slot="{ hover }">
            <v-card
              max-width="400"
              @click="$router.push(item.route)"
              :elevation="hover ? 8 : 2"
              class="ml-4"
            >
              <v-list-item two-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">{{
                    item.title
                  }}</v-list-item-title>
                  <v-list-item-subtitle>{{
                    item.subtitle
                  }}</v-list-item-subtitle>
                </v-list-item-content>
                <img
                  :src="require('../../assets/img/hr/tests/' + item.img)"
                  contain
                />
              </v-list-item>
            </v-card>
          </v-hover>
        </div>
      </v-row>
    </v-card>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    title: "HR",
    tests: [
      {
        title: "Тест №1",
        subtitle: "Тестовые вопросы к инструкциям по уборке нишевых культур",
        route: "/test1",
        img: "test.png",
      },
      {
        title: "Анкета",
        subtitle: "Анкета увольняющегося сотрудника",
        route: "/test2",
        img: "anketa.png",
      },
    ],
  }),
};
</script>