
<template>
  <div class="d-block d-lg-none d-xl-none my-2">
    <div class="threads_table_header my-2 py-1 text-center">
      <span style="font-size: 1.25rem">主题</span>
    </div>
    <div v-show="threads_load_status">
      <div
        class="threads_container"
        v-for="thread in threads_data"
        :key="thread.id"
      >
        <div class="text-left my-1 py-1" :style="{ color: thread.title_color }">
          <span class="thread_sub_title"> {{ thread.sub_title }}&nbsp; </span>
          <router-link
            class="thread_title"
            :to="'/thread/' + thread.id"
            :style="{ color: thread.title_color }"
            :target="router_target"
          >
            {{ thread.title }}&nbsp;&nbsp;
          </router-link>
          <router-link
            :to="
              '/thread/' + thread.id + '/' + Math.ceil(thread.posts_num / 200)
            "
            v-if="thread.posts_num > 200"
            style="color: #212529"
            >[{{ Math.ceil(thread.posts_num / 200) }}]</router-link
          >
        </div>
        <div class="my-1 py-1" style="font-size: 0.8rem">
          <span>发帖人：{{ thread.nickname }} </span>
          <span>最新回复：{{ thread.updated_at }}</span>
          <span class="float-right">Re:{{ thread.posts_num }}</span>
        </div>
      </div>
    </div>
    <b-spinner
      id="spinner"
      v-show="!threads_load_status"
      variant="success"
      label="读取中"
    >
    </b-spinner>
  </div>
</template>


<script>
import { mapState } from "vuex";

export default {
  components: {},
  props: {
    forum_id: Number,
    page: Number,
    new_window_to_post: Boolean,
  },
  data: function () {
    return {
      name: "forum_threads_mobile",
    };
  },
  computed: {
    router_target() {
      return this.new_window_to_post ? "_blank" : "false";
    },
    threads_data() {
      if (this.threads_load_status) {
        if (this.$store.state.User.UsePingbici) {
          const title_pingbici = JSON.parse(
            this.$store.state.User.TitlePingbici
          );
          return this.$store.state.Threads.ThreadsData.data.filter((thread) => {
            for (var i = 0; i < title_pingbici.length; i++) {
              var reg = new RegExp(title_pingbici[i], "g");
              if (reg.test(thread.title)) {
                return false;
              }
            }
            return true;
          });
        } else {
          return this.$store.state.Threads.ThreadsData.data; // 记得ThreadsData要比ForumsData多.threads_data，因为多了分页数据
        }
      }
    },
    ...mapState({
      threads_load_status: (state) => state.Threads.ThreadsLoadStatus,
    }),
  },
  methods: {},
  created() {
    this.$store.commit("ThreadsLoadStatus_set", 0); //避免显示上个ThreadsData
  },
};
</script>



<style lang="scss" scoped>
.thread_title {
  cursor: pointer;
  color: #0000ee;
}
.threads_table_header {
  background-color: #e9ecef;
  border-bottom: 1px solid #111;
  border-top: 1px solid #111;
}
.threads_container {
  border-bottom: 1px solid #111;
}
#spinner {
  position: fixed;
  z-index: 999;
  width: 3rem;
  height: 3rem;
  right: 50%;
  top: 40%;
}
</style>>

