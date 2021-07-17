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
        <b-button variant="success" class="mt-4" @click="register_handle"
          >没有饼干？来一个！</b-button
        >
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
  data() {
    return {
      name: "login_modal",
      binggan_input: "",
    };
  },
  mounted() {
    // this.$bvModal.show('login-modal');
    this.$refs["login-modal"].show();
  },
  methods: {
    login_handle() {
      const config = {
        method: "post",
        url: "api/login",
        data: {
          binggan: this.binggan_input,
        },
      };
      axios(config)
        .then((response) => {
          this.$store.commit("Token_set", response.data.data.token);
          this.$store.commit("Binggan_set", response.data.data.binggan);
          this.$store.commit("LoginStatus_set", true);
          if (window.localStorage) {
            localStorage.Token = response.data.data.token;
            localStorage.Binggan = response.data.data.binggan;
          } else {
            throw new Error("此浏览器居然不支持localstorage");
          }
          axios.defaults.headers.Authorization = "Bearer " + localStorage.Token;
          window.location.href = "/"; //因为想清空Vuex状态，所以用js原生的重定向，而不是Vuerouter的push
        })
        .catch((error) => console.log(error)); // Todo:写异常返回代码
      this.$router.push({ name: "homepage" });
    },
    register_handle() {},
  },
};
</script>