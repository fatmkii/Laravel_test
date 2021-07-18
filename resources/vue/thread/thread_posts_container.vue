
<template>
  <div v-if="posts_load_status">
    <div class="row align-items-center mt-3">
      <div class="col-6 h5">
        <b-badge variant="secondary" pill class="float-left">
          {{ forum_id }}
        </b-badge>
        <span id="forum_name" @click="back_to_forum">{{ forum_name }}</span>
      </div>
      <div class="col-6">
        <ThreadPaginator :thread_id="thread_id"></ThreadPaginator>
      </div>
    </div>
    <div class="post_container">
      <div class="post_title px-2 py-3 h4">标题：{{ thread_title }}</div>
      <div v-for="post_data in posts_data" :key="post_data.id">
        <PostItem :post_data="post_data"></PostItem>
      </div>
    </div>
    <ThreadPaginator :thread_id="thread_id"></ThreadPaginator>
    <div class="h6 my-2">昵称</div>
    <b-form-input id="nickname_input" v-model="nickname_input"></b-form-input>
    <div class="h6 my-2">内容</div>
    <b-form-textarea
      id="post_input"
      v-model="post_input"
      rows="3"
      max-rows="10"
    ></b-form-textarea>
    <div class="row align-items-center mt-3">
      <div class="col-6">
        <b-button variant="success">回复</b-button>
      </div>
      <div class="col-6">放点别的？</div>
    </div>
  </div>
</template>


<script>
import { mapState } from "vuex";
import PostItem from "./post_item.vue";
import ThreadPaginator from "./thread_paginator.vue";
import biaoqingbao from "./biaoqingbao.vue";

export default {
  components: { PostItem, ThreadPaginator, biaoqingbao },
  props: {
    thread_id: Number,
  },
  data: function () {
    return {
      name: "thread_posts_container",
      nickname_input: "= =",
      post_input: "",
    };
  },
  computed: mapState({
    forum_name: (state) => state.Forums.CurrentForumData.name,
    forum_id: (state) => state.Forums.CurrentForumData.id,
    thread_title: (state) => state.Threads.CurrentThreadData.title,
    posts_data: (state) => state.Posts.PostsData.data, // 记得ThreadsData要比ForumsData多.data，因为多了分页数据
    posts_load_status: (state) => state.Posts.PostsLoadStatus,
  }),

  methods: {
    back_to_forum() {
      this.$router.push({ name: "forum", params: { forum_id: this.forum_id } });
    },
  },
  created() {
    this.$store.commit("PostsLoadStatus_set", 0); //避免显示上个ThreadsData
  },
};
</script>

<style lang="scss" scoped>
.post_title {
  background-color: #dff0d8;
}
.post_container {
  background-color: white;
}
#forum_name {
  cursor: pointer;
}
</style>