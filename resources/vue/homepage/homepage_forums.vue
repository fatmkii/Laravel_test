<template>
  <div>
    <p>版列表</p>
    <li v-for="forum in forums_data" :key="forum">{{ forum.name }}</li>
  </div>
</template>


<script>
export default {
  components: {},
  props: {},
  data: function () {
    return {
      name: "homepage_forums",
      forums_data: "",
    };
  },
  methods: {},
  mounted: function () {
    const config = {
      method: "get",
      url: "/api/forums/",
      data: {},
    };
    // 发送 POST 请求
    axios(config).then((response) => {
      if (response.data.code == 200) {
        this.forums_data = response.data.data;
      } else {
      }
    });
  },
};
</script>