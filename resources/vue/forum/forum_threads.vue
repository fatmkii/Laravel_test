
<template>
  <div v-if="threads_load_status" class="d-none d-lg-block d-xl-block">
    <b-table-simple
      hover
      small
      caption-top
      responsive
      class="threads_table table-striped"
    >
      <b-thead head-variant="light">
        <b-tr class="text-center">
          <b-th width="40%">标题</b-th>
          <b-th width="10%">作者</b-th>
          <b-th width="20%">发表时间</b-th>
          <b-th width="10%">回帖数</b-th>
          <b-th width="20%">最新回复</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-for="thread in threads_data" :key="thread.id">
          <b-td class="text-left" :style="{ color: thread.title_color }">
            <span class="thread_sub_title"> {{ thread.sub_title }}&nbsp; </span>
            <span class="thread_title" @click="reply_view(thread.id)">
              {{ thread.title }}&nbsp;&nbsp;
            </span>
            <router-link
              :to="
                '/thread/' + thread.id + '/' + Math.ceil(thread.posts_num / 200)
              "
              v-if="thread.posts_num > 200"
              style="color: #212529"
              >[{{ Math.ceil(thread.posts_num / 200) }}]</router-link
            ></b-td
          >
          <b-td class="text-center">{{ thread.nickname }}</b-td>
          <b-td class="text-center">{{ thread.created_at }}</b-td>
          <b-td class="text-center">{{ thread.posts_num }}</b-td>
          <b-td class="text-center">{{ thread.updated_at }}</b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </div>
</template>


<script>
import { mapState } from "vuex";

export default {
  components: {},
  props: {
    forum_id: Number,
    page: Number,
  },
  data: function () {
    return {
      name: "forum_threads",
    };
  },
  computed: mapState({
    threads_data: (state) => state.Threads.ThreadsData.data, // 记得ThreadsData要比ForumsData多.threads_data，因为多了分页数据
    threads_load_status: (state) => state.Threads.ThreadsLoadStatus,
  }),
  methods: {
    reply_view(thread_id) {
      this.$router.push({ name: "thread", params: { thread_id: thread_id } });
    },
  },
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
.threads_table {
  background-color: white;
}
</style>>

