
<template>
  <div>
    <!-- 必须使page渲染进页面，才能触发updated() -->
    <p>这是版面：{{ forum_name }} 第{{ page }}页</p>
    <ForumThreads :forum_id="forum_id"></ForumThreads>
    <ForumPaginator :forum_id="forum_id"></ForumPaginator>
  </div>
</template>


<script>
import ForumThreads from "./forum_threads.vue";
import ForumPaginator from "./forum_paginator.vue";

export default {
  components: { ForumThreads, ForumPaginator },
  props: {
    forum_id: Number, //来自router，
    page: Number, //来自router
  },
  data: function () {
    return {
      name: "forum_page",
    };
  },
  computed: {
    forum_name() {
      var forum_data = this.$store.getters.ForumData(this.forum_id);
      if (forum_data) {
        return forum_data.name;
      } else {
        return "加载中……";
      }
    },
  },
  methods: {
    get_thread_data() {
      const config = {
        method: "get",
        url: "/api/forums/" + this.forum_id,
        params: {
          page: this.page,
        },
      };
      axios(config)
        .then((response) => {
          this.$store.commit("ThreadsData_set", response.data.data);
          this.$store.commit("ThreadsLoadStatus_set", 1);
        })
        .catch((error) => console.log(error)); // Todo:写异常返回代码;
    },
  },
  updated() {
    this.get_thread_data(); //当page切换时重新获得threads数据
  },
  created() {
    this.get_thread_data(); //当page切换时重新获得threads数据
  },
};
</script>