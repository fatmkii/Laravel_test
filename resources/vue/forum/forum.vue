
<template>
  <div>这是版面：{{ forum_name }}</div>
</template>


<script>
export default {
  components: {},
  props: {},
  data: function () {
    return {
      name: "forum",
      id: this.$route.params.id,
      forum_name: "",
    };
  },
  mounted: function () {
    const config = {
      method: "get",
      url: "/api/forums/" + this.id,
      data: {},
    };
    axios(config).then((response) => {
      if (response.data.code == 200) {
        this.forum_name = response.data.data.name;
        window.document.title = response.data.data.name;
      } else {
        console.log("获取版面内容失败"); //Todo:待完善失败处理
      }
    });
  },
};
</script>