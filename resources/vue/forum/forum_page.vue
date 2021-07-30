
<template>
  <div>
    <div class="row align-items-center mt-3">
      <div class="col-auto h5">
        <b-badge variant="secondary" pill class="float-left">
          {{ forum_id }}
        </b-badge>
        <span id="forum_name">{{ forum_name }}</span>
      </div>
      <div class="col-auto">
        <b-form-checkbox v-model="new_window_to_post" switch>
          新窗口打开
        </b-form-checkbox>
      </div>
      <div class="col-auto my-2 ml-auto">
        <span v-if="!this.$store.state.User.LoginStatus">
          请在先<router-link to="/login">导入或领取饼干 </router-link>
          后才能发言喔
        </span>
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
    <ForumThreads
      :forum_id="forum_id"
      :page="page"
      :new_window_to_post="new_window_to_post"
    ></ForumThreads>
    <ForumThreadsMobile
      :forum_id="forum_id"
      :page="page"
      :new_window_to_post="new_window_to_post"
    ></ForumThreadsMobile>
    <ForumPaginator :forum_id="forum_id"></ForumPaginator>
  </div>
</template>


<script>
import ForumThreads from "./forum_threads.vue";
import ForumPaginator from "./forum_paginator.vue";
import ForumThreadsMobile from "./forum_threads_mobile.vue";

export default {
  components: { ForumThreads, ForumPaginator, ForumThreadsMobile },
  props: {
    forum_id: Number, //来自router，
    page: Number, //来自router
  },
  watch: {
    // 如果路由有变化，再次获得数据
    $route: "get_threads_data",
    new_window_to_post: function () {
      localStorage.setItem(
        "new_window_to_post",
        this.new_window_to_post ? "true" : ""
      );
    },
  },
  data: function () {
    return {
      name: "forum_page",
      new_window_to_post: false,
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
    this.get_threads_data();
    if (localStorage.getItem("new_window_to_post") == null) {
      localStorage.new_window_to_post = false;
    } else {
      this.new_window_to_post = localStorage.new_window_to_post;
    }
  },
};
</script>