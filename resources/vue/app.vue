<template>
  <div display:none></div>
</template>

<script>
export default {
  data: function () {
    return {
      name: "app",
    };
  },
  methods: {
    get_forums_data() {
      const config = {
        method: "get",
        url: "/api/forums/",
        data: {},
      };
      axios(config)
        .then((response) => {
          this.$store.commit("ForumsData_set", response.data.data);
        })
        .catch((error) => alert(error)); // Todo:写异常返回代码;}
    },
    get_user_data() {
      if (localStorage.Token != null && localStorage.Binggan != null) {
        this.$store.commit("Token_set", localStorage.Token);
        this.$store.commit("Binggan_set", localStorage.Binggan);
        this.$store.commit("LoginStatus_set", true);
        axios.defaults.headers.Authorization = "Bearer " + localStorage.Token;
        //确认饼干和token是否合法
        const config = {
          method: "post",
          url: "/api/user/show",
          data: {
            binggan: localStorage.Binggan,
          },
        };
        axios(config)
          .then((response) => {
            this.$store.commit("AdminStatus_set", response.data.data.binggan.admin);
          })
          .catch((error) => {
            if (err.response.status === 401) {
              localStorage.clear("Binggan"); //如果遇到401错误(用户未认证)，就清除Binggan和Token
              localStorage.clear("Token");
              axios.defaults.headers.Authorization = "";
            }
            alert('你的饼干好像有问题？请重新登录');
          }); // Todo:写异常返回代码;
      }
    },
  },
  created() {
    this.get_forums_data();
    this.get_user_data();
  },
};
</script>
