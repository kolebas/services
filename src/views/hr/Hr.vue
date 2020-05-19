<template>
    <v-container>
    <v-dialog
    v-model="dialog"
    width="500"
    persistent
    >
        <v-card>
            <v-card-title
            class="headline grey lighten-2"
            primary-title
            >
            Добавление позиции
            </v-card-title>
            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field v-model="item_name" label="Наименование"></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field type="number" v-model="item_price" label="Цена"></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field v-model="item_tara" label="Тара"></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
            <v-spacer></v-spacer>
                <v-btn color="primary" text @click="dialog=false">Отмена</v-btn>
                <v-btn color="primary" text @click="addItem()">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-card
    class="mx-auto"
    max-width="400"
    outlined
    >
        <v-list-item two-line>
            <v-list-item-content>
                <div class="overline mb-4">MARKET</div>
                <v-list-item-title class="headline mb-1">Внутренний магазин</v-list-item-title>
                <v-list-item-subtitle>Панель управления магазином</v-list-item-subtitle>
            </v-list-item-content>

            <v-list-item-avatar
                tile
                size="80"
                color="grey"
            ></v-list-item-avatar>
        </v-list-item>

        <v-card-actions>
        <v-btn @click="$router.go(Hr)" outlined color="green lighten-2" >Вход</v-btn>
        </v-card-actions>
    </v-card>        
    <v-container>
            <v-tabs>
                <v-tab @click="getItem(857)">Заказы</v-tab>
                    <v-tab-item>
                        <v-card>
                            <v-card-title>
                            Заказы
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Поиск"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </v-card-title>
                            <v-data-table
                            :loading="loading"
                            loading-text="Загружаю данные"
                            :headers="headers_orders"
                            :items="item"
                            :search="search"
                            ></v-data-table>
                        </v-card>
                    </v-tab-item>
                <v-tab @click="getItem(856)">Номенклатура</v-tab>
                    <v-tab-item>
                        <v-card>
                            <v-card-title>
                            Номенклатура
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Поиск"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-btn
                                    @click="dialog=true" 
                                    color="primary" 
                                    dark 
                                    class="mb-2 ml-2"
                                >Добавить
                                </v-btn>
                            </v-card-title>
                            <v-data-table
                                :headers="headers_items"
                                :items="item"
                                :search="search"
                            ></v-data-table>
                        </v-card>
                    </v-tab-item>
            </v-tabs>
            </v-container>
    </v-container>
</template>

<script>
import axios from 'axios';
export default {
    data: () => ({
        title: "HR",
        market_title: "Магазин",
        loading: false,
        //source: "./ajax/market/ajax_hr.php"
        headers_items: [
            {text: "Наименование", value: "NAME"},
            {text: "Дата", value: "DATE"},
            {text: "Тара", value: "TARA"},
            {text: "Цена", value: "PRICE"}
        ],
        headers_orders: [            
            {text: "Заказчик", value: "CREATED_BY"},
            {text: "Дата", value: "DATE"},
            {text: "Наименование", value: "NAME"},
            {text: "Тара", value: "TARA"},
            {text: "Цена", value: "PRICE"}
        ],
        item: [],
        item_name: "",
        item_tara: "",
        item_price: "",
        search: "",
        source: "https://portal.ahstep.ru/ahstep/services/ajax/hr/ajax_hr.php",
        dialog: false
    }),
    mounted() {
        this.loading = true
         axios
             .get(this.source, {
                auth:{
                  username: "zaikin.ni",
                  password: "Vbuhfwbz75"  
                },
                 params:{
                    param: 857
                 }
                })
                .then(response => {
                    (this.item = response.data)
                    this.loading = false
                })                
                .catch(error => (console.log(error)))    
    },
    methods:{
        getItem(type){
            this.loading = true
         axios
             .get(this.source, {
                auth:{
                  username: "zaikin.ni",
                  password: "Vbuhfwbz75"  
                },
                 params:{
                    param: type
                 }
                })
                .then(response => {
                    (this.item = response.data)
                    this.loading = false
                })                
                .catch(error => (console.log(error)))    
    },
        addItem(){
            if(this.item_name && this.item_price && this.item_tara){            
            axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: this.source,
                    auth:{
                        username: "zaikin.ni",
                        password: "Vbuhfwbz75"  
                        },
                    data: {
                        name: this.item_name,
                        price: this.item_price,
                        tara: this.item_tara
                    } 
                })
                .then(response => {
                    if(response.status == 200){
                        axios
                            .get(this.source, {
                                auth:{
                                username: "zaikin.ni",
                                password: "Vbuhfwbz75"  
                                },
                                params:{
                                    param: this.param
                                }
                                })
                                .then(response => (this.item = response.data))
                            }
                        this.dialog = false
                })
                .catch(error => (console.log(error)))
            }   
        }
    }
}
</script>