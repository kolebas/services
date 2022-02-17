<template>
  <v-container fluid>
    <br />
    <v-card class="py-12">
      <div v-for="item in tests" :key="item.route" >
        <v-hover>
          <template>
            <v-card
              max-width="400"
              @click="$router.push('/test1')"
              :elevation="hover ? 24 : 6"
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

                <v-list-item-avatar size="60" color="grey">
                  <img :src="require('../../assets/img/hr/tests/' + item.img)" />
                </v-list-item-avatar>
              </v-list-item>
            </v-card>
          </template>
        </v-hover>
      </div>
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
        title: "Анкета Увольняющегося",
        subtitle: "Тестовые вопросы к инструкциям по уборке нишевых культур",
        route: "/test2",
        img: "test.png",
      },
    ],
  }),
};
</script>


