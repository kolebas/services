<template>
    <v-container>
        <v-row>
            <v-card
            max-width="55%"
            raised
            class="mx-auto"
            color="grey lighten-4"            
            >
                <v-card-text class="pa-0">
                    <p class="text-center pt-4 headline text--primary">{{ title }}</p>
					<p class="subtitle-1 font-weight-medium mx-8">{{ sub_message }} <v-btn @click="clkmyreq()" color="green lighten-2 white--text" small><v-icon left dark>mdi-format-list-bulleted</v-icon> Мои заявки</v-btn></p>
                </v-card-text>

            <v-expansion-panels
                :inset="inset"
                :focusable="focusable"
                :tile="tile"
            >
                <v-expansion-panel
                    v-for="(cat, i) in cats"                    
                    :key="i"
                >                    
                    <v-expansion-panel-header v-if="condition && cat.status == 'dev'" class="py-0">
                            <v-col cols="1" class="px-0">
                                <img  :src="require('../assets/img/' + cat.img)">
                            </v-col>
                            <v-col cols="10" class="px-0">
                               <p class="subtitle-1 font-weight-medium mb-0">{{cat.name}}</p>                           
                            </v-col>                     
                    </v-expansion-panel-header>
                    <v-expansion-panel-header v-if="cat.status != 'dev'" class="py-0">
                            <v-col cols="1" class="px-0">
                                <img  :src="require('../assets/img/' + cat.img)">
                            </v-col>
                            <v-col cols="10" class="px-0">
                               <p class="subtitle-1 font-weight-medium mb-0">{{cat.name}}</p>                           
                            </v-col>                     
                    </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-list>
                                <v-list-item-group>                            
                                    <v-list-item
                                    v-for="(item, i) in cat.items"
                                    :key="i"
                                    @click="itemclk(item.lnk, item.route)">
                                        <v-list-item-icon>
                                            <img :src="require('../assets/img/' + item.img)">
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title v-text="item.name"></v-list-item-title>                                    
                                        </v-list-item-content>
                                    </v-list-item> 
                                </v-list-item-group>
                            </v-list>                        
                        </v-expansion-panel-content>
                    </v-expansion-panel>                
                </v-expansion-panels>
            </v-card>
        </v-row>
    </v-container>
</template>


<script>
import axios from 'axios';
  export default {
    data: () => ({
        usrid: '',
        title: "Заявки на доступ к ИТ услугам",
        sub_message: "Для получения доступа к сервису или услуге, выберите нужую категорию, а затем услугу, после заполнения необходимых полей формы заявка будет отправлена на согласование отвественных сотрудникам. Статус заявки вы можете отслеживать в разделе",
        cats: [
            {
                name: "Нормативно-справочная информация",
                img: "list.png",
                items: [
                    {
                        name: "Редактирование справочника номенклатуры", img:"lists.png", lnk:"../../it-uslugi/uslugi/nsi-001.php"}                    
                ]
                },
            {
                name: "Заявки пользователя",
                img: "user.png",
                items: [
                    {name:"Новый пользователь", img:"user_add.png", route:"/nu001"},
                    {name:"Удаленный доступ (VPN)", img:"vpn.png", route:"/ns001"},
                    {name:"Служебная сотовая связь", img:"sim_add.png", route:"/ph001"},
                    {name:"Установка программного обеспечения", img:"soft.png", route:"/sf001"},
                    {name:"Отключение доступов", img:"user_del.png", route:"/ar001"}                    
                ]
                },
            {
                name: "Заявки 1С",
                img: "1s.png",
                items: [
                    {name:"Предоставление доступа к 1С", img:"1s.png", lnk:"../../it-uslugi/uslugi/1c-001.php"},
                    {name:"Запрос на доработку 1С", img:"1s.png", route:"/1c002"}                    
                ]
                },
            {
                name: "Заявки на закупку",
                img: "soft.png",
                items: [
                    {name:"Закупка техники/оборудования/программного обеспечения", img:"computer_add.png", route:"/rb001"}                                      
                ]
                },
            {
                name: "Заявки на каталог (папку)",
                img: "folders.png",
                items: [
                    {name:"Создание нового каталога (папки)", img:"folder_add.png", lnk:"../request/nc-002"},
                    {name:"Доступ к каталогу (папке)", img:"folder_rep.png", lnk:"../request/nc-003"},
                    {name:"Создание/изменение сетевого каталога (внешние сотрудники)", img:"folder_add.png", lnk:"../request/nc-004"}                   
                ]
                },
            {
                name: "Почта",
                img: "mails.png",
                items: [
                    {name:"Создание/изменение адреса почтовой рассылки", img:"mails.png", lnk:"../../it-uslugi/uslugi/ml-001.php"}                       
                ]
                },
            {
                name: "Заявки в отдел мониторинга -- Development",
                status: "dev",
                img: "mon.png",
                items: [
                    {name:"Корректировка данных в путевых листах 1С", img:"mon_corr.png", route:"/ms001"},
                    {name:"Предоставление доступа к программам мониторинга", img:"mon_accs.png", route:"/ms002"}, 
                    {name:"Заявка о неисправности телематического оборудования", img:"mon_brkn.png", route:"/ms003"},
                    {name:"Добавление основного средства в папку «Техника для выработки»", img:"ms004.png", route:"/ms004"}                         
                ]
                }    
            ],
      inset: true,
      focusable: true,
      tile: true,
    }),
    methods: {
        itemclk: function(lnk, route){
            if(route != null){
                this.$router.push(route);
            } else {
                document.location.href = lnk;
            }
        },
        clkmyreq: function(){
            document.location.href = "/it-uslugi/helpdesk/my_ticket.php";
        }
    },
    mounted() {
         axios
             .get('./ajax/ajax_usr.php', {
                })
                .then(response => (this.usrid = response.data))        
    },
    computed: {
    condition() {
      this.usrid;
      return this.usrid == 1 || this.usrid == 2318 || this.usrid == 2416 || this.usrid == 2385 || this.usrid == 3371 || this.usrid == 1940;
    }
  }
  }
</script>