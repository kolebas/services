<template>
  <v-container fluid>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
    <form
      id="nsi004"
      name="nsi004"
      action="submit"
      @submit.prevent="formSend()"
      novalidate
    >
      <v-card min-height="800px" class="py-12">
        <v-row>
          <v-card width="65%" raised class="mx-auto" color="grey lighten-4">
            <RqCardTitle
              :title="$router.currentRoute.name"
              :sub_message="sub_message"
            ></RqCardTitle>
            <v-divider />
            <v-row v-if="!type" class="d-flex justify-center my-4">
              <v-hover v-for="button in buttons" :key="button.id">
                <template v-slot:default="{ hover }">
                  <v-card
                    @click="
                      type = true;
                      ib_id = button.ib_id;
                      wf_id = button.wf_id;
                      getFormData();
                    "
                    min-width="20vw"
                    min-height="10vh"
                    :elevation="hover ? 12 : 6"
                    class="d-flex align-center mx-2 my-2"
                    raised
                    color="grey lighten-5"
                  >
                    <v-card-text class="mx-auto my-auto">
                      <v-icon :color="button.iconColor" large left>
                        {{ button.icon }} </v-icon
                      ><span class="text-h6 font-weight-light">{{
                        button.title
                      }}</span>
                    </v-card-text></v-card
                  >
                </template>
              </v-hover>
            </v-row>
            <div v-if="type">
              <Input :arrInput="inputs" />
              <v-card-text> * Поля обязательные для заполнения </v-card-text>
              <v-divider />
              <Buttons
                :sendButtonDisable="sendButtonDisable"
                type="submit"
                :btnLoader="btnLoader"
              />
            </div>
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
import RqCardTitle from "@/components/RqCardTitle";
import Buttons from "@/components/Buttons.vue";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import { Form } from "@/utils.js";
export default {
  components: {
    RqCardTitle,
    Input,
    Buttons,
    DialogAfterSendForm,
  },
  data: () => ({
    sub_message: "Вы сможете отслеживать статус заявки в разделе",
    sendButtonDisable: true,
    api: new Api(),
    inputs: [],
    dialog: false,
    btnLoader: false,
    type: false,
    buttons: [
      {
        id: 0,
        title: "Создание физ. лица",
        icon: "mdi-account-plus-outline",
        iconColor: "green",
        ib_id: 123,
        wf_id: 537,
      },
      {
        id: 1,
        title: "Редактирование физ. лица",
        icon: "mdi-account-edit-outline",
        iconColor: "orange",
        ib_id: 124,
        wf_id: 540,
      },
    ],
    ib_id: "",
    wf_Id: "",
    dialogMessage: "",
    //source: "https://portal.ahstep.ru/ahstep/services/ajax/ajax_services.php",
    source: "./ajax/ajax_services.php",
  }),
  created() {
    bus.$on("inputFile", (data) => {
      this.inputs.find((item) => item.type === "F").value = data;
    });
  },
  watch: {
    inputs: {
      handler: function () {
        const form = new Form(this.inputs);
        this.sendButtonDisable = !form.validation();
      },
      deep: true,
    },
    changeType(val) {
      const inputsTemp = this.inputs.filter(
        (item) =>
          item.code === "FORM_INN" ||
          item.code === "FORM_SNILS" ||
          item.code === "FORM_MESTO_ROZHDENIYA" ||
          item.code === "FORM_DOKUMENT_UDOSTOVERYAYUSHCHIY_LICHNOST_VID_DOK" ||
          item.code === "FORM_DOKUMENT_UDOSTOVERYAYUSHCHIY_LICHNOST_SERIYA" ||
          item.code === "FORM_DOKUMENT_UDOSTOVERYAYUSHCHIY_LICHNOST_NOMER" ||
          item.code === "FORM_DOKUMENT_UDOSTOVERYAYUSHCHIY_LICHNOST_KEM_VYD" ||
          item.code === "FORM_DOKUMENT_UDOSTOVERYAYUSHCHIY_LICHNOST_DATA_VY" ||
          item.code === "FORM_DOKUMENT_UDOSTOVERYAYUSHCHIY_LICHNOST_KOD_POD" 
      );
      if (val != "Прочее физлицо") {
        inputsTemp.forEach((item) => {
          item.name = item.name.replace(/\*/g, "");
          item.is_required = "Y";
          item.name = item.name + "*";
          item.rule =
            item.is_required === "Y"
              ? [(value) => !!value || "Обязательное поле"]
              : [];
        });
      } else {
        inputsTemp.forEach((item) => {
          item.is_required = "N";
          item.rule = [];
          item.name = item.name.replace(/\*/g, "");
        });
      }
    },
  },
  computed: {
    changeType() {
      const type = this.inputs.find((item) => item.code === "FORM_TIP");
      if (this.inputs.length > 0 && type.value) {
        return type.value;
      }
      return null;
    },
  },
  methods: {
    getFormData() {
      this.api
        .getData({
          url: this.source,
          headers: {
            "Content-Type": "application/json",
          },
          method: "GET",
          params: {
            type: "getForm",
            ib_id: this.ib_id,
          },
        })
        .then((response) => {
          response.data.forEach((item) => {
            item.name = item.name + (item.is_required === "Y" ? "*" : "");
            item.value = null;
            item.multiple = item.multiple === "Y" ? true : false;
            item.visible = item.is_invisible ? false : true;
            item.cs = "12";
            item.sm = item.md = "6";
            item.rule =
              item.is_required === "Y"
                ? [(value) => !!value || "Обязательное поле"]
                : [];
            item.items = item.list_values.length > 0 ? item.list_values : false;
            item.outlined = item.dense = item.solo = true;
            this.inputs.push(item);
          });
        });
    },
    prepareData() {
      const formData = new FormData();
      this.inputs.forEach((element) => {
        if (element.value != null) {
          if (
            (element.type === "L" && element.multiple === true) ||
            element.type === "F"
          ) {
            const values = element.value;
            for (var i = 0; i < values.length; i++) {
              formData.append(element.id + "[" + i + "]", values[i]);
            }
          } else {
            formData.append(element.id, element.value);
          }
        }
      });
      formData.append("ib_id", this.ib_id);
      formData.append("wf_id", this.wf_id);
      return formData;
    },
    formSend() {
      this.btnLoader = true;
      if (!this.sendButtonDisable) {
        this.api
          .sendData({
            url: this.source,
            headers: { "Content-Type": "multipart/form-data" },
            method: "POST",
            data: this.prepareData(),
          })
          .then((response) => {
            if (response.status == 200) {
              this.dialog = true;
              this.dialogMessage =
                "Успешно. Номер вашей заявки: " + response.data;
              this.btnLoader = false;
            }
          })
          .catch((error) => {
            this.dialog = true;
            this.dialogMessage = "Произошла ошибка " + error;
          });
      }
    },
  },
};
</script>

<style>
</style>