<template>
    <v-container>
        <h1>Home</h1>
        
        <v-row>
            <v-card
            max-width="55%"
            raised="true"
            class="mx-auto"
            
            >
                <v-list-item-title class="headline mx-4 my-2">Заявки на доступ к ИТ услугам</v-list-item-title>
                <v-list-item-subtitle class="mx-4 my-2">Для получения доступа к сервису или услуге, выберите нужный раздел, а затем услугу, после заполнения необходимых полей формы<br> 
                заявка будет отправлена на согласование отвественных сотрудникам. Статус заявки вы можете отслеживать в разделе</v-list-item-subtitle>
                <hr/>
            
            
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
                            <v-col cols="1" class="py-2">
                                <img :src="cat.img">
                            </v-col>
                            <v-col cols="8">
                                {{cat.name}}                               
                            </v-col>                        
                    </v-expansion-panel-header>
                        <v-expansion-panel-content>
                        <v-list>
                            <v-list-item-group  color="primary">
                                <v-list-item
                                v-for="(item, i) in cat.items"
                                :key="i"
                                >
                                <v-list-item-icon>
                                    <v-icon>mdi-view-module</v-icon>
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
        cats: [
            {
                name: "Нормативно-справочная информация",
                img: "https://portal.ahstep.ru/ahstep/services/img/list.png",
                items: [
                    {
                        name: "Редактирование справочника номенклатуры"}                    
                ]
                },
            {
                name: "Заявки пользователя",
                img: "https://portal.ahstep.ru/ahstep/services/img/user.png",
                items: [
                    {name:"Новый пользователь", img:""},
                    {name:"Удаленный доступ (VPN)", img:""},
                    {name:"Служебная сотовая связь", img:""},
                    {name:"Установка программного обеспечения", img:""},
                    {name:"Отключение доступов", img:""}                    
                ]
                },
            {
                name: "Заявки 1С",
                img: "https://portal.ahstep.ru/ahstep/services/img/1s.png",
                items: [
                    {name:"Предоставление доступа к 1С", img:""},
                    {name:"Запрос на доработку 1С", img:""}                    
                ]
                },
            {
                name: "Заявки на закупку",
                img: "https://portal.ahstep.ru/ahstep/services/img/soft.png",
                items: [
                    {name:"Закупка техники/оборудования/программного обеспечения", img:""}                                      
                ]
                },
            {
                name: "Заявки на каталог (папку)",
                img: "https://portal.ahstep.ru/ahstep/services/img/folder_add.png",
                items: [
                    {name:"Создание нового каталога (папки)", img:""},
                    {name:"Доступ к каталогу (папке)", img:""},
                    {name:"Создание/изменение сетевого каталога (внешние сотрудники)", img:""}                   
                ]
                },
            {
                name: "Почта",
                img: "https://portal.ahstep.ru/ahstep/services/img/mails.png",
                items: [
                    {name:"Создание/изменение адреса почтовой рассылки", img:""}                       
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
  }
</script>