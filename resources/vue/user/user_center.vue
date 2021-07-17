
<template>
  <div>
    <p>这是个用户页面</p>
    <p>你好！{{ binggan }}，别来无恙。</p>
    <b-button
      size="md"
      class="my-1 my-sm-0"
      variant="dark"
      @click="logout_handle"
      >退出饼干</b-button
    >
    <b-button size="md" class="my-1 my-sm-0" variant="dark" @click="get_user"
      >获得用户信息</b-button
    >
  </div>
</template>


<script>
import { mapState } from "vuex";

export default {
  components: {},
  props: {},
  data: function () {
    return {
      name: "user_center",
    };
  },
  computed: mapState({
    login_status: (state) => state.User.LoginStatus,
    binggan: (state) => state.User.Binggan,
  }),
  methods: {
    logout_handle() {
      const config = {
        method: "post",
        url: "api/logout",
        data: {
          binggan: this.binggan,
        },
      };
      axios(config)
        .then((response) => {
          this.$store.commit("Token_set", "");
          this.$store.commit("Binggan_set", "");
          this.$store.commit("LoginStatus_set", false);
          if (window.localStorage) {
            localStorage.removeItem("Token");
            localStorage.removeItem("Binggan");
          } else {
            throw new Error("此浏览器居然不支持localstorage");
          }
          axios.defaults.headers.Authorization = "";
          window.location.href = "/"; //因为想清空Vuex状态，所以用js原生的重定向，而不是Vuerouter的push
        })
        .catch((error) => console.log(error)); // Todo:写异常返回代码
    },
    get_user() {
      const config = {
        method: "post",
        url: "api/get_user",
        data: {
          binggan: this.binggan,
        },
      };
      axios(config)
        .then((response) => {})
        .catch((error) => console.log(error)); // Todo:写异常返回代码
    },
  },
  mounted() {},
};
</script>