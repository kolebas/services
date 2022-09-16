<template>
  <v-container fluid>
    <DialogAfterSendForm :dialog="dialog" :warnMessage="dialogMessage" />
    <form
      id="1C003"
      name="1C003"
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
            <hr />
            <Input :arrInput="inputs" />
            <v-card-text> * Поля обязательные для заполнения </v-card-text>
            <hr />
            <Buttons
              type="submit"
              :btnLoader="btnLoader"
              :sendButtonDisable="sendButtonDisable"
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
import RqCardTitle from "@/components/RqCardTitle";
import DialogAfterSendForm from "@/components/DialogAfterSendForm.vue";
import Buttons from "@/components/Buttons.vue";
import { Form } from "@/utils.js";
export default {
  components: {
    RqCardTitle,
    Input,
    DialogAfterSendForm,
    Buttons,
  },
  data: () => ({
    sub_message: "Вы сможете отслеживать статус заявки в разделе",
    sendButtonDisable: true,
    api: new Api(),
    btnLoader: false,
    inputs: [
      {
        name: "Организация/Кластер*",
        code: "DEPS",
        is_required: "Y",
        type: "select",
        select_arr: [],
        cs: "12",
        sm: "6",
        outlined: true,
        dense: true,
        solo: true,
        value: "",
      },
      {
        name: "Подразделение*",
        code: "SUBDEPS1",
        type: "select",
        visible: false,
        select_arr: [],
        cs: "12",
        sm: "6",
        outlined: true,
        dense: true,
        solo: true,
        value: "",
      },
      {
        name: "Доп. подразделение*",
        code: "SUBDEPS2",
        type: "select",
        select_arr: [],
        visible: false,
        cs: "12",
        sm: "6",
        rule: [(value) => !!value || "Обязательное поле"],
        outlined: true,
        dense: true,
        solo: true,
        value: "",
      },
    ],
    ib_id: 122,
    wf_id: 561,
    dialog: false,
    dialogMessage: "",
    deps: [],
    source: "https://portal.ahstep.ru/ahstep/services/ajax/ajax_services.php",
    //source: "./ajax/ajax_services.php",
  }),
  created() {
    bus.$on("inputFile", (data) => {
      this.inputs.find((item) => item.type === "F").value = data;
    });
    bus.$on("SelectUsr", (data) => {
      this.inputs.find((item) => item.id === data.input_id).value = data.userId;
    })
  },
  mounted() {
    this.getFormData();
    this.getDeps();
  },
  computed: {
    computedDeps() {
      const inputDeps = this.inputs.find((item) => item.code === "DEPS");
      return inputDeps.value;
    },
    computedSubDeps() {
      const inputSubDeps = this.inputs.find((item) => item.code === "SUBDEPS1");
      return inputSubDeps.value;
    },
    computedActions() {
      const inputAction = this.inputs.find(
        (item) => item.code === "FORM_DEYSTVIE"
      );
      if (inputAction && inputAction.value) {
        return inputAction.value;
      }
      return null;
    },
  },
  watch: {
    inputs: {
      handler: function () {
        const form = new Form(this.inputs);
        this.sendButtonDisable = !form.validation();
      },
      deep: true,
    },
    computedDeps: {
      handler: function (newValue) {
        const inputSubDeps = this.inputs.find(
          (item) => item.code === "SUBDEPS1"
        );
        const inputSubDeps2 = this.inputs.find(
          (item) => item.code === "SUBDEPS2"
        );
        const subDepsArr = this.deps.find((item) => item.NAME === newValue);
        if (subDepsArr.DEPS !== undefined) {
          inputSubDeps.visible = true;
          inputSubDeps.select_arr = this.convertObject(subDepsArr.DEPS);
        } else {
          inputSubDeps2.visible = false;
          inputSubDeps2.select_arr = [];
          inputSubDeps.visible = false;
          inputSubDeps.select_arr = [];
        }
      },
    },
    computedSubDeps: {
      handler: function (newValue) {
        const inputSubDeps = this.inputs.find(
          (item) => item.code === "SUBDEPS2"
        );
        const subDepsValue = this.deps.find(
          (item) =>
            item.NAME === this.inputs.find((item) => item.code === "DEPS").value
        );
        const subDepsArr = Object.values(subDepsValue.DEPS);
        if (subDepsArr) {
          inputSubDeps.visible = true;
          inputSubDeps.select_arr = this.convertObject(
            subDepsArr.find((item) => item.NAME === newValue).DEPS
          );
        } else {
          inputSubDeps.visible = false;
          inputSubDeps.select_arr = [];
        }
      },
    },
    computedActions: {
      handler: function (newValue) {
        const inputUser = this.inputs.find(
          (item) => item.code === "FORM_FIO_SOTRUDNIKA"
        );

        if (newValue === "Заменить") {
          if (inputUser) {
            inputUser.visible = true;
            inputUser.is_required = "Y";
          }
        } else {
          inputUser.visible = inputUser.is_required = false;
          inputUser.value = "";
        }
      },
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
            item.value = "";
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
    async getDeps() {
      this.api
        .getData({
          url: "https://portal.ahstep.ru/ahstep/services/ajax/ajax_services.php",
          headers: {
            "Content-Type": "application/json",
          },
          method: "GET",
          params: {
            type: "getSections",
            ib_id: 126,
          },
        })
        .then(async (response) => {
          const data = response.data;
          const inputDeps = this.inputs.find((item) => item.code === "DEPS");
          let inputDepsValue = [];

          for (const [key, value] of Object.entries(data.DEPS)) {
            console.log(`${key} -- ${value.NAME}`);
            inputDepsValue.push(value.NAME);
            this.deps.push(value);
            inputDeps.select_arr.push(value.NAME);
          }
        });
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
    convertObject(obj) {
      let arr = [];
      for (const [key, value] of Object.entries(obj)) {
        console.log(`${key} -- ${value.NAME}`);
        arr.push(value.NAME);
      }
      return arr;
    },
  },
};
</script>