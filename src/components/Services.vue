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
                    <p class="text-center pt-4 headline text--primary">{{title}}</p>
					<p class="text-center">{{sub_message}} <v-btn @click="clkmyreq()" color="green lighten-2 white--text" small><v-icon left dark>mdi-format-list-bulleted</v-icon> Мои заявки</v-btn></p>
                </v-card-text>

            <v-expansion-panels
                :accordion="accordion"
                :popout="popout"
                :inset="inset"
                :multiple="multiple"
                :focusable="focusable"
                :disabled="disabled"
                :readonly="readonly"
                :flat="flat"
                :hover="hover"
                :tile="tile"
            >
                <v-expansion-panel
                    v-for="(cat, i) in cats"
                    :key="i"
                >
                    <v-expansion-panel-header class="py-0">
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
                                 @click="itemclk(item.lnk)">
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
  export default {
    data: () => ({
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
                    {name:"Новый пользователь", img:"user_add.png", lnk:"../../it-uslugi/uslugi/nu-001.php"},
                    {name:"Удаленный доступ (VPN)", img:"vpn.png", lnk:"../../it-uslugi/uslugi/ns-001.php"},
                    {name:"Служебная сотовая связь", img:"sim_add.png", lnk:"../../it-uslugi/uslugi/ph-001.php"},
                    {name:"Установка программного обеспечения", img:"soft.png", lnk:"../../it-uslugi/uslugi/sf-001.php"},
                    {name:"Отключение доступов", img:"user_del.png", lnk:"../../it-uslugi/uslugi/ar-001.php"}                    
                ]
                },
            {
                name: "Заявки 1С",
                img: "1s.png",
                items: [
                    {name:"Предоставление доступа к 1С", img:"1s.png", lnk:"../../it-uslugi/uslugi/1c-001.php"},
                    {name:"Запрос на доработку 1С", img:"1s.png", lnk:"../../it-uslugi/uslugi/1c-002.php"}                    
                ]
                },
            {
                name: "Заявки на закупку",
                img: "soft.png",
                items: [
                    {name:"Закупка техники/оборудования/программного обеспечения", img:"computer_add.png", lnk:"../../it-uslugi/uslugi/ws-001.php"},
                    {name:"Заявка на закупку (Dev)", img:"computer_add.png", lnk:"/rb-001"}                                      
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
                    {name:"Создание/изменение адреса почтовой рассылки", img:"mails.png", lnk:""}                       
                ]
                }
            ],
      accordion: false,
      popout: false,
      inset: true,
      multiple: false,
      disabled: false,
      readonly: false,
      focusable: true,
      flat: false,
      hover: false,
      tile: true,
    }),
    methods: {
        itemclk: function(prop){
            console.log(prop);
            document.location.href = prop;
        },
        clkmyreq: function(){
            document.location.href = "/helpdesk/my_ticket.php";
        }
    }
  }
</script>