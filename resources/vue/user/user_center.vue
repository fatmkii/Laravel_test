
<template>
  <div>
    <p>你好！别来无恙。</p>
    <p>你的饼干是：{{ binggan }}</p>
    <p>你的奥利奥：{{ user_coin }} 个</p>
    <b-button
      size="md"
      class="my-1 my-sm-0"
      variant="dark"
      @click="logout_handle"
      >退出饼干</b-button
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
      user_coin: 0,
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
          this.$store.commit("AdminStatus_set", 0);
          if (window.localStorage) {
            localStorage.removeItem("Token");
            localStorage.removeItem("Binggan");
          } else {
            throw new Error("此浏览器居然不支持localstorage");
          }
          axios.defaults.headers.Authorization = "";
          window.location.href = "/"; //因为想清空Vuex状态，所以用js原生的重定向，而不是Vuerouter的push
        })
        .catch((error) => alert(error)); // Todo:写异常返回代码
    },
  },
  created() {
    document.title = "个人中心";
    if (localStorage.Token != null && localStorage.Binggan != null) {
      const config = {
        method: "post",
        url: "/api/user/show",
        data: {
          binggan: localStorage.Binggan,
        },
      };
      axios(config)
        .then((response) => {
          this.user_coin = response.data.data.binggan.coin;
        })
        .catch((error) => {
          if (err.response.status === 401) {
            localStorage.clear("Binggan"); //如果遇到401错误(用户未认证)，就清除Binggan和Token
            localStorage.clear("Token");
            axios.defaults.headers.Authorization = "";
          }
          alert(error);
        }); // Todo:写异常返回代码;
    }
  },
};
</script>