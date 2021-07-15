<template>
  <div>
    <b-modal ref="login-modal" id="login-modal">
      <template v-slot:modal-header>
        <h6>导入饼干</h6>
      </template>

      <template v-slot:default>
        <b-input-group class="mt-3">
          <b-form-input
            placeholder="请输入饼干"
            v-model="binggan_input"
          ></b-form-input>
          <b-button
            variant="outline-success"
            type="submit"
            @click="login_handle"
          >
            导入</b-button
          >
        </b-input-group>
        <b-button variant="success" class="mt-4">没有饼干？来一个！</b-button>
      </template>

      <template v-slot:modal-footer="{ cancel }">
        <b-button size="sm" variant="outline-seco ndary" @click="cancel()">
          取消
        </b-button>
      </template>
    </b-modal>
  </div>
</template>


<script>
export default {
  components: {},
  props: {},
  data: function () {
    return {
      name: "login_modal",
      binggan_input: "",
    };
  },
  mounted: function () {
    // this.$bvModal.show('login-modal');
    this.$refs["login-modal"].show();
  },
  methods: {
    login_handle: function () {
      axios
        .get("/sanctum/csrf-cookie")
        .then((response) => {
          const config = {
            method: "post",
            url: "/api/login",
            data: {
              binggan: this.binggan_input,
            },
          };
          axios(config).then((response) => {
            console.log(response);
          });
        })
        .catch((error) => console.log(error));
    },
  },
};
</script>