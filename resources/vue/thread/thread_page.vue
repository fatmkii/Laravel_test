
<template>
  <div>
    <!-- 必须使page渲染进页面，才能触发updated() -->
    <p style="display: none">这是帖子：{{ thread_id }} 第{{ page }}页</p>
    <ThreadPostsContainer :thread_id="thread_id"></ThreadPostsContainer>
  </div>
</template>


<script>
import ThreadPostsContainer from "./thread_posts_container";
import { mapState } from "vuex";

export default {
  components: { ThreadPostsContainer },
  props: {
    thread_id: Number, //来自router，
    page: Number, //来自router
  },
  data: function () {
    return {
      name: "thread_page",
    };
  },
  methods: {
    get_posts_data() {
      const config = {
        method: "get",
        url: "/api/threads/" + this.thread_id,
        params: {
          page: this.page,
        },
      };
      axios(config)
        .then((response) => {
          this.$store.commit("PostsData_set", response.data.posts_data);
          this.$store.commit(
            "CurrentThreadData_set",
            response.data.thread_data
          );
          this.$store.commit("CurrentForumData_set", response.data.forum_data);
          this.$store.commit("PostsLoadStatus_set", 1);
        })
        .catch((error) => alert(error)); // Todo:写异常返回代码;
    },
  },
  updated() {
    this.get_posts_data(); //当page切换时重新获得posts数据
  },
  created() {
    this.get_posts_data(); //当page切换时重新获得posts数据
  },
};
</script>

