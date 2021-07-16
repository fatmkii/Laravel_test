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
  created: function () {
    const config = {
      method: "get",
      url: "/api/forums/",
      data: {},
    };
    axios(config).then((response) => {
      if (response.data.code == 200) {
        this.$store.commit("ForumsData_set", response.data.data);
      } else {
        console.log("获取版面内容失败"); //Todo:待完善失败处理
      }
    });
  },
};
</script>
