
<template>
  <div>
    <b-carousel
      id="carousel-fade"
      fade
      :interval="10000"
      img-width="825"
      img-height="224"
      v-if="forum_banners && threads_load_status"
      v-show="!banner_hiden"
    >
      <b-carousel-slide
        v-for="banner in JSON.parse(
          this.$store.state.Forums.CurrentForumData.banners
        ).sort(function () {
          return 0.5 - Math.random();
        })"
        :key="banner.id"
        :img-src="banner"
      ></b-carousel-slide>
    </b-carousel>
    <div class="row align-items-center mt-3">
      <div class="col-auto h5 d-none d-lg-block d-xl-block">
        <b-badge variant="secondary" pill class="float-left">
          {{ forum_id }}
        </b-badge>
        <span id="forum_name">{{ forum_name }}</span>
      </div>
      <div class="col-auto h6 d-block d-lg-none d-xl-none">
        <b-badge variant="secondary" pill class="float-left">
          {{ forum_id }}
        </b-badge>
        <span id="forum_name">{{ forum_name }}</span>
      </div>
      <div class="col-auto d-inline-flex">
        <b-form-checkbox v-model="new_window_to_post" switch class="ml-2">
          新窗口打开
        </b-form-checkbox>
        <b-form-checkbox v-model="banner_hiden" switch class="ml-2">
          隐藏版头
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
    <div class="z-sidebar">
      <div class="icon-top" @click="scroll_top">
        <svg
          aria-hidden="true"
          focusable="false"
          data-prefix="fas"
          data-icon="arrow-up"
          class="svg-inline--fa fa-arrow-up fa-w-14"
          role="img"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 448 512"
        >
          <path
            fill="currentColor"
            d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z"
          ></path>
        </svg>
      </div>
      <div class="icon-new-thread" @click="new_thread_botton">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          class="bi bi-chat-dots-fill"
          viewBox="0 0 16 16"
        >
          <path
            d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7zM5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"
          />
        </svg>
      </div>
      <div class="icon-reload" @click="get_threads_data(true)">
        <svg
          aria-hidden="true"
          focusable="false"
          data-prefix="fas"
          data-icon="sync-alt"
          class="svg-inline--fa fa-sync-alt fa-w-16"
          role="img"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 512 512"
        >
          <path
            fill="currentColor"
            d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z"
          ></path>
        </svg>
      </div>
      <div class="icon-down" @click="scroll_bottom">
        <svg
          aria-hidden="true"
          focusable="false"
          data-prefix="fas"
          data-icon="arrow-down"
          class="svg-inline--fa fa-arrow-down fa-w-14"
          role="img"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 448 512"
        >
          <path
            fill="currentColor"
            d="M413.1 222.5l22.2 22.2c9.4 9.4 9.4 24.6 0 33.9L241 473c-9.4 9.4-24.6 9.4-33.9 0L12.7 278.6c-9.4-9.4-9.4-24.6 0-33.9l22.2-22.2c9.5-9.5 25-9.3 34.3.4L184 343.4V56c0-13.3 10.7-24 24-24h32c13.3 0 24 10.7 24 24v287.4l114.8-120.5c9.3-9.8 24.8-10 34.3-.4z"
          ></path>
        </svg>
      </div>
    </div>
  </div>
</template>


<script>
import { mapState } from "vuex";
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
    $route(to) {
      this.get_threads_data();
      this.$store.commit("ThreadsLoadStatus_set", 0);
    },
    new_window_to_post: function () {
      localStorage.setItem(
        "new_window_to_post",
        this.new_window_to_post ? "true" : ""
      );
    },
    banner_hiden: function () {
      localStorage.setItem("banner_hiden", this.banner_hiden ? "true" : "");
    },
  },
  data: function () {
    return {
      name: "forum_page",
      new_window_to_post: false,
      banner_hiden: false,
    };
  },
  computed: {
    ...mapState({
      forum_name: (state) =>
        state.Forums.CurrentForumData.name
          ? state.Forums.CurrentForumData.name
          : "",
      forum_banners: (state) => state.Forums.CurrentForumData.banners,
      threads_load_status: (state) => state.Threads.ThreadsLoadStatus,
    }),
  },
  methods: {
    get_threads_data(remind) {
      const config = {
        method: "get",
        url: "/api/forums/" + this.forum_id,
        params: {
          page: this.page,
          binggan: this.$store.state.User.Binggan,
        },
      };
      axios(config)
        .then((response) => {
          if (response.data.code == 200) {
            this.$store.commit("ThreadsData_set", response.data.threads_data);
            this.$store.commit(
              "CurrentForumData_set",
              response.data.forum_data
            );
            this.$store.commit("ThreadsLoadStatus_set", 1);
            document.title = this.forum_name;
            if (remind) {
              this.$bvToast.toast("已刷新帖子", {
                title: "Done.",
                autoHideDelay: 1500,
                appendToast: true,
              });
            }
          } else {
            alert(response.data.message);
          }
        })
        .catch((error) => alert(error)); // Todo:写异常返回代码;
    },
    new_thread_botton() {
      this.$router.push({
        name: "new_thread",
        params: { forum_id: this.forum_id },
      });
    },
    scroll_bottom() {
      window.scrollTo(0, document.documentElement.scrollHeight);
    },
    scroll_top() {
      window.scrollTo(0, 0);
    },
  },
  created() {
    this.get_threads_data();
    this.$store.commit("ThreadsLoadStatus_set", 0);
    if (localStorage.getItem("new_window_to_post") == null) {
      localStorage.new_window_to_post = "";
    } else {
      this.new_window_to_post = Boolean(localStorage.new_window_to_post);
    }
    if (localStorage.getItem("banner_hiden") == null) {
      localStorage.banner_hiden = "";
    } else {
      this.banner_hiden = Boolean(localStorage.banner_hiden);
    }
  },
};
</script>

<style lang="scss" scoped>
.z-sidebar {
  position: fixed;
  z-index: 999;
  right: 15px;
  top: 40%;
  color: #5fb878;
  width: 40px;
  div {
    width: 32x;
    height: 32px;
    position: relative;
  }
}
.z-sidebar > div + div {
  margin-top: 10px;
}
.z-sidebar > .icon-top,
.z-sidebar > .icon-down,
.z-sidebar > .icon-new-thread,
.z-sidebar .icon-reload {
  width: 24px;
  left: 4px;
  cursor: pointer;
}
</style>>