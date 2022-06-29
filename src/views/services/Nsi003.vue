<template>
  <v-container fluid>
    <TitleService />
    <form id="nsi003" name="nsi003" action="submit" novalidate>
      <v-card min-height="800px" class="py-12">
         <v-row>
          <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
            <RqCardTitle
              :title="$router.currentRoute.name"
              :sub_message="sub_message"
            ></RqCardTitle>
            <hr />
            <Input :arrInput="inputs" />
            <v-card-text> * Поля обязательные для заполнения </v-card-text>
            <hr />
            <Buttons
              :input="sendData()"
              :sendButtonDisable="sendButtonDisable"
              :ajax="source"
            />
          </v-card>
        </v-row>
      </v-card>
    </form>
  </v-container>  
</template>

<script>
import { bus } from "@/main.js";
import Api from "@/components/Api.js";
import Input from "@/components/Input.vue";
import TitleService from "@/components/TitleService.vue";
import RqCardTitle from "@/components/RqCardTitle";
import Buttons from "@/components/Buttons.vue";
export default {
  components: {
    TitleService,
    RqCardTitle,
    Input,
    Buttons,
  },
  data: () => ({
    sub_message: "Вы сможете отслеживать статус заявки в разделе",
    sendButtonDisable: false,
    api: new Api(),
    inputs: [],
    //source: "https://portal.ahstep.ru/ahstep/services/ajax/ajax_nsi003.php",
    source: "./ajax/ajax_nsi003.php",
  }),
  created() {
    bus.$on("inputFile", (data) => {
      this.inputs.find((item) => item.type === "F").value = data;
    });
  },
  mounted(){
    this.getFormData();
  },
  methods: {
    getFormData(){
        this.api.getData({
        url: this.source,
        headers: {
          'Content-Type': 'application/json'
        },
        method: "GET",
        params: {
          type: "getForm",
          ib_id: 121
        }
      })
      .then(response => {
        response.data.forEach((item) => {
          item.name = item.name + (item.is_required === "Y" ? "*" : "");
          item.value = "";
          item.cs = "12";
          item.sm = item.md = "6";
          item.items = (item.list_values.length > 0) ? item.list_values : false;
          item.outlined = item.dense = item. solo = true;
          this.inputs.push(item);
        });
      })
    },
    sendData(){
      const formData = new FormData();
      const fileInput = this.inputs.find(item => item.type === "F");
      this.inputs.forEach((element) => {
        if(element.value != null){
          if(element.type === "L"){
            const values = element.value;            
            for (var i = 0; i < values.length; i++) {
              formData.append(element.id + "[" + i + "]" , values[i]);              
            }
          } else {        
            formData.append(element.id, element.value);
          }        
        }
      });
      if(fileInput) {
        const files = fileInput.value;
        for (var i = 0; i < files.length; i++) {
          let file = files[i];
          formData.append("file[" + i + "]", file);
        }
      }  
      return formData;  
    },
  }
}
</script>