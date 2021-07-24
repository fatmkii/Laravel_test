
<template>
  <div>
    <div class="row align-items-center mt-3">
      <div class="col-auto h5">
        <b-badge variant="secondary" pill class="float-left">
          {{ forum_id }}
        </b-badge>
        <span id="forum_name">{{ forum_name }}</span>
      </div>
      <div class="col-auto my-2 ml-auto">
        <b-button
          size="sm"
          class="my-1 my-sm-0 d-lg-none"
          variant="success"
          :disabled="!this.$store.state.User.LoginStatus"
          @click="new_thread_botton"
          >发表主题</b-button
        >
        <b-button
          size="md"
          class="my-1 my-sm-0 d-none d-lg-block"
          variant="success"
          :disabled="!this.$store.state.User.LoginStatus"
          @click="new_thread_botton"
          >发表主题</b-button
        >
      </div>
    </div>
    <ForumThreads :forum_id="forum_id" :page="page"></ForumThreads>
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
  watch: {
    // 如果路由有变化，再次获得数据
    $route: "get_threads_data",
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
    get_threads_data() {
      const config = {
        method: "get",
        url: "/api/forums/" + this.forum_id,
        params: {
          page: this.page,
        },
      };
      axios(config)
        .then((response) => {
          this.$store.commit("ThreadsData_set", response.data.threads_data);
          this.$store.commit("ThreadsLoadStatus_set", 1);
        })
        .catch((error) => alert(error)); // Todo:写异常返回代码;
    },
    new_thread_botton() {
      this.$router.push({
        name: "new_thread",
        params: { forum_id: this.forum_id },
      });
    },
  },
  created() {
    this.get_threads_data(); //当page切换时重新获得threads数据
  },
};
</script>