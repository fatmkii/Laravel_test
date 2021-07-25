
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
        <PostItem
          :post_data="post_data"
          :binggan_hash="binggan_hash"
          :thread_anti_jingfen="thread_anti_jingfen"
          @quote_click="quote_click_handle"
          @get_posts_data="get_posts_data"
        ></PostItem>
      </div>
    </div>
    <ThreadPaginator :thread_id="thread_id"></ThreadPaginator>
    <div class="h6 my-2">昵称</div>
    <b-form-input id="nickname_input" v-model="nickname_input"></b-form-input>
    <Emoji @emoji_append="emoji_append"></Emoji>
    <div class="h6 my-2">内容</div>
    <b-form-textarea
      id="content_input"
      v-model="content_input"
      rows="3"
      max-rows="10"
      ref="content_input"
      :disabled="!this.$store.state.User.LoginStatus"
      @keyup.ctrl.enter="new_post_handle"
    ></b-form-textarea>
    <div class="row align-items-center mt-3">
      <div class="col-6">
        <b-button
          variant="success"
          :disabled="!this.$store.state.User.LoginStatus"
          v-b-popover.hover.right="'可以Ctrl+Enter喔'"
          @click="new_post_handle"
          >回复
        </b-button>
        <span v-if="!this.$store.state.User.LoginStatus">
          请在先<router-link to="/login">导入或领取饼干</router-link
          >后才能发言喔
        </span>
      </div>
      <div class="col-6"></div>
    </div>
  </div>
</template>


<script>
import { mapState } from "vuex";
import PostItem from "./post_item.vue";
import ThreadPaginator from "./thread_paginator.vue";
import Emoji from "./emoji.vue";

export default {
  components: { PostItem, ThreadPaginator, Emoji },
  props: {
    thread_id: Number, //来自router，
    page: Number, //来自router
  },
  watch: {
    // 如果路由有变化，再次获得数据
    $route: "get_posts_data",
  },
  data: function () {
    return {
      name: "thread_page",
      new_thread_handling: false,
      nickname_input: "= =",
      content_input: "",
    };
  },

  computed: {
    binggan_hash() {
      return sha256(this.$store.state.User.Binggan);
    },
    ...mapState({
      forum_name: (state) => state.Forums.CurrentForumData.name,
      forum_id: (state) => state.Forums.CurrentForumData.id,
      thread_title: (state) => state.Threads.CurrentThreadData.title,
      thread_anti_jingfen: (state) =>
        state.Threads.CurrentThreadData.anti_jingfen,
      posts_data: (state) => state.Posts.PostsData.data, // 记得ThreadsData要比ForumsData多.data，因为多了分页数据
      posts_load_status: (state) => state.Posts.PostsLoadStatus,
    }),
  },
  methods: {
    get_posts_data() {
      const config = {
        method: "get",
        url: "/api/threads/" + this.thread_id,
        params: {
          page: this.page,
          binggan: this.$store.state.User.Binggan,
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
    back_to_forum() {
      this.$router.push({ name: "forum", params: { forum_id: this.forum_id } });
    },
    new_post_handle() {
      this.new_post_handling = true;
      const config = {
        method: "post",
        url: "/api/posts/create",
        data: {
          binggan: this.$store.state.User.Binggan,
          forum_id: this.forum_id,
          thread_id: this.thread_id,
          content: this.content_input,
          nickname: this.nickname_input,
        },
      };
      axios(config)
        .then((response) => {
          if (response.data.code == 200) {
            this.$bvToast.toast(response.data.message, {
              title: "Done.",
              autoHideDelay: 1500,
              appendToast: true,
            });
            this.content_input = "";
            this.new_post_handling = false;
            this.get_posts_data();
          } else {
            this.new_post_handling = false;
            alert(response.data.message);
          }
        })
        .catch((error) => {
          alert(error);
          this.new_post_handling = false;
        }); // Todo:写异常返回代码
    },
    emoji_append(emoji_src) {
      this.content_input += "<img src='" + emoji_src + "' class='emoji_img'>";
      this.$refs.content_input.focus();
    },
    quote_click_handle(quote_content) {
      this.content_input = quote_content;
      document
        .querySelector("#content_input")
        .scrollIntoView({ behavior: "smooth" });
      this.$refs.content_input.focus();
    },
  },
  created() {
    this.get_posts_data();
    // this.$store.commit("PostsLoadStatus_set", 0); //避免显示上个ThreadsData
  },
};
</script>

<style lang="scss" scoped>
.post_title {
  background-color: #dff0d8;
}
.post_container {
  background-color: #eefaee;
}
#forum_name {
  cursor: pointer;
}
</style>