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
  methods: {},
  created() {
    if (localStorage.Token != null && localStorage.Binggan != null) {
      this.$store.commit("Token_set", localStorage.Token);
      this.$store.commit("Binggan_set", localStorage.Binggan);
      this.$store.commit("LoginStatus_set", true);
      axios.defaults.headers.Authorization = "Bearer " + localStorage.Token;
    }

    const config = {
      method: "get",
      url: "/api/forums/",
      data: {},
    };
    axios(config)
      .then((response) => {
        this.$store.commit("ForumsData_set", response.data.data);
      })
      .catch((error) => console.log(error)); // Todo:写异常返回代码;
  },
};
</script>
