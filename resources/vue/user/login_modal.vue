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
        <b-button
          variant="success"
          class="mt-4"
          :disabled="reg_record_TTL > 0"
          @click="register_handle"
          >没有饼干？来一个！
        </b-button>
        <p v-if="reg_record_TTL > 0">
          你需要等待{{
            Math.floor(reg_record_TTL / 86400) + 1
          }}天后才能再次申请新饼干
        </p>
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
      reg_record_TTL: 1, //如果记录不存在，TTL返回-2
    };
  },
  mounted() {
    this.$refs["login-modal"].show();
    const config = {
      method: "get",
      url: "api/user/check_reg_record",
    };
    axios(config)
      .then((response) => {
        this.reg_record_TTL = response.data.data.reg_record_TTL;
      })
      .catch((error) => alert(error)); // Todo:写异常返回代码
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
          if (response.data.code == 200) {
            this.$store.commit("Token_set", response.data.data.token);
            this.$store.commit("Binggan_set", response.data.data.binggan);
            this.$store.commit("LoginStatus_set", true);
            if (window.localStorage) {
              localStorage.Token = response.data.data.token;
              localStorage.Binggan = response.data.data.binggan;
            } else {
              throw new Error("此浏览器居然不支持localstorage");
            }
            axios.defaults.headers.Authorization =
              "Bearer " + localStorage.Token;
            window.location.href = "/"; //因为想清空Vuex状态，所以用js原生的重定向，而不是Vuerouter的push
          } else {
            alert(response.data.message);
          }
        })
        .catch((error) => alert(error)); // Todo:写异常返回代码
      this.$router.push({ name: "homepage" });
    },
    register_handle() {
      const config = {
        method: "post",
        url: "api/user/register",
      };
      axios(config)
        .then((response) => {
          if (response.data.code == 200) {
            this.$store.commit("Token_set", response.data.data.token);
            this.$store.commit("Binggan_set", response.data.data.binggan);
            this.$store.commit("LoginStatus_set", true);
            if (window.localStorage) {
              localStorage.Token = response.data.data.token;
              localStorage.Binggan = response.data.data.binggan;
            } else {
              throw new Error("此浏览器居然不支持localstorage");
            }
            axios.defaults.headers.Authorization =
              "Bearer " + localStorage.Token;
            window.location.href = "/"; //因为想清空Vuex状态，所以用js原生的重定向，而不是Vuerouter的push
          } else {
            alert(response.data.message);
          }
        })
        .catch((error) => alert(error)); // Todo:写异常返回代码
    },
  },
};
</script>