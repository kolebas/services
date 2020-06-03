<template>
    <v-container>
    <v-dialog
        v-model="dialog"
        max-width="65%"
        persistent
        >
        <v-card>
            <v-card-title
            class="headline grey lighten-2"
            primary-title
            >
            {{ dialog_title }}
            </v-card-title>
            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="6" md="8">
                    <v-text-field v-model="item_name" label="Наименование"></v-text-field>
                  </v-col>
                  <v-col cols="6" sm="3" md="2">
                    <v-text-field type="number" v-model="item_price" label="Цена"></v-text-field>
                  </v-col>
                  <v-col cols="6" sm="3" md="2">
                    <v-select v-model="item_tara" :items="items_tara" label="Тара"></v-select>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
            <v-spacer></v-spacer>
                <v-btn color="primary" text @click="close()">Отмена</v-btn>
                <v-btn color="primary" text @click="addItem(856)">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>        
    <v-container>
            <v-tabs>
                <v-tab @click="getItem(857)">Заказы</v-tab>
                    <v-tab-item>
                        <v-card>
                            <v-card-title>
                            Заказы
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                                ></v-divider>
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
                            :headers="product_name"
                            :items="orders"
                            :search="search"
                            >
                            </v-data-table>
                        </v-card>
                    </v-tab-item>
                <v-tab @click="getItem(856)">Номенклатура</v-tab>
                    <v-tab-item>
                        <v-card>
                            <v-card-title>
                            Номенклатура
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                                ></v-divider>
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Поиск"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-btn
                                    @click="dialog=true, dialog_title='Добавление позиции'" 
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
                                >
                                <template v-slot:item.ACTIONS="{ item }">
                                    <v-icon
                                        small
                                        class="mr-2"
                                        @click="editItem(item.ID)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                    <v-icon
                                        small
                                        @click="deleteItem(item.ID)"
                                    >
                                        mdi-delete
                                    </v-icon>
                                    </template>
                            </v-data-table>
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
        headers_items: [
            {text: "Наименование", value: "NAME"},
            {text: "Дата", value: "DATE"},
            {text: "Тара", value: "TARA"},
            {text: "Цена", value: "PRICE"},
            {text: "Действия", value: "ACTIONS", sortable: false}
        ],
        headers_orders: [            
            {text: "Заказчик", value: "CREATED_BY"},
            {text: "Дата", value: "DATE"},
            {text: "Наименование", value: "NAME"},
            {text: "Тара", value: "TARA"},
            {text: "Цена", value: "PRICE"}
        ],
        item: [],
        product_name: [
            {text: "Заказчик", value: "created_order"},
            {text: "Дата заказа", value: "date"}
        ],
        orders: [],
        item_name: "",
        item_tara: "",
        items_tara: ["Уп.", "Кг."],
        item_price: "",
        search: "",
        source: "./ajax/hr/ajax_hr.php",
        dialog: false,
        dialog_title: ""
    }),
    mounted() {
        this.loading = true
         axios
             .get("https://portal.ahstep.ru/ahstep/services/ajax/hr/ajax_hr.php", {
                 auth:{
                     username: "zaikin.ni",
                     password: "Vbuhfwbz75"
                 },
                 params:{
                    param: "get_orders"
                 }
                })
                .then(response => {
                    (this.item = response.data)
                    this.loading = false
                    for(let i=0; i < this.item.length; i++ ){
                        if(this.item[i].PRODUCT_NAME != null){
                            this.product_name.push({
                                text: this.item[i].PRODUCT_NAME,
                                value: this.item[i].PRODUCT_ID
                            })                        
                        }
                        if(this.item[i].ORDER_ID != null){
                            var value = this.item[i].PRODUCT_ID
                            this.orders.push({
                                created_order: this.item[i].CREATED_BY,
                                date: this.item[i].DATE,
                                [value]: this.item[i].VALUE
                            })
                            console.log(value)
                        }                            
                    }
                    console.log(this.item)
                    //console.log(this.product_name)
                    //console.log(this.orders)
                })                
                .catch(error => (console.log(error)))
                .get("https://portal.ahstep.ru/ahstep/services/ajax/hr/ajax_hr.php", {
                 auth:{
                     username: "zaikin.ni",
                     password: "Vbuhfwbz75"
                 },
                 params:{
                    param1: "get_products"
                 }
                })
                .then(response => {
                    (this.item = response.data)
                    this.loading = false
                    for(let i=0; i < this.item.length; i++ ){
                        if(this.item[i].PRODUCT_NAME != null){
                            this.product_name.push({
                                text: this.item[i].PRODUCT_NAME,
                                value: this.item[i].PRODUCT_ID
                            })                        
                        }
                        if(this.item[i].ORDER_ID != null){
                            var value = this.item[i].PRODUCT_ID
                            this.orders.push({
                                created_order: this.item[i].CREATED_BY,
                                date: this.item[i].DATE,
                                [value]: this.item[i].VALUE
                            })
                            console.log(value)
                        }                            
                    }
                    console.log(this.item)
                    //console.log(this.product_name)
                    //console.log(this.orders)
                })                
                .catch(error => (console.log(error)))
    },
    methods:{
        getItem(type){
            this.loading = true
         axios
             .get(this.source, {
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
        addItem(type){
            if(this.item_name && this.item_price && this.item_tara){           
            axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: this.source,
                    data: {
                        name: this.item_name,
                        price: this.item_price,
                        tara: this.item_tara
                    }
                })
                .then(response => {
                    if(response.status == 200){
                        this.getItem(type)
                            }
                        this.dialog = false
                })
                .catch(error => (console.log(error)))
            }   
        },
        editItem(id){
            let arr = []
            arr = this.item.filter(arr => arr.ID == id)
            this.dialog = true
            this.dialog_title = "Редактирование позиции"
            this.item_name = arr[0].NAME
            this.item_price = arr[0].PRICE
            this.item_tara = arr[0].TARA

            axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: this.source,
                    data: {
                        type: "edit",
                        id: id
                    }
                })
                .then(response => {
                    if(response.status == 200){
                        this.getItem(856);
                    }
                })
                .catch(error => (console.log(error)))
        },
        deleteItem(id){
            axios({
                    method: 'post',
                    withCredentials: true,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
                    url: this.source,
                    data: {
                        type: "delete",
                        id: id
                    }
                })
                .then(response => {
                    if(response.status == 200){
                        this.getItem(856);
                    }
                })
                .catch(error => (console.log(error)))
        },
        close(){
            this.dialog = false
        }
    }
}
</script>